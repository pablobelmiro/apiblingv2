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

        $fillable = [
            'descontoPed' => $pedido['pedido']['desconto'] ?? null,
            'observacoesPed' => $pedido['pedido']['observacoes'] ?? null,
            'observacaointernaPed' => $pedido['pedido']['observacaointerna'] ?? null,
            'dataPed' => $pedido['pedido']['data'],
            'numeroPedidoPed' => $pedido['pedido']['numero'],
            'numeroOrdemCompraPed' => $pedido['pedido']['numeroOrdemCompra'] ?? null,
            'vendedorPed' => $pedido['pedido']['vendedor'] ?? null,
            'valorfretePed' => $pedido['pedido']['valorfrete'] ?? null,
            'outrasdespesasPed' => $pedido['pedido']['outrasdespesas'] ?? null,
            'totalprodutosPed' => $pedido['pedido']['totalprodutos'] ?? null,
            'totalvendaPed' => $pedido['pedido']['totalvenda'] ?? null,
            'situacaoPed' => $pedido['pedido']['situacao'] ?? null,
            'dataSaidaPed' => $pedido['pedido']['dataSaida'] ?? null,
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
            'emailClie' => $pedido['pedido']['cliente']['email'] ?? null,
            'celularClie' => $pedido['pedido']['cliente']['celular'] ?? null,
            'foneClie' => $pedido['pedido']['cliente']['fone'] ?? null,

            //Dados nfe
            'serieNf' => $pedido['pedido']['nota']['serie'] ?? null,
            'numeroNf' => $pedido['pedido']['nota']['numero'] ?? null,
            'dataEmissaoNf' => $pedido['pedido']['nota']['dataEmissao'] ?? null,
            'situacaoNf' => $pedido['pedido']['nota']['situacao'] ?? null,
            'valorNf' => $pedido['pedido']['nota']['valor'] ?? null,
            'chaveAcesso' => $pedido['pedido']['nota']['chaveAcesso'] ?? null,

            //transportadora
            'transportadora' => $pedido['pedido']['transporte']['transportadora'] ?? null,
            'cnpj' => $pedido['pedido']['transporte']['cnpj'] ?? null,
            'tipo_frete' => $pedido['pedido']['transporte']['tipo_frete'] ?? null,
            'qtde_volumes' => $pedido['pedido']['transporte']['qtde_volumes'] ?? null,
            'peso_bruto' => $pedido['pedido']['transporte']['peso_bruto'] ?? null,
            'enderecoEntregaNome' => $pedido['pedido']['transporte']['enderecoEntrega']['Nome'] ?? null,
            'enderecoEntrega' => $pedido['pedido']['transporte']['enderecoEntrega'][''] ?? null,
            'enderecoEntregaNumero' => $pedido['pedido']['transporte']['enderecoEntrega']['Numero'] ?? null,
            'enderecoEntregaComplemento' => $pedido['pedido']['transporte']['enderecoEntrega']['Complemento'] ?? null,
            'enderecoEntregaCidade' => $pedido['pedido']['transporte']['enderecoEntrega']['Cidade'] ?? null,
            'enderecoEntregaBairro' => $pedido['pedido']['transporte']['enderecoEntrega']['Bairro'] ?? null,
            'enderecoEntregaCep' => $pedido['pedido']['transporte']['enderecoEntrega']['Cep'] ?? null,
            'enderecoEntregaUf' => $pedido['pedido']['transporte']['enderecoEntrega']['Uf'] ?? null,
        ];

        Log::debug('fillable ==> '.json_encode($fillable));
        $pedidoModel = Pedido::where('numeroPedLoja', '=', $pedido['pedido']['numeroPedidoLoja'])->first();

        if(!is_null($pedidoModel)){
            Log::debug('Pedido Já existente ==> '.$pedido);
        }else{
            $pedidoModel = Pedido::create($fillable);
        }

        //Tratando outros dados do pedido
        $this->trataItem($pedido, $pedidoModel['id']);
        $this->trataParcela($pedido, $pedidoModel['id']);
        $this->trataDadosVolumes($pedido, $pedidoModel['id']);
    }

    private function trataItem($pedido, $idPedido){
        Log::debug('trataItem');

        $listaItens = $pedido['pedido']['itens'];

        foreach($listaItens as $item){
            $fillable = [
                'id_pedido' => $idPedido,
                'codigo' => $item['item']['codigo'],
                'descricao' => $item['item']['descricao'] ?? null,
                'quantidade' => $item['item']['quantidade'],
                'valorunidade' => $item['item']['valorunidade'],
                'precocusto' => $item['item']['valorunidade'],
                'descontoItem' => $item['item']['descontoItem'],
                'un' => $item['item']['un'],
                'pesoBruto' => $item['item']['pesoBruto'],
                'largura' => $item['item']['largura'],
                'altura' => $item['item']['altura'],
                'profundidade' => $item['item']['profundidade'],
                'descricaoDetalhada' => $item['item']['descricaoDetalhada'] ?? null,
                'unidadeMedida' => $item['item']['unidadeMedida'] ?? null,
                'gtin' => $item['item']['gtin']
            ];

            Log::debug('fillable de itens ===> '.json_encode($fillable));

            // //verificar se já existe o item
            $itemModel = Item::where('id_pedido', '=', $idPedido)
                        ->where('gtin', '=', $item['item']['gtin'])
                        ->get();
            
            if(!is_null($itemModel)){
                //já existe o pedido, apenas atualizar os dados já existente
            }else{
                //Criando um novo pedido
                $itemModel = item::create($fillable);
            }
        }
    }

    private function trataParcela($pedido, $idPedido){
        Log::debug('trataParcela');

        $listaParcelas = $pedido['pedido']['parcelas'] ?? null;

        if(!is_null($listaParcelas)){
            foreach($listaParcelas as $parcela){
                $fillable = [
                    'id_pedido' => $idPedido,
                    'idLancamento' => $parcela['parcela']['idLancamento'] ?? null,
                    'valor' => $parcela['parcela']['valor'] ?? null,
                    'dataVencimento' => $parcela['parcela']['dataVencimento'] ?? null,
                    'obs' => $parcela['parcela']['obs'] ?? null,
                    'destino' => $parcela['parcela']['destino'] ?? null
                ];
    
                Log::debug('fillable de parcela ===> '.json_encode($fillable));
    
                //verificar se já existe o item
                $parcelaModel = Parcela::where('id_pedido', '=', $idPedido)
                            ->where('gtin', '=', $parcela['parcela']['idLancamento'])
                            ->get();
                
                if(!is_null($parcelaModel)){
                    //já existe o pedido, apenas atualizar os dados já existente
                }else{
                    //Criando um novo pedido
                    $parcelaModel = Parcela::create($fillable);
                }
    
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
            //verificar se já existe o item
            $formaPagModel = FormaPagamento::where('id_pedido', '=', $idPedido)
                        ->where('id_formapagamento', '=', $idParcela)
                        ->where('codigo', '=', $formaPag['id'])
                        ->get();
            
            if(!is_null($formaPagModel)){
                //já existe o pedido, apenas atualizar os dados já existente
            }else{
                //Criando um novo pedido
                $formaPagModel = FormaPagamento::create($fillable);
            }
        }
    }

    private function trataDadosVolumes($pedido, $idPedido){
        Log::debug('trataDadosVolumes');

        $volume = $pedido['transporte'] ?? null;
        if(!is_null($volume)){
            
        $fillable = [
            'id_pedido' => $idPedido,
            'transportadora' => $volume['transportadora'],
            'cnpj' => $volume['cnpj'],
            'tipo_frete' => $volume['tipo_frete'],
            'qtde_volumes' => $volume['qtde_volumes'],
            'peso_bruto' => $volume['peso_bruto'],
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

        //verificar se já existe o volume
         $volumeModel = Volume::where('id_pedido', '=', $idPedido)->get();
        
        if(!is_null($volumeModel)){
            //já existe o pedido, apenas atualizar os dados já existente
        }else{
            //Criando um novo pedido
            $volumeModel = Volume::create($fillable);
        }
        }
    }

}
