<?php

namespace Wlrsilveira\LaravelBoletos\Contracts\Cnab\Retorno;

interface Detalhe
{
    const OCORRENCIA_LIQUIDADA = 1;
    const OCORRENCIA_BAIXADA = 2;
    const OCORRENCIA_ENTRADA = 3;
    const OCORRENCIA_ALTERACAO = 4;
    const OCORRENCIA_PROTESTADA = 5;
    const OCORRENCIA_OUTROS = 6;
    const OCORRENCIA_ERRO = 9;

    /**
     * @return mixed
     */
    public function getNossoNumero();

    /**
     * @return mixed
     */
    public function getNumeroDocumento();

    /**
     * @return mixed
     */
    public function getOcorrencia();

    /**
     * @return mixed
     */
    public function getOcorrenciaDescricao();

    /**
     * @return mixed
     */
    public function getOcorrenciaTipo();

    /**
     * @param string $format
     *
     * @return mixed
     */
    public function getDataOcorrencia($format = 'd/m/Y');

    /**
     * @param string $format
     *
     * @return mixed
     */
    public function getDataVencimento($format = 'd/m/Y');

    /**
     * @param string $format
     *
     * @return mixed
     */
    public function getDataCredito($format = 'd/m/Y');

    /**
     * @return mixed
     */
    public function getValor();

    /**
     * @return mixed
     */
    public function getValorTarifa();

    /**
     * @return mixed
     */
    public function getValorIOF();

    /**
     * @return mixed
     */
    public function getValorAbatimento();

    /**
     * @return mixed
     */
    public function getValorDesconto();

    /**
     * @return mixed
     */
    public function getValorRecebido();

    /**
     * @return mixed
     */
    public function getValorMora();

    /**
     * @return mixed
     */
    public function getValorMulta();

    /**
     * @return string
     */
    public function getError();

    /**
     * @return mixed
     */
    public function getId();

    /**
     * @return mixed
     */
    public function getPixLocation();

    /**
     * @return mixed
     */
    public function getPixQrCode();

    /**
     * @param $nome
     * @param $cidade
     * @param $force
     * @return mixed
     */
    public function gerarPixCopiaECola($nome, $cidade, $force = false);

    /**
     * @return bool
     */
    public function hasError();

    /**
     * @return bool
     */
    public function hasOcorrencia();

    /**
     * @return array
     */
    public function toArray();
}
