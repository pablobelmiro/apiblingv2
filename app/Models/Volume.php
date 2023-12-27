<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class volume extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $table = 'pedidoitem';
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id_pedido',
        'idServico',
        'idOrigem',
        'servico',
        'codigoServico',
        'codigoRastreamento',
        'valorFretePrevisto',
        'remessa',
        'dataSaida',
        'prazoEntregaPrevisto',
        'valorDeclarado',
        'avisoRecebimento',
        'maoPropria',
        'peso',
        'altura',
        'largura',
        'comprimento',
        'diametro',
        'urlRastreamento'
    ];

    protected $casts = [
        'id_pedido' => 'string',
        'idServico' => 'string',
        'idOrigem' => 'string',
        'servico' => 'string',
        'codigoServico' => 'string',
        'codigoRastreamento' => 'string',
        'valorFretePrevisto' => 'float',
        'remessa' => 'string',
        'dataSaida' => 'date',
        'prazoEntregaPrevisto' => 'date',
        'valorDeclarado' => 'float',
        'avisoRecebimento' => 'string',
        'maoPropria' => 'string',
        'peso' => 'string',
        'altura' => 'string',
        'largura' => 'string',
        'comprimento' => 'string',
        'diametro' => 'string',
        'urlRastreamento' => 'string'
    ];
}
