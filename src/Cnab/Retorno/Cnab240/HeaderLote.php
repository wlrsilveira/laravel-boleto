<?php

namespace Wlrsilveira\LaravelBoletos\Cnab\Retorno\Cnab240;

use Carbon\Carbon;
use Wlrsilveira\LaravelBoletos\MagicTrait;
use Wlrsilveira\LaravelBoletos\Contracts\Cnab\Retorno\Cnab240\HeaderLote as HeaderLoteContract;

class HeaderLote implements HeaderLoteContract
{
    use MagicTrait;

    /**
     * @var string
     */
    protected $codBanco;

    /**
     * @var string
     */
    protected $numeroLoteRetorno;

    /**
     * @var string
     */
    protected $tipoRegistro;

    /**
     * @var string
     */
    protected $tipoOperacao;

    /**
     * @var string
     */
    protected $tipoServico;

    /**
     * @var string
     */
    protected $versaoLayoutLote;

    /**
     * @var string
     */
    protected $tipoInscricao;

    /**
     * @var string
     */
    protected $numeroInscricao;

    /**
     * @var string
     */
    protected $agenciaDv;

    /**
     * @var string
     */
    protected $codigoCedente;

    /**
     * @var string
     */
    protected $nomeEmpresa;

    /**
     * @var string
     */
    protected $numeroRetorno;

    /**
     * @var Carbon
     */
    protected $dataGravacao;

    /**
     * @var string
     */
    protected $agencia;

    /**
     * @var string
     */
    protected $conta;

    /**
     * @var string
     */
    protected $contaDv;

    /**
     * @var string
     */
    protected $codigoBanco;

    /**
     * @var string
     */
    protected $mensagem_1;

    /**
     * @var Carbon
     */
    protected $dataCredito;

    /**
     * @var string
     */
    protected $convenio;

    /**
     * @return string
     */
    public function getCodigoBanco()
    {
        return $this->codigoBanco;
    }

    /**
     * @param string $codigoBanco
     *
     * @return HeaderLote
     */
    public function setCodigoBanco($codigoBanco)
    {
        $this->codigoBanco = $codigoBanco;

        return $this;
    }

    /**
     * @return string
     */
    public function getTipoRegistro()
    {
        return $this->tipoRegistro;
    }

    /**
     * @param string $tipoRegistro
     *
     * @return HeaderLote
     */
    public function setTipoRegistro($tipoRegistro)
    {
        $this->tipoRegistro = $tipoRegistro;

        return $this;
    }

    /**
     * @return string
     */
    public function getCodBanco()
    {
        return $this->codBanco;
    }

    /**
     * @param string $codBanco
     *
     * @return HeaderLote
     */
    public function setCodBanco($codBanco)
    {
        $this->codBanco = $codBanco;

        return $this;
    }

    /**
     * @return string
     */
    public function getNumeroLoteRetorno()
    {
        return $this->numeroLoteRetorno;
    }

    /**
     * @param string $numeroLoteRetorno
     *
     * @return HeaderLote
     */
    public function setNumeroLoteRetorno($numeroLoteRetorno)
    {
        $this->numeroLoteRetorno = $numeroLoteRetorno;

        return $this;
    }

    /**
     * @return string
     */
    public function getTipoOperacao()
    {
        return $this->tipoOperacao;
    }

    /**
     * @param string $tipoOperacao
     * @return HeaderLote
     */
    public function setTipoOperacao($tipoOperacao)
    {
        $this->tipoOperacao = $tipoOperacao;

        return $this;
    }

    /**
     * @return string
     */
    public function getTipoServico()
    {
        return $this->tipoServico;
    }

    /**
     * @param string $tipoServico
     *
     * @return HeaderLote
     */
    public function setTipoServico($tipoServico)
    {
        $this->tipoServico = $tipoServico;

        return $this;
    }

    /**
     * @return string
     */
    public function getVersaoLayoutLote()
    {
        return $this->versaoLayoutLote;
    }

    /**
     * @param string $versaoLayoutLote
     *
     * @return HeaderLote
     */
    public function setVersaoLayoutLote($versaoLayoutLote)
    {
        $this->versaoLayoutLote = $versaoLayoutLote;

        return $this;
    }

    /**
     * @return string
     */
    public function getTipoInscricao()
    {
        return $this->tipoInscricao;
    }

    /**
     * @param $tipoInscricao
     *
     * @return HeaderLote
     */
    public function setTipoInscricao($tipoInscricao)
    {
        $this->tipoInscricao = $tipoInscricao;

        return $this;
    }

    /**
     * @return string
     */
    public function getNumeroInscricao()
    {
        return $this->numeroInscricao;
    }

    /**
     * @param string $numeroInscricao
     *
     * @return HeaderLote
     */
    public function setNumeroInscricao($numeroInscricao)
    {
        $this->numeroInscricao = $numeroInscricao;

        return $this;
    }

    /**
     * @return string
     */
    public function getCodigoCedente()
    {
        return $this->codigoCedente;
    }

    /**
     * @param string $codigoCedente
     *
     * @return HeaderLote
     */
    public function setCodigoCedente($codigoCedente)
    {
        $this->codigoCedente = $codigoCedente;

        return $this;
    }

    /**
     * @return string
     */
    public function getConvenio()
    {
        return $this->convenio;
    }

    /**
     * @param string $convenio
     *
     * @return HeaderLote
     */
    public function setConvenio($convenio)
    {
        $this->convenio = $convenio;

        return $this;
    }

    /**
     * @return string
     */
    public function getNomeEmpresa()
    {
        return $this->nomeEmpresa;
    }

    /**
     * @param string $nomeEmpresa
     *
     * @return HeaderLote
     */
    public function setNomeEmpresa($nomeEmpresa)
    {
        $this->nomeEmpresa = $nomeEmpresa;

        return $this;
    }

    /**
     * @return string
     */
    public function getMensagem1()
    {
        return $this->mensagem_1;
    }

    /**
     * @param string $format
     *
     * @return string
     */
    public function getDataGravacao($format = 'd/m/Y')
    {
        return $this->dataGravacao instanceof Carbon
            ? $format === false ? $this->dataGravacao : $this->dataGravacao->format($format)
            : null;
    }

    /**
     * @param string $dataGravacao
     *
     * @param string $format
     *
     * @return HeaderLote
     */
    public function setDataGravacao($dataGravacao, $format = 'dmY')
    {
        $this->dataGravacao = trim($dataGravacao, '0 ') ? Carbon::createFromFormat($format, $dataGravacao) : null;

        return $this;
    }

    /**
     * @param string $format
     *
     * @return string
     */
    public function getDataCredito($format = 'd/m/Y')
    {
        return $this->dataCredito instanceof Carbon
            ? $format === false ? $this->dataCredito : $this->dataCredito->format($format)
            : null;
    }

    /**
     * @param string $dataCredito
     *
     * @param string $format
     *
     * @return HeaderLote
     */
    public function setDataCredito($dataCredito, $format = 'dmY')
    {
        $this->dataCredito = trim($dataCredito, '0 ') ? Carbon::createFromFormat($format, $dataCredito) : null;

        return $this;
    }

    /**
     * @return string
     */
    public function getAgencia()
    {
        return $this->agencia;
    }

    /**
     * @param string $agencia
     *
     * @return HeaderLote
     */
    public function setAgencia($agencia)
    {
        $this->agencia = $agencia;

        return $this;
    }

    /**
     * @return string
     */
    public function getAgenciaDv()
    {
        return $this->agenciaDv;
    }

    /**
     * @param string $agenciaDv
     *
     * @return HeaderLote
     */
    public function setAgenciaDv($agenciaDv)
    {
        $this->agenciaDv = $agenciaDv;

        return $this;
    }

    /**
     * @return string
     */
    public function getConta()
    {
        return $this->conta;
    }

    /**
     * @param string $conta
     *
     * @return HeaderLote
     */
    public function setConta($conta)
    {
        $this->conta = $conta;

        return $this;
    }

    /**
     * @return string
     */
    public function getNumeroRetorno()
    {
        return $this->numeroRetorno;
    }

    /**
     * @param string $numeroRetorno
     *
     * @return HeaderLote
     */
    public function setNumeroRetorno($numeroRetorno)
    {
        $this->numeroRetorno = $numeroRetorno;

        return $this;
    }

    /**
     * @return string
     */
    public function getContaDv()
    {
        return $this->contaDv;
    }

    /**
     * @param string $contaDv
     *
     * @return HeaderLote
     */
    public function setContaDv($contaDv)
    {
        $this->contaDv = $contaDv;

        return $this;
    }
}
