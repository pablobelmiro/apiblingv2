<?php

namespace App\Http\Controllers;

use App\Models\FormaPagamento;
use App\Models\Item;
use App\Models\Parcela;
use App\Models\Pedido;
use App\Models\volume;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


class ApiBlingV2Controller extends Controller
{
    public function index(){
        //sua lógica
    }

    public function resgataPedidos(){
        Log::debug("resgataPedidos");

        //inicalizando variáveis
        $dtAtual = Carbon::now();
        $dtInicial = $dtAtual->addDay(-7)->copy();
        $token = ''; //Aqui vai o seu token para usuário API
        $endpoint = 'https://bling.com.br/Api/v2/pedidos/json/';
        $dtEmissaoFormatada = strval($dtInicial).' TO '.strval($dtAtual);
        $idSituacao = '[6,9]';

        $response = Http::get($endpoint, [
            'apikey' => $token,
            'filters=dataEmissao' => $dtEmissaoFormatada.';idSituacao['.$idSituacao.']'
        ]);        

        if($response->successful()){
           
            $listaPedidos = collect($response->json()); //separando apenas lista de pedidos

            foreach($listaPedidos['retorno']['pedidos'] as $pedido){
                $flag = $this->trataDadosPedido($pedido);
            }
        }else{
            $mensagem = [
                'mensagem' => 'Requisição na API Bling V2 não foi possível ser realizada',
                'retorno' => $response->json()
            ];
            
            return response()->json($mensagem);
        }
    }

    private function trataDadosPedido($pedido){
        Log::debug('trataDadosPedido');
        Log::debug('trataDadosPedido pedido ==> '.json_encode($pedido));

        if(isset($pedido['pedido']['numeroPedidoLoja'])){
                // Verifica se 'nota' está presente
            $isset = isset($pedido['pedido']['nota']);

            if (isset($pedido['pedido']['nota'])) {
                Log::debug('TEM DADO DE NOTA FISCAL');
                $serieNf = $pedido['pedido']['nota']['serie'];
                $numeroNf = $pedido['pedido']['nota']['numero'];
                $dataEmissaoNf = date(str_replace(' ', $pedido['pedido']['nota']['dataEmissao'], 'T'));
                $situacaoNf = $pedido['pedido']['nota']['situacao'];
                $valorNf = doubleval($pedido['pedido']['nota']['valorNota']);
                $chaveAcesso = $pedido['pedido']['nota']['chaveAcesso'];
            } else {
                $serieNf = $numeroNf = $dataEmissaoNf = $situacaoNf = $valorNf = $chaveAcesso = null;
            }

            // Verifica se 'transportadora' está presente
            if (isset($pedido['pedido']['transporte'])) {
                Log::debug('TEM DADO DE TRANSPORTADORA');
                $transportadora = $pedido['pedido']['transporte']['transportadora'] ?? null;
                $cnpj = $pedido['pedido']['transporte']['cnpj'] ?? null;
                $tipo_frete = $pedido['pedido']['transporte']['tipo_frete'] ?? null;
                $qtde_volumes = intval($pedido['pedido']['transporte']['qtde_volumes']) ?? null;
                $peso_bruto = doubleval($pedido['pedido']['transporte']['peso_bruto']) ?? null;
                $enderecoEntregaNome = $pedido['pedido']['transporte']['enderecoEntrega']['nome'] ?? null;
                $enderecoEntrega = $pedido['pedido']['transporte']['enderecoEntrega']['endereco'] ?? null;
                $enderecoEntregaNumero = $pedido['pedido']['transporte']['enderecoEntrega']['numero'] ?? null;
                $enderecoEntregaComplemento = $pedido['pedido']['transporte']['enderecoEntrega']['complemento'] ?? null;
                $enderecoEntregaCidade = $pedido['pedido']['transporte']['enderecoEntrega']['cidade'] ?? null;
                $enderecoEntregaBairro = $pedido['pedido']['transporte']['enderecoEntrega']['bairro'] ?? null;
                $enderecoEntregaCep = $pedido['pedido']['transporte']['enderecoEntrega']['cep'] ?? null;
                $enderecoEntregaUf = $pedido['pedido']['transporte']['enderecoEntrega']['uf'] ?? null;
            } else {
                $transportadora = $cnpj = $tipo_frete = $qtde_volumes = $peso_bruto = $enderecoEntregaNome = $enderecoEntrega = $enderecoEntregaNumero = $enderecoEntregaComplemento = $enderecoEntregaCidade = $enderecoEntregaBairro = $enderecoEntregaCep = $enderecoEntregaUf = null;
            }

            $fillable = [
                'descontoPed' => floatval($pedido['pedido']['desconto']) ?? null,
                'observacoesPed' => $pedido['pedido']['observacoes'] ?? null,
                'observacaointernaPed' => $pedido['pedido']['observacaointerna'] ?? null,
                'dataPed' => date($pedido['pedido']['data']),
                'numeroPed' => $pedido['pedido']['numero'],
                'numeroOrdemCompraPed' => $pedido['pedido']['numeroOrdemCompra'] ?? null,
                'vendedorPed' => $pedido['pedido']['vendedor'] ?? null,
                'valorfretePed' => floatval($pedido['pedido']['valorfrete']) ?? null,
                'outrasdespesasPed' => floatval($pedido['pedido']['outrasdespesas']) ?? null,
                'totalprodutosPed' => floatval($pedido['pedido']['totalprodutos']) ?? null,
                'totalvendaPed' => floatval($pedido['pedido']['totalvenda']) ?? null,
                'situacaoPed' => $pedido['pedido']['situacao'] ?? null,
                'dataSaidaPed' => date($pedido['pedido']['dataSaida']) ?? null,
                'lojaPed' => $pedido['pedido']['loja'] ?? null,
                'numeroPedLoja' => $pedido['pedido']['numeroPedidoLoja'] ?? null,
                'tipoIntegracaoPed' => $pedido['pedido']['tipoIntegracao'] ?? null,

                //Dados cliente
                'idClie' => $pedido['pedido']['cliente']['id'],
                'nomeClie' => $pedido['pedido']['cliente']['nome'],
                'docClie' => $pedido['pedido']['cliente']['cnpj'],
                'ieClie' => $pedido['pedido']['cliente']['ie'] ?? null,
                'indIEClie' => $pedido['pedido']['cliente']['indIEDest'] ?? null,
                'rgClie' => $pedido['pedido']['cliente']['rg'] ?? null,
                'enderecoClie' => $pedido['pedido']['cliente']['endereco'],
                'numeroCasaClie' => $pedido['pedido']['cliente']['numero'],
                'complementoClie' => $pedido['pedido']['cliente']['complemento'] ?? null,
                'cidadeClie' => $pedido['pedido']['cliente']['cidade'],
                'bairroClie' => $pedido['pedido']['cliente']['bairro'],
                'cepClie' => $pedido['pedido']['cliente']['cep'],
                'ufClie' => $pedido['pedido']['cliente']['uf'],
                'emailClie' => null,
                'celularClie' => $pedido['pedido']['cliente']['celular'] ?? null,
                'foneClie' => $pedido['pedido']['cliente']['fone'] ?? null,

                //Dados nfe
                'serieNf' => $serieNf,
                'numeroNf' => $numeroNf,
                'dataEmissaoNf' => $dataEmissaoNf,
                'situacaoNf' => $situacaoNf,
                'valorNf' => $valorNf,
                'chaveAcesso' => $chaveAcesso,

                //transportadora
                'transportadora' => $transportadora,
                'cnpj' => $cnpj,
                'tipo_frete' => $tipo_frete,
                'qtde_volumes' => $qtde_volumes,
                'peso_bruto' => $peso_bruto,
                'enderecoEntregaNome' => $enderecoEntregaNome,
                'enderecoEntrega' => $enderecoEntrega,
                'enderecoEntregaNumero' => $enderecoEntregaNumero,
                'enderecoEntregaComplemento' => $enderecoEntregaComplemento,
                'enderecoEntregaCidade' => $enderecoEntregaCidade,
                'enderecoEntregaBairro' => $enderecoEntregaBairro,
                'enderecoEntregaCep' => $enderecoEntregaCep,
                'enderecoEntregaUf' => $enderecoEntregaUf
            ];

            Log::debug('fillable Pedido ==> '.json_encode($fillable));

            $pedidoModel = Pedido::where('numeroPedLoja', '=', $pedido['pedido']['numeroPedidoLoja'])->first();

            if(isset($pedidoModel)){
                Log::debug('Pedido existente  ===> '.json_encode($pedidoModel->toArray()));
            }else{
                $pedidoModel = Pedido::create($fillable);
                Log::debug('Novo pedidoModel ===> '.json_encode($pedidoModel->toArray()));
            }

            //Tratando outros dados do pedido
            if(isset($pedido['pedido']['itens'])){
                $this->trataItem($pedido, $pedidoModel['id']);
            }
            if(isset($pedido['pedido']['parcelas'])){
                $this->trataParcela($pedido, $pedidoModel['id']);
            }
            if(isset($pedido['transporte'])){
                $this->trataDadosVolumes($pedido, $pedidoModel['id']);
            }
        }else{
            Log::debug('Pedido não possui código');
        }
                
    }

    private function trataItem($pedido, $idPedido){
        Log::debug('trataItem');

        $listaItens = $pedido['pedido']['itens'];

        foreach($listaItens as $item){
            $fillable = [
                'id_pedido' => $idPedido,
                'codigo' => $item['item']['codigo'],
                'descricao' => $item['item']['descricao'] ?? null,
                'quantidade' => doubleval($item['item']['quantidade']),
                'valorunidade' => doubleval($item['item']['valorunidade']),
                'precocusto' => doubleval($item['item']['valorunidade']),
                'descontoItem' => doubleval($item['item']['descontoItem']),
                'un' => $item['item']['un'],
                'pesoBruto' => doubleval($item['item']['pesoBruto']),
                'largura' => doubleval($item['item']['largura']),
                'altura' => doubleval($item['item']['altura']),
                'profundidade' => doubleval($item['item']['profundidade']),
                'descricaoDetalhada' => $item['item']['descricaoDetalhada'] ?? null,
                'unidadeMedida' => $item['item']['unidadeMedida'] ?? null,
                'gtin' => $item['item']['gtin']
            ];

            Log::debug('fillable de itens ===> '.json_encode($fillable));
            
            $itemModel = Item::updateOrCreate([
                'id_pedido' => $idPedido,
                'gtin' => $item['item']['gtin'],
            ],$fillable);

            Log::debug('itemModel ===> '.json_encode($itemModel));
        }
    }

    private function trataParcela($pedido, $idPedido){
        Log::debug('trataParcela');

        $listaParcelas = $pedido['pedido']['parcelas'];

        if(!is_null($listaParcelas)){
            foreach($listaParcelas as $parcela){
                $fillable = [
                    'id_pedido' => $idPedido,
                    'idLancamento' => $parcela['parcela']['idLancamento'] ?? null,
                    'valor' => doubleval($parcela['parcela']['valor']) ?? null,
                    'dataVencimento' => date(str_replace(' ', $parcela['parcela']['dataVencimento'], 'T')) ?? null,
                    'obs' => $parcela['parcela']['obs'] ?? null,
                    'destino' => $parcela['parcela']['destino'] ?? null
                ];
    
                Log::debug('fillable de parcela ===> '.json_encode($fillable));

                $parcelaModel = Parcela::updateOrCreate([
                            'id_pedido' => $idPedido,
                            'idLancamento' => $parcela['parcela']['idLancamento']
                        ],$fillable);

                Log::debug('parcelaModel ===> '.json_encode($parcelaModel));
    
                $this->trataFormaPagamento($parcela, $parcelaModel['id'], $idPedido);
            }
        }
    }

    private function trataFormaPagamento($parcela, $idParcela, $idPedido){
        Log::debug('trataFormaPagamento');

        $formaPag = $parcela['parcela']['forma_pagamento'] ?? null;

        if(!is_null($formaPag)){
            $fillable = [
                'id_pedido' => $idPedido,
                'id_formapagamento' => $idParcela,
                'codigo' => $formaPag['id'] ?? null,
                'descricao' => $formaPag['descricao'] ?? null,
                'codigoFiscal' => $formaPag['codigoFiscal'] ?? null
            ];
    
            Log::debug('fillable de formaPag ===> '.json_encode($fillable));

            $formaPagModel = FormaPagamento::updateOrCreate([
                'id_pedido' => $idPedido,
                'id_formapagamento' => $idParcela,
                'codigo' => $formaPag['id']
            ],$fillable);

            Log::debug('formaPagModel ===> '.json_encode($formaPagModel));
        }
    }

    private function trataDadosVolumes($pedido, $idPedido){
        Log::debug('trataDadosVolumes');

        $volume = $pedido['transporte'];

        if(!is_null($volume)){    
        $fillable = [
            'id_pedido' => $idPedido,
            'transportadora' => $volume['transportadora'],
            'cnpj' => $volume['cnpj'],
            'tipo_frete' => $volume['tipo_frete'],
            'qtde_volumes' => doubleval($volume['qtde_volumes']),
            'peso_bruto' => doubleval($volume['peso_bruto']),
            'nome' => $volume['nome'],
            'endereco' => $volume['endereco'],
            'numero' => $volume['numero'],
            'complemento' => $volume['complemento'],
            'cidade' => $volume['cidade'],
            'bairro' => $volume['bairro'],
            'cep' => $volume['cep'],
            'uf' => $volume['uf']
        ];

        Log::debug('fillable de dados do volume ===> '.json_encode($fillable));
        
         $volumeModel = FormaPagamento::updateOrCreate([
            'id_pedido' => $idPedido
        ],$fillable);

        Log::debug('volumeModel ===> '.json_encode($volumeModel));
        }
    }

}
