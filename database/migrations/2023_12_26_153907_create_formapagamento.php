<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormapagamento extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('formapagamento', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_pedido');
            //$table->foreign('id_pedido')->references('id')->on('pedidos');

            $table->unsignedBigInteger('id_formapagamento');
            //$table->foreign('id_formapagamento')->references('id')->on('pedidoparcelas');

            $table->string('codigo', 50);
            $table->string('descricao', 3000);
            $table->string('codigoFiscal', 50);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('formapagamento');
    }
}
