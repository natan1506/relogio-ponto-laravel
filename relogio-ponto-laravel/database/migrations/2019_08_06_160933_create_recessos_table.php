<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecessosTable extends Migration
{
    public function up()
    {
        Schema::create('recessos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nome');
            $table->date('recesso');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('recessos');
    }
}
