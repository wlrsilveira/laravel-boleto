<?php

namespace Wlrsilveira\LaravelBoletos\Boleto\Banco;

use Wlrsilveira\LaravelBoletos\Util;
use Wlrsilveira\LaravelBoletos\CalculoDV;
use Wlrsilveira\LaravelBoletos\Boleto\AbstractBoleto;
use Wlrsilveira\LaravelBoletos\Contracts\Boleto\Boleto;
use Wlrsilveira\LaravelBoletos\Exception\ValidationException;
use Wlrsilveira\LaravelBoletos\Contracts\Boleto\Boleto as BoletoContract;

class Delbank extends AbstractBoleto implements BoletoContract
{
    /**
     * Código do banco
     *
     * @var string
     */
    protected $codigoBanco = Boleto::COD_BANCO_DELCRED;

    /**
     * Define as carteiras disponíveis para este banco
     * '112' => Com registro - Emissão na Internet | '121' => Com Registro
     *
     * @var array
     */
    protected $carteiras = ['112', '121'];

    /**
     * Trata-se de código utilizado para identificar mensagens especificas ao cedente, sendo
     * que o mesmo consta no cadastro do Banco, quando não houver código cadastrado preencher
     * com zeros "000".
     *
     * @var int
     */
    protected $cip = '000';

    /**
     * Variaveis adicionais.
     *
     * @var array
     */
    public $variaveis_adicionais = [
        'cip'        => '000',
        'mostra_cip' => true,
    ];

    /**
     * Espécie do documento, coódigo para remessa
     *
     * @var string
     */
    protected $especiesCodigo240 = [
        'DM' => '01', //Duplicata
        'NP' => '02', //Nota Promissória
        'NS' => '03', //Nota de Seguro
        'CS' => '04', //Cobrança Seriada
        'RC' => '05', //Recibo
        'LC' => '10', //Letras de Câmbio
        'ND' => '11', //Nota de Débito
        'DS' => '12', //Duplicata de Serv.
        'BP' => '30', //Boleto de Proposta
        'O'  => '99', //Outros,
    ];

    /**
     * Espécie do documento, coódigo para remessa
     *
     * @var string
     */
    protected $especiesCodigo400 = [
        'DM'  => '01', // Duplicata Mercantil
        'NP'  => '02', // Nota Promissória
        'NS'  => '03', // Nota de Seguro
        'CS'  => '04', // Cobrança Seriada
        'RC'  => '05', // Recibo
        'LC'  => '10', // Letra de Câmbio
        'ND'  => '11', // Nota de Débito
        'DS'  => '12', // Duplicata de Serviço
        'CC'  => '31', // Cartão de Crédito
        'BDP' => '32', // Boleto de Proposta
        'O'   => '99', // Outros
    ];

    /**
     * Mostrar o endereço do beneficiário abaixo da razão e CNPJ na ficha de compensação
     *
     * @var bool
     */
    protected $mostrarEnderecoFichaCompensacao = true;

    /**
     * Gera o Nosso Número.
     *
     * @return string
     */
    protected function gerarNossoNumero()
    {
        return Util::numberFormatGeral($this->getNumero(), 10)
            . CalculoDV::delcredNossoNumero($this->getCarteira(), $this->getNumero());
    }

    /**
     * Seta dia para baixa automática
     *
     * @param int $baixaAutomatica
     *
     * @return Delbank
     * @throws ValidationException
     */
    public function setDiasBaixaAutomatica($baixaAutomatica)
    {
        if ($this->getDiasProtesto() > 0) {
            throw new ValidationException('Você deve usar dias de protesto ou dias de baixa, nunca os 2');
        }
        $baixaAutomatica = (int) $baixaAutomatica;
        $this->diasBaixaAutomatica = $baixaAutomatica > 0 ? $baixaAutomatica : 0;

        return $this;
    }

    /**
     * Método que retorna o nosso numero usado no boleto. alguns bancos possuem algumas diferenças.
     *
     * @return string
     */
    public function getNossoNumeroBoleto()
    {
        return Util::numberFormatGeral($this->getCarteira(), 2) . ' / ' . substr_replace($this->getNossoNumero(), '-', -1, 0);
    }

    /**
     * Método para gerar o código da posição de 20 a 44
     *
     * @return string
     */
    protected function getCampoLivre()
    {
        if ($this->campoLivre) {
            return $this->campoLivre;
        }

        $campoLivre = Util::numberFormatGeral($this->getAgencia(), 4);
        $campoLivre .= Util::numberFormatGeral($this->getCarteira(), 2);
        $campoLivre .= Util::numberFormatGeral($this->getNumero(), 11);
        $campoLivre .= Util::numberFormatGeral($this->getConta(), 7);
        $campoLivre .= '0';

        return $this->campoLivre = $campoLivre;
    }

    /**
     * Método onde qualquer boleto deve extender para gerar o código da posição de 20 a 44
     *
     * @param $campoLivre
     *
     * @return array
     */
    public static function parseCampoLivre($campoLivre)
    {
        return [
            'convenio'        => null,
            'agenciaDv'       => null,
            'contaCorrenteDv' => null,
            'agencia'         => substr($campoLivre, 0, 4),
            'carteira'        => substr($campoLivre, 4, 2),
            'nossoNumero'     => substr($campoLivre, 6, 11),
            'nossoNumeroDv'   => null,
            'nossoNumeroFull' => substr($campoLivre, 6, 11),
            'contaCorrente'   => substr($campoLivre, 17, 7),
        ];
    }

    /**
     * Define o campo CIP do boleto
     *
     * @param int $cip
     * @return Delbank
     */
    public function setCip($cip)
    {
        $this->cip = $cip;
        $this->variaveis_adicionais['cip'] = $this->getCip();

        return $this;
    }

    /**
     * Retorna o campo CIP do boleto
     *
     * @return string
     */
    public function getCip()
    {
        return Util::numberFormatGeral($this->cip, 3);
    }
}
