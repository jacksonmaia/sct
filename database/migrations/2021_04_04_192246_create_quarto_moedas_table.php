<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuartoMoedasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quarto_moedas', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('quarto_id'); // Chave estrangeira Sem sinal
            $table->foreign('quarto_id')->references('id')->on('quartos'); // Referenciando tabela
            $table->unsignedInteger('moeda_id'); // Chave estrangeira Sem sinal
            $table->foreign('moeda_id')->references('id')->on('moedas'); // Referenciando tabela
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quarto_moedas');
    }
}
