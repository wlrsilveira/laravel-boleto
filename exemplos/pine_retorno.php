<?php

require 'autoload.php';
$retorno = Wlrsilveira\LaravelBoleto\Cnab\Retorno\Factory::make(__DIR__ . DIRECTORY_SEPARATOR . 'arquivos' . DIRECTORY_SEPARATOR . 'pine.ret');
$retorno->processar();

echo $retorno->getBancoNome();
dd($retorno->getDetalhes());
