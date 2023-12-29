<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Parcela extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $table = 'pedidoparcelas';
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id_pedido',
        'idLancamento',
        'valor',
        'dataVencimento',
        'obs',
        'destino'
    ];

    protected $casts = [
        'id_pedido' => 'string',
        'idLancamento' => 'string',
        'valor' => 'float',
        'dataVencimento' => 'datetime',
        'obs' => 'string',
        'destino' => 'integer'
    ];
}
