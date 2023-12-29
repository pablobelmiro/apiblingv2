<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pedido extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $table = 'pedidos';
    protected $dates = ['deleted_at'];
    
    protected $fillable = [
        'descontoPed',
        'observacoesPed',
        'observacaointernaPed',
        'dataPed',
        'numeroPed',
        'numeroOrdemCompraPed',
        'vendedorPed',
        'valorfretePed',
        'outrasdespesasPed',
        'totalprodutosPed',
        'totalvendaPed',
        'situacaoPed',
        'dataSaidaPed',
        'lojaPed',
        'numeroPedLoja',
        'tipoIntegracaoPed',
        'idClie',
        'nomeClie',
        'docClie',
        'ieClie',
        'indIEClie',
        'rgClie',
        'enderecoClie',
        'numeroCasaClie',
        'complementoClie',
        'cidadeClie',
        'bairroClie',
        'cepClie',
        'ufClie',
        'emailClie',
        'celularClie',
        'foneClie',
        'serieNf',
        'numeroNf',
        'dataEmissaoNf',
        'situacaoNf',
        'valorNf',
        'chaveAcesso',
        'transportadora',
        'cnpj',
        'tipo_frete',
        'qtde_volumes',
        'peso_bruto',
        'enderecoEntregaNome',
        'enderecoEntrega',
        'enderecoEntregaNumero',
        'enderecoEntregaComplemento',
        'enderecoEntregaCidade',
        'enderecoEntregaBairro',
        'enderecoEntregaCep',
        'enderecoEntregaUf',
    ];

    protected $casts = [
        'descontoPed' => 'double',
        'observacoesPed' => 'string',
        'observacaointernaPed' => 'string',
        'dataPed' => 'date',
        'numeroPedidoPed' => 'string',
        'numeroOrdemCompraPed' => 'string',
        'vendedorPed' => 'string',
        'valorfretePed' => 'double',
        'outrasdespesasPed' => 'double',
        'totalprodutosPed' => 'double',
        'totalvendaPed' => 'double',
        'situacaoPed' => 'string',
        'dataSaidaPed' => 'date',
        'lojaPed' => 'string',
        'numeroPedLoja' => 'string',
        'tipoIntegracaoPed' => 'string',
        'idClie' => 'string',
        'nomeClie' => 'string',
        'docClie' => 'string',
        'ieClie' => 'string',
        'indIEClie' => 'string',
        'rgClie' => 'string',
        'enderecoClie' => 'string',
        'numeroCasaClie' => 'string',
        'complementoClie' => 'string',
        'cidadeClie' => 'string',
        'bairroClie' => 'string',
        'cepClie' => 'string',
        'ufClie' => 'string',
        'emailClie' => 'string',
        'celularClie' => 'string',
        'foneClie' => 'string',
        'categoria' => 'string',
        'serieNf' => 'string',
        'numeroNf' => 'string',
        'dataEmissaoNf' => 'datetime',
        'situacaoNf' => 'string',
        'valorNf' => 'double',
        'chaveAcesso' => 'string',
        'transportadora' => 'string',
        'cnpj' => 'string',
        'tipo_frete' => 'string',
        'qtde_volumes' => 'integer',
        'peso_bruto' => 'string',
        'enderecoEntregaNome' => 'string',
        'enderecoEntrega' => 'string',
        'enderecoEntregaNumero' => 'string',
        'enderecoEntregaComplemento' => 'string',
        'enderecoEntregaCidade' => 'string',
        'enderecoEntregaBairro' => 'string',
        'enderecoEntregaCep' => 'string',
        'enderecoEntregaUf' => 'string'
    ];
}
