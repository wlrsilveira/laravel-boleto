<?php

namespace Wlrsilveira\LaravelBoletos\Contracts\Boleto;

use Wlrsilveira\LaravelBoletos\Boleto\AbstractBoleto;

interface BoletoAPI extends Boleto
{
    /**
     * Return boleto as a Array.
     *
     * @return array
     */
    public function toAPI();

    /**
     * @param $boleto
     * @param $appends
     *
     * @return AbstractBoleto
     */
    public static function fromAPI($boleto, $appends);
}
