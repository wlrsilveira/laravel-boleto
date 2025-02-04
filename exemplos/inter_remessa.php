<?php

require 'autoload.php';

$beneficiario = new Eduardokum\LaravelBoleto\Pessoa([
    'nome'      => 'ACME',
    'endereco'  => 'Rua um, 123',
    'cep'       => '99999-999',
    'uf'        => 'UF',
    'cidade'    => 'CIDADE',
    'documento' => '99.999.999/9999-99',
]);

$pagador = new Eduardokum\LaravelBoleto\Pessoa([
    'nome'      => 'Cliente',
    'endereco'  => 'Rua um, 123',
    'bairro'    => 'Bairro',
    'cep'       => '99999-999',
    'uf'        => 'UF',
    'cidade'    => 'CIDADE',
    'documento' => '999.999.999-99',
]);

$boleto = new Eduardokum\LaravelBoleto\Boleto\Banco\Inter([
    'logo'            => realpath(__DIR__ . '/../logos/') . DIRECTORY_SEPARATOR . '077.png',
    'dataVencimento'  => (new Carbon\Carbon())->addDays(),
    'valor'           => 10,
    'multa'           => false,
    'juros'           => false,
    'numero'          => 1,
    'numeroDocumento' => 1,
    'pagador'         => $pagador,
    'beneficiario'    => $beneficiario,
    'conta'           => '123456789',
    'operacao'        => '1234567',
    'aceite'          => 'S',
    'especieDoc'      => 'DM',
]);

$remessa = new Eduardokum\LaravelBoleto\Cnab\Remessa\Cnab400\Banco\Inter([
    'idRemessa'    => 1,
    'agencia'      => '0001',
    'conta'        => '123456789',
    'carteira'     => 112,
    'beneficiario' => $beneficiario,
]);
$remessa->addBoleto($boleto);

echo $remessa->save(__DIR__ . DIRECTORY_SEPARATOR . 'arquivos' . DIRECTORY_SEPARATOR . 'inter.txt', true);
