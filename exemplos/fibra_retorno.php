<?php

require 'autoload.php';
$retorno = Wlrsilveira\LaravelBoletos\Cnab\Retorno\Factory::make(__DIR__ . DIRECTORY_SEPARATOR . 'arquivos' . DIRECTORY_SEPARATOR . 'fibra.ret');
$retorno->processar();

echo $retorno->getBancoNome();
dd($retorno->getDetalhe(1));
