<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeriadosTable extends Migration
{
    public function up()
    {
        Schema::create('feriados', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nome');
            $table->date('feriado');
            $table->boolean('uteis');
            $table->boolean('obrigatorio');
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('feriados');
    }
}
