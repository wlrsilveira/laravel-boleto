<?php

namespace Wlrsilveira\LaravelBoletos\Contracts\Cnab\Retorno\Cnab240;

interface Trailer
{
    /**
     * @return mixed
     */
    public function getTipoRegistro();

    /**
     * @return mixed
     */
    public function getNumeroLoteRemessa();

    /**
     * @return mixed
     */
    public function getQtdLotesArquivo();

    /**
     * @return mixed
     */
    public function getQtdRegistroArquivo();

    /**
     * @return array
     */
    public function toArray();
}
