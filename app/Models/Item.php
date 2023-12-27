<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class item extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $table = 'pedidoitem';
    protected $dates = ['deleted_at'];

    private $fillable = [
        'id_pedido',
        'codigo',
        'descricao',
        'quantidade',
        'precocusto',
        'descontoItem',
        'un',
        'pesoBruto',
        'largura',
        'altura',
        'profundidade',
        'descricaoDetalhada',
        'unidadeMedida',
        'gtin'
    ];

    protected $casts = [
        'id_pedido' => 'string',
        'codigo' => 'string',
        'descricao' => 'string',
        'quantidade' => 'double',
        'precocusto' => 'float',
        'descontoItem' => 'float',
        'un' => 'string',
        'pesoBruto' => 'double',
        'largura' => 'float',
        'altura' => 'float',
        'profundidade' => 'float',
        'descricaoDetalhada' => 'string',
        'unidadeMedida' => 'string',
        'gtin' => 'string'
    ];
}
