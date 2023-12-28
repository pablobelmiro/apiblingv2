<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePedidoParcelas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pedidoparcelas', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_pedido');
            //$table->foreign('id_pedido')->references('id')->on('pedidos');

            $table->string('idLancamento', 100);
            $table->float('valor');
            $table->dateTime('dataVencimento');
            $table->text('obs');
            $table->unsignedTinyInteger('destino');

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
        Schema::dropIfExists('pedidoparcelas');
    }
}
