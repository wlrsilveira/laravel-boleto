<?php

require 'autoload.php';

$beneficiario = new Wlrsilveira\LaravelBoleto\Pessoa([
    'nome'      => 'FUNDO DELCRED MULTISSETORIAL',
    'endereco'  => 'AV. RIO BRANCO, 186',
    'cep'       => '49010-030',
    'uf'        => 'SE',
    'cidade'    => 'CENTRO',
    'documento' => '32.853.772/0001-62',
]);

$pagador = new Wlrsilveira\LaravelBoleto\Pessoa([
    'nome'      => 'PABLO KAWAN SANTOS TRINDADE',
    'endereco'  => 'AV. RIO BRANCO, 186',
    'bairro'    => 'CENTRO',
    'cep'       => '49010-030',
    'uf'        => 'SE',
    'cidade'    => 'ARACAJU',
    'documento' => '000.000.000-00',
]);

$boleto = new Wlrsilveira\LaravelBoleto\Boleto\Banco\Delbank([
    'logo'                => realpath(__DIR__ . '/../logos/') . DIRECTORY_SEPARATOR . '435.png',
    'dataVencimento'      => new Carbon\Carbon(),
    'dataDocumento'       => new Carbon\Carbon('2022-05-23'),
    'valor'               => 10,
    'multa'               => false,
    'juros'               => false,
    'numero'              => 0,
    'numero_controle'     => 'SEUNUMERO',
    'diasBaixaAutomatica' => 5,
    'numeroDocumento'     => 1,
    'pagador'             => $pagador,
    'beneficiario'        => $beneficiario,
    'carteira'            => '112',
    'agencia'             => 19,
    'conta'               => 10138,
    'instrucoes'          => ['instrucao 1', 'instrucao 2', 'instrucao 3', 'instrucao 4', 'instrucao 5'],
    'especieDoc'          => 'DM',
]);

$remessa = new Wlrsilveira\LaravelBoleto\Cnab\Remessa\Cnab400\Banco\Delbank([
    'idRemessa'     => 1,
    'agencia'       => 19,
    'carteira'      => '112',
    'conta'         => 10138,
    'codigoCliente' => '10138DELCREDFUNDOLTD',
    'beneficiario'  => $beneficiario,
]);
$remessa->addBoleto($boleto);

echo $remessa->save(__DIR__ . DIRECTORY_SEPARATOR . 'arquivos' . DIRECTORY_SEPARATOR . 'delbank.112.txt');
