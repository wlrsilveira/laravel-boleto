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

$api = new Wlrsilveira\LaravelBoleto\Api\Banco\Inter([
    'versao'           => 2,
    'beneficiario'     => $beneficiario,
    'client_id'        => 'id',
    'client_secret'    => '',
    'certificado'      => realpath(__DIR__ . '/certs/') . DIRECTORY_SEPARATOR . 'cert.crt',
    'certificadoChave' => realpath(__DIR__ . '/certs/') . DIRECTORY_SEPARATOR . 'key.key',
]);
$retorno = $api->retrieveList();

dd($retorno);
//$pdf = new Wlrsilveira\LaravelBoleto\Boleto\Render\Pdf();
//$pdf->addBoletos($retorno);
//$pdf->gerarBoleto($pdf::OUTPUT_SAVE, __DIR__ . DIRECTORY_SEPARATOR . 'arquivos' . DIRECTORY_SEPARATOR . 'inter_lista_v2.pdf');
