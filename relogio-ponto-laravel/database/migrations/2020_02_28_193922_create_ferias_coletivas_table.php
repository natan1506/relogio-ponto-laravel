<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeriasColetivasTable extends Migration
{
    public function up()
    {
        Schema::create('ferias_coletivas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('data_inicial');
            $table->date('data_final');
            $table->string('observacao');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ferias_coletivas');
    }
}
