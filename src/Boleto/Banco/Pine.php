<?php

namespace Eduardokum\LaravelBoleto\Boleto\Banco;

use Eduardokum\LaravelBoleto\Util;
use Eduardokum\LaravelBoleto\CalculoDV;
use Eduardokum\LaravelBoleto\Boleto\AbstractBoleto;
use Eduardokum\LaravelBoleto\Exception\ValidationException;
use Eduardokum\LaravelBoleto\Contracts\Boleto\Boleto as BoletoContract;

class Pine extends AbstractBoleto implements BoletoContract
{
    public function __construct(array $params = [])
    {
        parent::__construct($params);
        $this->addCampoObrigatorio('range', 'codigoCliente', 'modalidadeCarteira');
    }

    /**
     * Código do banco
     * @var string
     */
    protected $codigoBanco = self::COD_BANCO_PINE;

    /**
     * Define as carteiras disponíveis para este banco
     * @var array
     */
    protected $carteiras = false;

    /**
     * Espécie do documento, código para remessa do CNAB240
     * @var string
     */
    protected $especiesCodigo = [
        'DM'  => '01', //Duplicata Mercantil
        'NP'  => '02', //Nota Promissória
        'CH'  => '03', //Cheque
        'LC'  => '04', //Letra de Câmbio
        'RC'  => '05', //Recibo
        'AP'  => '08', //Apólice de Seguro
        'DS'  => '12', //Duplicata de Serviço
        'CAR' => '31', //Cartão de crédito
        'O'   => '99',  //Outros,
    ];

    /**
     * Linha de local de pagamento
     *
     * @var string
     */
    protected $localPagamento = 'Canais eletrônicos, agências ou correspondentes bancários de todo o BRASIL';

    /**
     * Código de range de composição do nosso numero.
     *
     * @var int
     */
    protected $range = 0;

    /**
     * @var string
     */
    protected $codigoCliente;

    /**
     * Modalidade da carteira
     *
     * @var string
     */
    protected $modalidadeCarteira;

    /**
     * @return int
     */
    public function getRange()
    {
        return $this->range;
    }

    /**
     * @param int $range
     *
     * @return Pine
     */
    public function setRange($range)
    {
        $this->range = (int) $range;

        return $this;
    }

    /**
     * Define o número do codigo do Cliente
     *
     * @param string $codigoCliente
     * @return Pine
     */
    public function setCodigoCliente($codigoCliente)
    {
        $this->codigoCliente = $codigoCliente;

        return $this;
    }

    /**
     * Retorna o número do codigo Cliente
     *
     * @return string
     */
    public function getCodigoCliente()
    {
        return $this->codigoCliente;
    }

    /**
     * Retorna a modalidade da carteira
     *
     * @return mixed
     */
    public function getModalidadeCarteira()
    {
        return $this->modalidadeCarteira;
    }

    /**
     * Seta a modalidade da carteira.
     *
     * @param mixed $modalidadeCarteira
     *
     * @return Pine
     * @throws ValidationException
     */
    public function setModalidadeCarteira($modalidadeCarteira)
    {
        $modalidadeCarteira = Util::upper($modalidadeCarteira);
        if (! in_array($modalidadeCarteira, ['1', '2', '5', '6', 'D'])) {
            throw new ValidationException('Modalidade da carteira inválida');
        }
        $this->modalidadeCarteira = $modalidadeCarteira;

        return $this;
    }

    /**
     * Gera o Nosso Número.
     *
     * @return string
     * @throws ValidationException
     */
    protected function gerarNossoNumero()
    {
        $nn = 0;
        if (Util::upper($this->getModalidadeCarteira()) == 'D') {
            $nn = ((int) $this->getRange()) + ((int) $this->getNumero());
            $nn .= CalculoDV::pineNossoNumero($this->getAgencia(), $this->getCarteira(), $nn);
        }

        return Util::numberFormatGeral($nn, 11);
    }

    /**
     * Método para gerar o código da posição de 20 a 44
     *
     * @return string
     * @throws ValidationException
     */
    protected function getCampoLivre()
    {
        if ($this->campoLivre) {
            return $this->campoLivre;
        }

        $nossoNumero = $this->getNossoNumero();

        $campoLivre = Util::numberFormatGeral($this->getAgencia(), 4);
        $campoLivre .= Util::numberFormatGeral($this->getCarteira(), 3);
        $campoLivre .= Util::numberFormatGeral($this->getCodigoCliente(), 7);
        $campoLivre .= Util::numberFormatGeral($nossoNumero, 11);

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
            'parcela'         => null,
            'agenciaDv'       => null,
            'contaCorrente'   => null,
            'modalidade'      => null,
            'contaCorrenteDv' => null,
            'nossoNumeroDv'   => null,
            'agencia'         => substr($campoLivre, 0, 4),
            'nossa_carteira'  => substr($campoLivre, 4, 3),
            'codigoCliente'   => substr($campoLivre, 7, 7),
            'nossoNumero'     => substr($campoLivre, 14, 11),
            'nossoNumeroFull' => substr($campoLivre, 14, 11),
        ];
    }

    /**
     * @return string
     */
    public function getAgenciaCodigoBeneficiario()
    {
        return sprintf(
            '%s%s / %s%s',
            $this->getAgencia(),
            ! is_null($this->getAgenciaDv()) ? $this->getAgenciaDv() : CalculoDV::pineAgencia($this->getAgencia()),
            $this->getConta(),
            ! is_null($this->getContaDv()) ? $this->getContaDv() : CalculoDV::pineConta($this->getConta())
        );
    }

    /**
     * @return bool
     */
    public function imprimeBoleto()
    {
        return Util::upper($this->getModalidadeCarteira()) == 'D';
    }
}
