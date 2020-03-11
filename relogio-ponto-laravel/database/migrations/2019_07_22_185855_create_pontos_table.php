<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePontosTable extends Migration
{
    public function up()
    {
        Schema::create('pontos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nome');
            $table->string('matricula');
            $table->date('data');
            $table->time('entrada1');
            $table->time('saida1');
            $table->time('entrada2');
            $table->time('saida2');
            $table->time('entrada3');
            $table->time('saida3');
            $table->string('observacao');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pontos');
    }
}
