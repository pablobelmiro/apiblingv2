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
            $table->float('desconto', 10, 2);
            $table->text('observacoes');
            $table->text('observacaointerna');
            $table->date('data');
            $table->string('numero', 50)->nullable();
            $table->string('numeroOrdemCompra', 50)->nullable();
            $table->string('vendedor', 100)->nullable();
            $table->float('valorfrete', 10, 2)->nullable();
            $table->float('outrasdespesas', 10, 2)->nullable();
            $table->float('totalprodutos', 10, 2)->nullable();
            $table->float('totalvenda', 10, 2)->nullable();
            $table->string('situacao', 100)->nullable();
            $table->date('dataSaida')->nullable();
            $table->string('loja', 100)->nullable();
            $table->string('numeroPedidoLoja', 100)->nullable();
            $table->string('tipoIntegracao', 100)->nullable();

            //Dados cliente
            $table->string('idcliente', 100)->nullable();
            $table->string('nome', 255)->nullable();
            $table->string('documento', 15)->nullable();
            $table->string('ie', 10)->nullable();
            $table->string('indIEDest', 10)->nullable();
            $table->string('rg', 10)->nullable();
            $table->string('endereco', 255)->nullable();
            $table->string('numero', 10)->nullable();
            $table->text('complemento')->nullable();
            $table->string('cidade', 100)->nullable();
            $table->string('bairro', 100)->nullable();
            $table->string('cep', 10)->nullable();
            $table->string('uf', 2)->nullable();
            $table->string('email', 255)->nullable();
            $table->string('celular', 20)->nullable();
            $table->string('fone', 20)->nullable();

            //Dados pagamento
            $table->string('categoria', 255)->nullable();

            //Dados nf-e
            $table->string('serie', 5)->nullable();
            $table->string('numero', 10)->nullable();
            $table->dateTime('dataEmissao')->nullable();
            $table->string('situacao', 2)->nullable();
            $table->float('valorNota', 15, 2)->nullable();
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
