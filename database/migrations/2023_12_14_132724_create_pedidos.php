<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePedidos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();

            //Dados gerais pedido
            $table->float('descontoPed', 10, 2);
            $table->text('observacoesPed');
            $table->text('observacaointernaPed');
            $table->date('dataPed');
            $table->string('numeroPedidoPed', 50)->nullable();
            $table->string('numeroOrdemCompraPed', 50)->nullable();
            $table->string('vendedorPed', 100)->nullable();
            $table->float('valorfretePed', 10, 2)->nullable();
            $table->float('outrasdespesasPed', 10, 2)->nullable();
            $table->float('totalprodutosPed', 10, 2)->nullable();
            $table->float('totalvendaPed', 10, 2)->nullable();
            $table->string('situacaoPed', 100)->nullable();
            $table->date('dataSaidaPed')->nullable();
            $table->string('lojaPed', 100)->nullable();
            $table->string('numeroPedLoja', 100)->nullable();
            $table->string('tipoIntegracaoPed', 100)->nullable();

            //Dados cliente
            $table->string('idClie', 100)->nullable();
            $table->string('nomeClie', 255)->nullable();
            $table->string('docClie', 15)->nullable();
            $table->string('ieClie', 10)->nullable();
            $table->string('indIEClie', 10)->nullable();
            $table->string('rgClie', 10)->nullable();
            $table->string('enderecoClie', 255)->nullable();
            $table->string('numeroCasaClie', 10)->nullable();
            $table->text('complementoClie')->nullable();
            $table->string('cidadeClie', 100)->nullable();
            $table->string('bairroClie', 100)->nullable();
            $table->string('cepClie', 10)->nullable();
            $table->string('ufClie', 2)->nullable();
            $table->string('emailClie', 255)->nullable();
            $table->string('celularClie', 20)->nullable();
            $table->string('foneClie', 20)->nullable();

            //Dados pagamento
            $table->string('categoria', 255)->nullable();

            //Dados nf-e
            $table->string('serieNf', 5)->nullable();
            $table->string('numeroNf', 10)->nullable();
            $table->dateTime('dataEmissaoNf')->nullable();
            $table->string('situacaoNf', 2)->nullable();
            $table->float('valorNf', 15, 2)->nullable();
            $table->string('chaveAcesso', 45)->nullable();

            //Dados Transporte
            $table->string('transportadora', 255)->nullable();
            $table->string('cnpj', 15)->nullable();
            $table->string('tipo_frete', 3)->nullable();
            $table->integer('qtde_volumes')->nullable();
            $table->string('peso_bruto', 45)->nullable();
            //Endereço de entrega transporte
            $table->string('enderecoEntregaNome', 255)->nullable();
            $table->string('enderecoEntrega', 255)->nullable();
            $table->string('enderecoEntregaNumero', 10)->nullable();
            $table->text('enderecoEntregaComplemento')->nullable();
            $table->string('enderecoEntregaCidade', 100)->nullable();
            $table->string('enderecoEntregaBairro', 100)->nullable();
            $table->string('enderecoEntregaCep', 10)->nullable();
            $table->string('enderecoEntregaUf', 2)->nullable();

            $table->string('created_by', 200)->nullable();
            $table->string('updated_by', 200)->nullable();
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
        Schema::dropIfExists('pedidos');
    }
}
