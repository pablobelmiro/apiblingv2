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

            $table->string('idServico', 50)->nullable();
            $table->string('idOrigem', 50)->nullable();
            $table->string('servico', 100)->nullable();
            $table->string('codigoServico', 100)->nullable();
            $table->string('codigoRastreamento', 100)->nullable();
            $table->float('valorFretePrevisto', 10, 2)->nullable();
            $table->string('remessa', 100)->nullable();
            $table->date('dataSaida')->nullable();
            $table->date('prazoEntregaPrevisto')->nullable();
            $table->float('valorDeclarado', 15, 2)->nullable();
            $table->string('avisoRecebimento', 10)->nullable();
            $table->string('maoPropria', 10)->nullable();

            //Dados dimensões
            $table->string('peso')->nullable();
            $table->string('altura')->nullable();
            $table->string('largura')->nullable();
            $table->string('comprimento')->nullable();
            $table->string('diametro')->nullable();
            $table->string('urlRastreamento', 2000)->nullable();

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
