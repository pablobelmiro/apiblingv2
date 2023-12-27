<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePedidoItem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pedidoitem', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_pedido');
            //$table->foreign('id_pedido')->references('id')->on('pedidos');

            $table->string('codigo', 100);
            $table->text('descricao');
            $table->double('quantidade', 20, 10);
            $table->float('precocusto', 10, 2);
            $table->float('descontoItem', 10, 2);
            $table->string('un', 4);
            $table->double('pesoBruto', 15, 5);
            $table->float('largura', 5);
            $table->float('altura', 5);
            $table->float('profundidade', 5);
            $table->text('descricaoDetalhada');
            $table->string('unidadeMedida', 5);
            $table->string('gtin', 100);

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
        Schema::dropIfExists('pedidoitem');
    }
}
