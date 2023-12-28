<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FormaPagamento extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $table = 'formapagamento';
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id_pedido',
        'id_formapagamento',
        'codigo',
        'descricao',
        'codigoFiscal'
    ];

    protected $casts = [
        'id_pedido' => 'integer',
        'id_formapagamento' => 'integer',
        'codigo' => 'string',
        'descricao' => 'string',
        'codigoFiscal' => 'string'
    ];
}
