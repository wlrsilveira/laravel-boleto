<?php

require 'autoload.php';
$retorno = Wlrsilveira\LaravelBoletos\Cnab\Retorno\Factory::make(__DIR__ . DIRECTORY_SEPARATOR . 'arquivos' . DIRECTORY_SEPARATOR . 'bb.ret');
$retorno->processar();

echo $retorno->getBancoNome();
dd($retorno->getDetalhes());
