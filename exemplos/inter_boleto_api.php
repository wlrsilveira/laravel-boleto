<?php

require 'autoload.php';

$beneficiario = new Wlrsilveira\LaravelBoleto\Pessoa([
    'nome'      => 'ACME',
    'endereco'  => 'Rua um, 123',
    'cep'       => '99999-999',
    'uf'        => 'UF',
    'cidade'    => 'CIDADE',
    'documento' => '99.999.999/9999-99',
]);

$pagador = new Wlrsilveira\LaravelBoleto\Pessoa([
    'nome'      => 'Cliente',
    'endereco'  => 'Rua um, 123',
    'bairro'    => 'Bairro',
    'cep'       => '99999-999',
    'uf'        => 'UF',
    'cidade'    => 'CIDADE',
    'documento' => '999.999.999-99',
]);

$boleto = new Wlrsilveira\LaravelBoleto\Boleto\Banco\Inter([
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

$api = new Wlrsilveira\LaravelBoleto\Api\Banco\Inter([
    'conta'            => '123456789',
    'certificado'      => realpath(__DIR__ . '/certs/') . DIRECTORY_SEPARATOR . 'cert.crt',
    'certificadoChave' => realpath(__DIR__ . '/certs/') . DIRECTORY_SEPARATOR . 'key.key',
]);

$pdf = new Wlrsilveira\LaravelBoleto\Boleto\Render\Pdf();
$pdf->addBoleto($boleto);
$pdf->gerarBoleto($pdf::OUTPUT_SAVE, __DIR__ . DIRECTORY_SEPARATOR . 'arquivos' . DIRECTORY_SEPARATOR . 'inter.pdf');
