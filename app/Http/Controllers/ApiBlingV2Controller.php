<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Pedido;

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

            //Log::debug('type of listaPedidos ===> '.json_encode($listaPedidos));
            Log::debug('type of listaPedidos ===> '.gettype($listaPedidos));

            foreach($listaPedidos['retorno']['pedidos'] as $pedido){
                log::debug('Pedido ===> '.json_encode($pedido));
                Log::debug('type of listaPedidos ===> '.gettype($listaPedidos));
                $flag = $this->trataDadosPedido($pedido);
                Log::debug('flag ==> '.$flag);
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

        $pedidoFillable = [
            'descontoPed' => $pedido['pedido']['desconto'],
            'observacoesPed' => $pedido['pedido']['observacoes'],
            'observacaointernaPed' => $pedido['pedido']['observacaointerna'],
            'dataPed' => $pedido['pedido']['data'],
            'numeroPedidoPed' => $pedido['pedido']['numero'],
            'numeroOrdemCompraPed' => $pedido['pedido']['numeroOrdemCompra'],
            'vendedorPed' => $pedido['pedido']['vendedor'],
            'valorfretePed' => $pedido['pedido']['valorfrete'],
            'outrasdespesasPed' => $pedido['pedido']['outrasdespesas'],
            'totalprodutosPed' => $pedido['pedido']['totalprodutos'],
            'totalvendaPed' => $pedido['pedido']['totalvenda'],
            'situacaoPed' => $pedido['pedido']['situacao'],
            'dataSaidaPed' => $pedido['pedido']['dataSaida'],
            'lojaPed' => $pedido['pedido']['loja'],
            'numeroPedLoja' => $pedido['pedido']['numeroPedidoLoja'],
            'tipoIntegracaoPed' => $pedido['pedido']['tipoIntegracao'],

            //Dados cliente
            'idClie' => $pedido['pedido']['cliente']['id'],
            'nomeClie' => $pedido['pedido']['cliente']['nome'],
            'docClie' => $pedido['pedido']['cliente']['cnpj'],
            'ieClie' => $pedido['pedido']['cliente']['ie'],
            'indIEClie' => $pedido['pedido']['cliente']['indIEDest'],
            'rgClie' => $pedido['pedido']['cliente']['rg'],
            'enderecoClie' => $pedido['pedido']['cliente']['endereco'],
            'numeroCasaClie' => $pedido['pedido']['cliente']['numero'],
            'complementoClie' => $pedido['pedido']['cliente']['complemento'],
            'cidadeClie' => $pedido['pedido']['cliente']['cidade'],
            'bairroClie' => $pedido['pedido']['cliente']['bairro'],
            'cepClie' => $pedido['pedido']['cliente']['cep'],
            'ufClie' => $pedido['pedido']['cliente']['uf'],
            'emailClie' => $pedido['pedido']['cliente']['email'],
            'celularClie' => $pedido['pedido']['cliente']['celular'],
            'foneClie' => $pedido['pedido']['cliente']['fone'],

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

        Log::debug('fillable ==> '.json_encode($pedidoFillable));
        $pedido = Pedido::select('id')->where('numeroPedLoja', '=', $pedido['pedido']['numeroPedidoLoja'])->first();

        if($pedido > 0){
            Log::debug('Pedido Já existente ==> '.$pedido);
        }else{
            //$pedido = Pedido::create($pedidoFillable);
            Log::debug('vai criar um pedido novo');
            //Log::debug('Novo pedido foi criado! Id ==> '.$pedido['id']);
        }

        //$item = $this->trataItem($pedido); 
        return true;
    }

    private function trataItem($pedido){
        Log::debug('trataItem');

        $listaItens = $pedido['itens'];

        foreach($listaItens as $item){

        }
    }
}
