<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePedidoVolumes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pedidovolumes', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_pedido');

            $table->string('transportadora');
            $table->string('cnpj');
            $table->string('tipo_frete');
            $table->float('qtde_volumes', 10, 2);
            $table->float('peso_bruto', 10, 2);
            $table->string('nome', 255);
            $table->string('endereco', 255);
            $table->string('numero', 50);
            $table->string('complemento', 500);
            $table->string('cidade', 100);
            $table->string('bairro', 100);
            $table->string('cep', 12);
            $table->string('uf', 3);

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
        Schema::dropIfExists('pedidovolumes');
    }
}
