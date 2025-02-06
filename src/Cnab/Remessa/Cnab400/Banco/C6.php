<?php

namespace Wlrsilveira\LaravelBoletos\Cnab\Remessa\Cnab400\Banco;

use Illuminate\Support\Arr;
use Wlrsilveira\LaravelBoletos\Util;
use Wlrsilveira\LaravelBoletos\Exception\ValidationException;
use Wlrsilveira\LaravelBoletos\Cnab\Remessa\Cnab400\AbstractRemessa;
use Wlrsilveira\LaravelBoletos\Contracts\Boleto\Boleto as BoletoContract;
use Wlrsilveira\LaravelBoletos\Contracts\Cnab\Remessa as RemessaContract;

class C6 extends AbstractRemessa implements RemessaContract
{
    const ESPECIE_DUPLICATA = '01';
    const ESPECIE_DUPLICATA_SERVICO = '02';
    const ESPECIE_NOTA_PROMISSORIA = '03';
    const ESPECIE_NOTA_SEGURO = '04';
    const ESPECIE_RECIBO = '05';
    const ESPECIE_LETRA_CAMBIO = '06';
    const ESPECIE_FICHA_COMPENSACAO = '07';
    const ESPECIE_CARNE = '08';
    const ESPECIE_CONTRATO = '09';
    const ESPECIE_CHEQUE = '10';
    const ESPECIE_COBRANCA_SERIADA = '11';
    const ESPECIE_MENSALIDADE_ESCOLAR = '12';
    const ESPECIE_NOTA_DEBITO = '13';
    const ESPECIE_DOCUMENTO_DIVIDA = '15';
    const ESPECIE_ENCARGOS_CONDOMINAIS = '16';
    const ESPECIE_CONTA_PRESTACAO_SERVICO = '17';
    const ESPECIE_FATURA_CARTAO = '31';
    const ESPECIE_BOLETO_APORTE = '33';
    const ESPECIE_OUTROS = '99';
    const OCORRENCIA_REMESSA = '01';
    const OCORRENCIA_PEDIDO_BAIXA = '02';
    const OCORRENCIA_CONCESSAO_ABATIMENTO = '04';
    const OCORRENCIA_CANC_ABATIMENTO_CONCEDIDO = '05';
    const OCORRENCIA_ALT_VENCIMENTO = '06';
    const OCORRENCIA_PEDIDO_PROTESTO = '09';
    const OCORRENCIA_SUSTAR_PROTESTO_BAIXAR_TITULO = '18';
    const OCORRENCIA_SUSTAR_PROTESTO_MANTER_TITULO = '19';
    const OCORRENCIA_ALT_OUTROS_DADOS = '31';
    const INSTRUCAO_SEM = '00';

    public function __construct(array $params = [])
    {
        parent::__construct($params);
        $this->addCampoObrigatorio('idremessa', 'codigoCliente');
    }

    /**
     * Código do banco
     *
     * @var string
     */
    protected $codigoBanco = BoletoContract::COD_BANCO_C6;

    /**
     * Define as carteiras disponíveis para cada banco
     *
     * @var array
     */
    protected $carteiras = ['10', '20', '30', '40', '60'];

    /**
     * Caracter de fim de linha
     *
     * @var string
     */
    protected $fimLinha = "\r\n";

    /**
     * Caracter de fim de arquivo
     *
     * @var null
     */
    protected $fimArquivo = "\r\n";

    /**
     * Codigo do cliente junto ao banco.
     *
     * @var string
     */
    protected $codigoCliente;

    /**
     * Retorna o codigo do cliente.
     *
     * @return mixed
     */
    public function getCodigoCliente()
    {
        return $this->codigoCliente;
    }

    /**
     * Seta o codigo do cliente.
     *
     * @param mixed $codigoCliente
     *
     * @return C6
     */
    public function setCodigoCliente($codigoCliente)
    {
        $this->codigoCliente = $codigoCliente;

        return $this;
    }

    /**
     * @return C6
     * @throws ValidationException
     */
    protected function header()
    {
        $this->iniciaHeader();

        $this->add(1, 1, '0');
        $this->add(2, 2, '1');
        $this->add(3, 9, 'REMESSA');
        $this->add(10, 11, '01');
        $this->add(12, 20, Util::formatCnab('X', 'COBRANCA', 8));
        $this->add(21, 26, '');
        $this->add(27, 38, Util::formatCnab('9', $this->getCodigoCliente(), 12));
        $this->add(39, 46, '');
        $this->add(47, 76, Util::formatCnab('X', $this->getBeneficiario()->getNome(), 30));
        $this->add(77, 79, $this->getCodigoBanco());
        $this->add(80, 94, '');
        $this->add(95, 100, $this->getDataRemessa('dmy'));
        $this->add(101, 108, '');
        $this->add(109, 120, Util::formatCnab('9', 0, 12));
        $this->add(121, 386, '');
        $this->add(387, 394, Util::formatCnab('9', $this->getIdremessa(), 8));
        $this->add(395, 400, Util::formatCnab('9', 1, 6));

        return $this;
    }

    /**
     * @param \Wlrsilveira\LaravelBoletos\Boleto\Banco\Bradesco $boleto
     *
     * @return C6
     * @throws ValidationException
     */
    public function addBoleto(BoletoContract $boleto)
    {
        $this->boletos[] = $boleto;
        $this->iniciaDetalhe();

        $this->add(1, 1, '1');
        $this->add(2, 3, strlen(Util::onlyNumbers($this->getBeneficiario()->getDocumento())) == 14 ? '02' : '01');
        $this->add(4, 17, Util::formatCnab('9L', $this->getBeneficiario()->getDocumento(), 14));
        $this->add(18, 29, Util::formatCnab('9', $this->getCodigoCliente(), 12));
        $this->add(30, 37, '');
        $this->add(38, 62, Util::formatCnab('X', $boleto->getNumeroControle(), 25)); // numero de controle
        $this->add(63, 74, Util::formatCnab('9', $boleto->getNossoNumero(), 12));
        $this->add(75, 82, '');
        $this->add(83, 85, Util::formatCnab('9', $this->getCodigoBanco(), 3));
        $this->add(86, 106, '');
        $this->add(107, 108, Util::formatCnab('9', $this->getCarteira(), 2));
        $this->add(109, 110, self::OCORRENCIA_REMESSA); // REGISTRO
        if ($boleto->getStatus() == $boleto::STATUS_BAIXA) {
            $this->add(109, 110, self::OCORRENCIA_PEDIDO_BAIXA); // BAIXA
        }
        if ($boleto->getStatus() == $boleto::STATUS_ALTERACAO) {
            $this->add(109, 110, self::OCORRENCIA_ALT_VENCIMENTO); // ALTERAR VENCIMENTO
        }
        if ($boleto->getStatus() == $boleto::STATUS_ALTERACAO_DATA) {
            $this->add(109, 110, self::OCORRENCIA_ALT_VENCIMENTO);
        }
        if ($boleto->getStatus() == $boleto::STATUS_CUSTOM) {
            $this->add(109, 110, sprintf('%2.02s', $boleto->getComando()));
        }
        $this->add(111, 120, Util::formatCnab('X', $boleto->getNumeroDocumento(), 10));
        $this->add(121, 126, $boleto->getDataVencimento()->format('dmy'));
        $this->add(127, 139, Util::formatCnab('9', $boleto->getValor(), 13, 2));
        $this->add(140, 147, '');
        $this->add(148, 149, $boleto->getEspecieDocCodigo());
        $this->add(150, 150, $boleto->getAceite());
        $this->add(151, 156, $boleto->getDataDocumento()->format('dmy'));
        $this->add(157, 158, self::INSTRUCAO_SEM);
        $this->add(159, 160, self::INSTRUCAO_SEM);
        $this->add(161, 173, Util::formatCnab('9', $boleto->getMoraDia(), 13, 2));
        $this->add(174, 179, $boleto->getDesconto() > 0 ? $boleto->getDataDesconto()->format('dmy') : '000000');
        $this->add(180, 192, Util::formatCnab('9', $boleto->getDesconto(), 13, 2));
        $this->add(193, 198, $boleto->getMulta() > 0 ? $boleto->getDataVencimento()->format('dmy') : '000000');
        $this->add(199, 205, '');
        $this->add(206, 218, Util::formatCnab('9', 0, 13, 2));
        $this->add(219, 220, strlen(Util::onlyNumbers($boleto->getPagador()->getDocumento())) == 14 ? '02' : '01');
        $this->add(221, 234, Util::formatCnab('9', Util::onlyNumbers($boleto->getPagador()->getDocumento()), 14));
        $this->add(235, 274, Util::formatCnab('X', $boleto->getPagador()->getNome(), 40));
        $this->add(275, 314, Util::formatCnab('X', $boleto->getPagador()->getEndereco(), 40));
        $this->add(315, 326, Util::formatCnab('X', $boleto->getPagador()->getBairro(), 12));
        $this->add(327, 334, Util::formatCnab('9', Util::onlyNumbers($boleto->getPagador()->getCep()), 8));
        $this->add(335, 349, Util::formatCnab('X', $boleto->getPagador()->getCidade(), 15));
        $this->add(350, 351, Util::formatCnab('X', $boleto->getPagador()->getUf(), 2));
        $this->add(352, 381, '');
        if ($boleto->getSacadorAvalista()) {
            $this->add(352, 381, Util::formatCnab('X', Util::onlyNumbers($boleto->getSacadorAvalista()->getNome()), 30));
        }
        $this->add(382, 382, $boleto->getMulta() > 0 ? '2' : '0');
        $this->add(383, 384, Util::formatCnab('9', (int) ($boleto->getMulta() > 0 ? $boleto->getMulta() : 0), 2));
        $this->add(385, 385, '');
        $this->add(386, 391, $boleto->getDataVencimento()->copy()->addDays((int) $boleto->getJurosApos())->format('dmy'));
        $this->add(392, 393, $boleto->getDiasProtesto('00'));
        $this->add(394, 394, '');
        $this->add(395, 400, Util::formatCnab('9', $this->iRegistros + 1, 6));

        $msgs = array_filter($boleto->getDescricaoDemonstrativo());
        if (count($msgs) > 0) {
            $this->iniciaDetalhe();
            $this->add(1, 1, '2');
            $this->add(2, 81, Util::formatCnab('X', Arr::get($msgs, 0), 80));
            $this->add(82, 161, Util::formatCnab('X', Arr::get($msgs, 1), 80));
            $this->add(162, 241, Util::formatCnab('X', Arr::get($msgs, 2), 80));
            $this->add(242, 321, Util::formatCnab('X', Arr::get($msgs, 3), 80));
            $this->add(322, 365, '');
            $this->add(366, 375, Util::formatCnab('9', substr($boleto->getNossoNumero(), 1, 10), 10));
            $this->add(376, 381, $boleto->getDataVencimento()->format('dmy'));
            $this->add(382, 394, Util::formatCnab('9', $boleto->getValor(), 13, 2));
            $this->add(395, 400, Util::formatCnab('9', $this->iRegistros + 1, 6));
        }

        return $this;
    }

    /**
     * @return C6
     * @throws ValidationException
     */
    protected function trailer()
    {
        $this->iniciaTrailer();

        $this->add(1, 1, '9');
        $this->add(2, 394, '');
        $this->add(395, 400, Util::formatCnab('9', $this->getCount(), 6));

        return $this;
    }
}
