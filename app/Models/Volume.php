<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class volume extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $table = 'pedidovolumes';
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id_pedido',
        'transportadora',
        'cnpj',
        'tipo_frete',
        'qtde_volumes',
        'peso_bruto',
        'nome',
        'endereco',
        'numero',
        'complemento',
        'cidade',
        'bairro',
        'cep',
        'uf'
    ];

    protected $casts = [
        'id_pedido' => 'integer',
        'transportadora' => 'string',
        'cnpj' => 'string',
        'tipo_frete' => 'string',
        'qtde_volumes' => 'float',
        'peso_bruto' => 'float',
        'nome' => 'string',
        'endereco' => 'string',
        'numero' => 'string',
        'complemento' => 'string',
        'cidade' => 'string',
        'bairro' => 'string',
        'cep' => 'string',
        'uf' => 'string'
    ];
}
