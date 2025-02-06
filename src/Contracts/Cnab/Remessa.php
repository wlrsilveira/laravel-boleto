<?php

namespace Wlrsilveira\LaravelBoletos\Contracts\Cnab;

interface Remessa extends Cnab
{
    public function gerar();
}
