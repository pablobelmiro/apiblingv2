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


        //inicalizando variáveis
        $dtAtual = Carbon::now();
        $dtInicial = $dtAtual->addDay(-7)->copy();
        $token = ''; //Aqui vai o seu token para usuário API
        $endpoint = 'https://bling.com.br/Api/v2/pedidos/json/';
        $dtEmissaoFormatada = strval($dtInicial).' TO '.strval($dtAtual);
        $idSituacao = [6,9];

        Log::debug("dtInicial => ".$dtInicial. ' dtAtual => '.$dtAtual);

        /*
        $response = Http::get($endpoint, [
            'apikey' => $token,
            'filters=dataEmissao' => $dtEmissaoFormatada.';idSituacao['.$idSituacao.']'
        ]);

        if($response->successful()){

            $listaPedidos = collect($response['retorno']['pedidos']); //separando apenas lista de pedidos

            $listaPedidos->each(function ($pedido, $key){
                $pedido = Pedido::where('numeroPedidoLoja', '=', $pedido['numeroPedidoLoja']);
            });
        }else{
            $mensagem = [
                'mensagem' => 'Requisição na API Bling V2 não foi possível ser realizada',
                'retorno' => $response->json()
            ];
            
            return response()->json($mensagem);
        }*/
    }
}
