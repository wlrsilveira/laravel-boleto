<?php

namespace Wlrsilveira\LaravelBoletos\Cnab\Retorno\Cnab240\Banco;

use Illuminate\Support\Arr;
use Wlrsilveira\LaravelBoletos\Util;
use Wlrsilveira\LaravelBoletos\Contracts\Cnab\RetornoCnab240;
use Wlrsilveira\LaravelBoletos\Exception\ValidationException;
use Wlrsilveira\LaravelBoletos\Cnab\Retorno\Cnab240\AbstractRetorno;
use Wlrsilveira\LaravelBoletos\Contracts\Boleto\Boleto as BoletoContract;

class Bradesco extends AbstractRetorno implements RetornoCnab240
{
    /**
     * Código do banco
     *
     * @var string
     */
    protected $codigoBanco = BoletoContract::COD_BANCO_BRADESCO;

    /**
     * Array com as ocorrencias do banco;
     *
     * @var array
     */
    private $ocorrencias = [
        '02' => 'Entrada Confirmada',
        '03' => 'Entrada Rejeitada',
        '04' => 'Transferência de Carteira/Entrada',
        '05' => 'Transferência de Carteira/Baixa',
        '06' => 'Liquidação',
        '07' => 'Confirmação do Recebimento da Instrução de Desconto',
        '08' => 'Confirmação do Recebimento do Cancelamento do Desconto',
        '09' => 'Baixa',
        '11' => 'Títulos em Carteira (Em Ser)',
        '12' => 'Confirmação Recebimento Instrução de Abatimento',
        '13' => 'Confirmação Recebimento Instrução de Cancelamento Abatimento',
        '14' => 'Confirmação Recebimento Instrução Alteração de Vencimento',
        '15' => 'Franco de Pagamento',
        '17' => 'Liquidação Após Baixa ou Liquidação Título Não Registrado b/c',
        '19' => 'Confirmação Recebimento Instrução de Protesto',
        '20' => 'Confirmação Recebimento Instrução de Sustação de Protesto',
        '23' => 'Remessa a Cartório (Aponte em Cartório)',
        '24' => 'Retirada de Cartório e Manutenção em Carteira',
        '25' => 'Protestado e Baixado (Baixa por Ter Sido Protestado)',
        '26' => 'Instrução Rejeitada (utilizar serviço Negativação)',
        '27' => 'Confirmação do Pedido de Alteração de Outros Dados',
        '28' => 'Débito de Tarifas/Custas',
        '29' => 'Ocorrências do Pagador (Não Tratar DDA)',
        '30' => 'Alteração de Dados Rejeitada',
        '33' => 'Confirmação da Alteração dos Dados do Rateio de Crédito',
        '34' => 'Confirmação do Cancelamento dos Dados do Rateio de Crédito',
        '35' => 'Confirmação do Desagendamento do Débito Automático',
        '36' => 'Confirmação de envio de e-mail/SMS (Não Tratar)',
        '37' => 'Envio de e-mail/SMS rejeitado (Não tratar)',
        '38' => 'Confirmação de alteração do Prazo Limite de Recebimento',
        '39' => 'Confirmação de Dispensa de Prazo Limite de Recebimento',
        '40' => 'Confirmação da alteração do número do título dado pelo beneficiario',
        '41' => 'Confirmação da alteração do número controle do Participante',
        '42' => 'Confirmação da alteração dos dados do Pagador',
        '43' => 'Confirmação da alteração dos dados do Sacador/Avalista',
        '44' => 'Título pago com cheque devolvido',
        '45' => 'Título pago com cheque compensado',
        '46' => 'Instrução para cancelar protesto confirmada',
        '47' => 'Instrução para protesto para fins falimentares confirmada',
        '48' => 'Confirmação de instrução de transferência de carteira/modalidade de cobrança',
        '49' => 'Alteração de contrato de cobrança',
        '50' => 'Título pago com cheque pendente de liquidação',
        '51' => 'Título DDA reconhecido pelo pagador',
        '52' => 'Título DDA não reconhecido pelo pagador',
        '53' => 'Título DDA recusado pela CIP',
        '54' => 'Confirmação da Instrução de Baixa de Título Negativado sem Protesto',
        '73' => 'Confirmação recebimento pedido de negativação',
    ];

    /**
     * Array com as possiveis descricoes de baixa e liquidacao.
     *
     * @var array
     */
    private $baixa_liquidacao = [
        '01' => 'Por Saldo',
        '02' => 'Por Conta',
        '03' => 'Liquidação no Guichê de Caixa em Dinheiro',
        '04' => 'Compensação Eletrônica',
        '05' => 'Compensação Convencional',
        '06' => 'Por Meio Eletrônico',
        '07' => 'Após Feriado Local',
        '08' => 'Em Cartório',
        '30' => 'Liquidação no Guichê de Caixa em Cheque',
        '31' => 'Liquidação em banco correspondente',
        '32' => 'Liquidação Terminal de Auto-Atendimento',
        '33' => 'Liquidação na Internet (Home banking)',
        '34' => 'Liquidado Office Banking',
        '35' => 'Liquidado Correspondente em Dinheiro',
        '36' => 'Liquidado Correspondente em Cheque',
        '37' => 'Liquidado por meio de Central de Atendimento (Telefone) Baixa: PELO BANCO)',
        '09' => 'Comandada Banco',
        '10' => 'Comandada Cliente Arquivo',
        '11' => 'Comandada Cliente On-line',
        '12' => 'Decurso Prazo - Cliente',
        '13' => 'Decurso Prazo - Banco',
        '14' => 'Protestado',
        '15' => 'Título Excluído',
    ];

    /**
     * Array com as possiveis rejeicoes do banco.
     *
     * @var array
     */
    private $rejeicoes = [
        '01' => 'Código do Banco Inválido',
        '02' => 'Código do Registro Detalhe Inválido',
        '03' => 'Código do Segmento Inválido',
        '04' => 'Código de Movimento Não Permitido para Carteira',
        '05' => 'Código de Movimento Inválido',
        '06' => 'Tipo/Número de Inscrição do Beneficiario Inválidos',
        '07' => 'Agência/Conta/DV Inválido',
        '08' => 'Nosso Número Inválido',
        '09' => 'Nosso Número Duplicado',
        '10' => 'Carteira Inválida',
        '11' => 'Forma de Cadastramento do Título Inválido',
        '12' => 'Tipo de Documento Inválido',
        '13' => 'Identificação da Emissão do Bloqueto Inválida',
        '14' => 'Identificação da Distribuição do Bloqueto Inválida',
        '15' => 'Características da Cobrança Incompatíveis',
        '16' => 'Data de Vencimento Inválida',
        '17' => 'Data de Vencimento Anterior a Data de Emissão',
        '18' => 'Vencimento fora do prazo da operação da Operação (indicador registro de títulos vencidos há mais de 59 dias)',
        '19' => 'Título a cargo de Bancos Correspondentes com vencimento inferior a XX dias',
        '20' => 'Valor do Título Inválido',
        '21' => 'Espécie do Título Inválida',
        '22' => 'Espécie do Título Não permitida para a carteira',
        '23' => 'Aceita Inválido (Utilizar serviço Negativação)',
        '24' => 'Data da Emissão Inválida',
        '25' => 'Data da Emissão porteior da data de entrada',
        '26' => 'Código de Juros de Mora Inválido',
        '27' => 'Valor/taxa de Juros de Mora Inválido',
        '28' => 'Código do Desconto Inválido',
        '29' => 'Valor do Desconto Maior ou Igual ao Valor do título',
        '30' => 'Desconto a conceder não confere',
        '31' => 'Concessão de Desconto - Já Existe Desconto Anterior',
        '32' => 'Valor do IOF Inválido',
        '33' => 'Valor do Abatimento Inválido',
        '34' => 'Valor do Abatimento Maior ou Igual ao Valor do Título',
        '35' => 'Valor a Conceder Não Confere',
        '36' => 'Concessão de Abatimento - Já Existe Abatimento Anterior',
        '37' => 'Código para Protesto Inválido',
        '38' => 'Prazo para Protesto/Negativação Inválido (alterado)',
        '39' => 'Pedido de Protesto/ Negativação Não Permitido para o Título (alterado)',
        '40' => 'Título com Ordem/pedido de Protesto/Negativação Emitida (o) (alterado)',
        '41' => 'Pedido de Sustação/Excl p/ Título s/ Instr de Protesto/Negativação (alterado)',
        '42' => 'Código para Baixa/Devolução Inválido',
        '43' => 'Prazo para Baixa/Devolução Inválido',
        '44' => 'Código da Moeda Inválido',
        '45' => 'Nome do Pagador Não Informado',
        '46' => 'Tipo/Número de Inscrição do Pagador Inválidos',
        '47' => 'Endereço do Pagador Não Informado',
        '48' => 'CEP Inválido',
        '49' => 'CEP Sem Praça de Cobrança (Não Localizado)',
        '50' => 'CEP Referente a um Banco Correspondente',
        '51' => 'CEP incompatível com a Unidade da Federação',
        '52' => 'Unidade da Federação Inválida',
        '53' => 'Tipo/Número de Inscrição do Pagadorr/Avalista Inválidos',
        '54' => 'Pagadorr/Avalista Não Informado',
        '55' => 'Nosso número no Banco Correspondente Não Informado',
        '56' => 'Código do Banco Correspondente Não Informado',
        '57' => 'Código da Multa Inválido',
        '58' => 'Data da Multa Inválida',
        '59' => 'Valor/Percentual da Multa Inválido',
        '60' => 'Movimento para Título Não Cadastrado',
        '61' => 'Alteração da Agência Cobradora/DV Inválida',
        '62' => 'Tipo de Impressão Inválido',
        '63' => 'Entrada para Título já Cadastrado',
        '64' => 'Número da Linha Inválido',
        '65' => 'Código do Banco para Débito Inválido',
        '66' => 'Agência/Conta/DV para Débito Inválido',
        '67' => 'Dados para Débito incompatível com a Identificação da Emissão do Bloqueto',
        '68' => 'Débito Automático Agendado',
        '69' => 'Débito Não Agendado - Erro nos Dados da Remessa',
        '70' => 'Débito Não Agendado - Pagador Não Consta do Cadastro de Autorizante',
        '71' => 'Débito Não Agendado - Beneficiario Não Autorizado pelo Pagador',
        '72' => 'Débito Não Agendado - Beneficiario Não Participa da Modalidade Débito Automático',
        '73' => 'Débito Não Agendado - Código de Moeda Diferente de Real (R$)',
        '74' => 'Débito Não Agendado - Data Vencimento Inválida',
        '75' => 'Débito Não Agendado, Conforme seu Pedido, Título Não Registrado',
        '76' => 'Débito Não Agendado, Tipo/Num. Inscrição do Debitado, Inválido',
        '77' => 'Transferência para Desconto Não Permitida para a Carteira do Título',
        '78' => 'Data Inferior ou Igual ao Vencimento para Débito Automático',
        '79' => 'Data Juros de Mora Inválido',
        '80' => 'Data do Desconto Inválida',
        '81' => 'Tentativas de Débito Esgotadas - Baixado',
        '82' => 'Tentativas de Débito Esgotadas - Pendente',
        '83' => 'Limite Excedido',
        '84' => 'Número Autorização Inexistente',
        '85' => 'Título com Pagamento Vinculado',
        '86' => 'Seu Número Inválido',
        '87' => 'e-mail/SMS enviado',
        '88' => 'e-mail Lido',
        '89' => 'e-mail/SMS devolvido - endereço de e-mail ou número do celular incorreto',
        '90' => 'e-mail devolvido - caixa postal cheia',
        '91' => 'e-mail/número do celular do pagador não informado',
        '92' => 'Pagador optante por Bloqueto Eletrônico - e-mail não enviado',
        '93' => 'Código para emissão de bloqueto não permite envio de e-mail',
        '94' => 'Código da Carteira inválido para envio e-mail.',
        '95' => 'ntrato não permite o envio de e-mail',
        '96' => 'úmero de contrato inválido',
        '97' => 'Rejeição da alteração do prazo limite de recebimento',
        '98' => 'Rejeição de dispensa de prazo limite de recebimento',
        '99' => 'Rejeição da alteração do número do título dado pelo beneficiario',
        'A1' => 'Rejeição da alteração do número controle do participante',
        'A2' => 'Rejeição da alteração dos dados do pagador',
        'A3' => 'Rejeição da alteração dos dados do pagadorr/avalista',
        'A4' => 'Pagador DDA',
    ];

    /**
     * Roda antes dos metodos de processar
     */
    protected function init()
    {
        $this->totais = [
            'liquidados'  => 0,
            'entradas'    => 0,
            'baixados'    => 0,
            'protestados' => 0,
            'erros'       => 0,
            'alterados'   => 0,
        ];
    }

    /**
     * @param array $header
     *
     * @return bool
     * @throws ValidationException
     */
    protected function processarHeader(array $header)
    {
        $this->getHeader()
            ->setCodBanco($this->rem(1, 3, $header))
            ->setLoteServico($this->rem(4, 7, $header))
            ->setTipoRegistro($this->rem(8, 8, $header))
            ->setTipoInscricao($this->rem(18, 18, $header))
            ->setNumeroInscricao($this->rem(19, 32, $header))
            ->setAgencia($this->rem(53, 57, $header))
            ->setAgenciaDv($this->rem(58, 58, $header))
            ->setConta($this->rem(59, 70, $header))
            ->setContaDv($this->rem(71, 71, $header))
//            ->setContaDv($this->rem(72, 72, $header))
            ->setNomeEmpresa($this->rem(73, 102, $header))
            ->setNomeBanco($this->rem(103, 132, $header))
            ->setCodigoRemessaRetorno($this->rem(143, 143, $header))
            ->setData($this->rem(144, 151, $header))
            ->setNumeroSequencialArquivo($this->rem(158, 163, $header))
            ->setVersaoLayoutArquivo($this->rem(164, 166, $header));

        return true;
    }

    /**
     * @param array $headerLote
     *
     * @return bool
     * @throws ValidationException
     */
    protected function processarHeaderLote(array $headerLote)
    {
        $this->getHeaderLote()
            ->setCodBanco($this->rem(1, 3, $headerLote))
            ->setNumeroLoteRetorno($this->rem(4, 7, $headerLote))
            ->setTipoRegistro($this->rem(8, 8, $headerLote))
            ->setTipoOperacao($this->rem(9, 9, $headerLote))
            ->setTipoServico($this->rem(10, 11, $headerLote))
            ->setVersaoLayoutLote($this->rem(14, 16, $headerLote))
            ->setTipoInscricao($this->rem(18, 18, $headerLote))
            ->setNumeroInscricao($this->rem(19, 33, $headerLote))
            ->setAgencia($this->rem(54, 58, $headerLote))
            ->setAgenciaDv($this->rem(59, 59, $headerLote))
            ->setConta($this->rem(60, 71, $headerLote))
            ->setContaDv($this->rem(72, 72, $headerLote))
//            ->setContaDv($this->rem(73, 73, $headerLote))
            ->setNomeEmpresa($this->rem(74, 103, $headerLote))
            ->setNumeroRetorno($this->rem(184, 191, $headerLote))
            ->setDataGravacao($this->rem(192, 199, $headerLote))
            ->setDataCredito($this->rem(200, 207, $headerLote));

        return true;
    }

    /**
     * @param array $detalhe
     *
     * @return bool
     * @throws ValidationException
     */
    protected function processarDetalhe(array $detalhe)
    {
        $d = $this->detalheAtual();

        if ($this->getSegmentType($detalhe) == 'T') {
            $d->setOcorrencia($this->rem(16, 17, $detalhe))
                ->setOcorrenciaDescricao(Arr::get($this->ocorrencias, $this->detalheAtual()->getOcorrencia(), 'Desconhecida'))
                ->setNossoNumero($this->rem(38, 57, $detalhe))
                ->setCarteira($this->rem(58, 58, $detalhe))
                ->setNumeroDocumento($this->rem(59, 73, $detalhe))
                ->setDataVencimento($this->rem(74, 81, $detalhe))
                ->setValor(Util::nFloat($this->rem(82, 96, $detalhe) / 100, 2, false))
                ->setNumeroControle($this->rem(106, 130, $detalhe))
                ->setPagador([
                    'nome'      => $this->rem(149, 188, $detalhe),
                    'documento' => $this->rem(134, 148, $detalhe),
                ])
                ->setValorTarifa(Util::nFloat($this->rem(199, 213, $detalhe) / 100, 2, false));

            /**
             * ocorrencias
             */
            $msgAdicional = str_split(sprintf('%08s', $this->rem(214, 223, $detalhe)), 2) + array_fill(0, 5, '');
            if ($d->hasOcorrencia('06', '17')) {
                $this->totais['liquidados']++;
                $ocorrencia = Util::appendStrings($d->getOcorrenciaDescricao(), Arr::get($this->baixa_liquidacao, $msgAdicional[0], ''), Arr::get($this->baixa_liquidacao, $msgAdicional[1], ''), Arr::get($this->baixa_liquidacao, $msgAdicional[2], ''), Arr::get($this->baixa_liquidacao, $msgAdicional[3], ''), Arr::get($this->baixa_liquidacao, $msgAdicional[4], ''));
                $d->setOcorrenciaDescricao($ocorrencia);
                $d->setOcorrenciaTipo($d::OCORRENCIA_LIQUIDADA);
            } elseif ($d->hasOcorrencia('02')) {
                $this->totais['entradas']++;
                if (array_search('a4', array_map('strtolower', $msgAdicional)) !== false) {
                    $d->getPagador()->setDda(true);
                }
                $d->setOcorrenciaTipo($d::OCORRENCIA_ENTRADA);
            } elseif ($d->hasOcorrencia('09')) {
                $this->totais['baixados']++;
                $ocorrencia = Util::appendStrings($d->getOcorrenciaDescricao(), Arr::get($this->rejeicoes, $msgAdicional[0], ''), Arr::get($this->rejeicoes, $msgAdicional[1], ''), Arr::get($this->rejeicoes, $msgAdicional[2], ''), Arr::get($this->rejeicoes, $msgAdicional[3], ''), Arr::get($this->rejeicoes, $msgAdicional[4], ''));
                $d->setOcorrenciaDescricao($ocorrencia);
                $d->setOcorrenciaTipo($d::OCORRENCIA_BAIXADA);
            } elseif ($d->hasOcorrencia('25')) {
                $this->totais['protestados']++;
                $d->setOcorrenciaTipo($d::OCORRENCIA_PROTESTADA);
            } elseif ($d->hasOcorrencia('33', '38', '40', '41', '42', '43', '49')) {
                $this->totais['alterados']++;
                $d->setOcorrenciaTipo($d::OCORRENCIA_ALTERACAO);
            } elseif ($d->hasOcorrencia('03', '26', '30')) {
                $this->totais['erros']++;
                $error = Util::appendStrings(Arr::get($this->rejeicoes, $msgAdicional[0], ''), Arr::get($this->rejeicoes, $msgAdicional[1], ''), Arr::get($this->rejeicoes, $msgAdicional[2], ''), Arr::get($this->rejeicoes, $msgAdicional[3], ''), Arr::get($this->rejeicoes, $msgAdicional[4], ''));
                $d->setError($error);
            } else {
                $d->setOcorrenciaTipo($d::OCORRENCIA_OUTROS);
            }
        }

        if ($this->getSegmentType($detalhe) == 'U') {
            $d->setValorMulta(Util::nFloat($this->rem(18, 32, $detalhe) / 100, 2, false))
                ->setValorDesconto(Util::nFloat($this->rem(33, 47, $detalhe) / 100, 2, false))
                ->setValorAbatimento(Util::nFloat($this->rem(48, 62, $detalhe) / 100, 2, false))
                ->setValorIOF(Util::nFloat($this->rem(63, 77, $detalhe) / 100, 2, false))
                ->setValorRecebido(Util::nFloat($this->rem(78, 92, $detalhe) / 100, 2, false))
                ->setValorTarifa($d->getValorRecebido() - Util::nFloat($this->rem(93, 107, $detalhe) / 100, 2, false))
                ->setDataOcorrencia($this->rem(138, 145, $detalhe))
                ->setDataCredito($this->rem(146, 153, $detalhe));
        }

        return true;
    }

    /**
     * @param array $trailer
     *
     * @return bool
     * @throws ValidationException
     */
    protected function processarTrailerLote(array $trailer)
    {
        $this->getTrailerLote()
            ->setLoteServico($this->rem(4, 7, $trailer))
            ->setTipoRegistro($this->rem(8, 8, $trailer))
            ->setQtdRegistroLote((int) $this->rem(18, 23, $trailer))
            ->setQtdTitulosCobrancaSimples((int) $this->rem(24, 29, $trailer))
            ->setValorTotalTitulosCobrancaSimples(Util::nFloat($this->rem(30, 46, $trailer) / 100, 2, false))
            ->setQtdTitulosCobrancaVinculada((int) $this->rem(47, 52, $trailer))
            ->setValorTotalTitulosCobrancaVinculada(Util::nFloat($this->rem(53, 69, $trailer) / 100, 2, false))
            ->setQtdTitulosCobrancaCaucionada((int) $this->rem(70, 75, $trailer))
            ->setValorTotalTitulosCobrancaCaucionada(Util::nFloat($this->rem(76, 92, $trailer) / 100, 2, false))
            ->setQtdTitulosCobrancaDescontada((int) $this->rem(93, 98, $trailer))
            ->setValorTotalTitulosCobrancaDescontada(Util::nFloat($this->rem(99, 115, $trailer) / 100, 2, false));

        return true;
    }

    /**
     * @param array $trailer
     *
     * @return bool
     * @throws ValidationException
     */
    protected function processarTrailer(array $trailer)
    {
        $this->getTrailer()
            ->setNumeroLote($this->rem(4, 7, $trailer))
            ->setTipoRegistro($this->rem(8, 8, $trailer))
            ->setQtdLotesArquivo((int) $this->rem(18, 23, $trailer))
            ->setQtdRegistroArquivo((int) $this->rem(24, 29, $trailer));

        return true;
    }
}
