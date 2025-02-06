<?php

namespace Wlrsilveira\LaravelBoletos\Contracts\Boleto\Render;

interface Html
{
    public function getImagemCodigoDeBarras($codigo_barras);

    public function gerarBoleto();

    public function setLocalizacaoPix($localizacao);
}
