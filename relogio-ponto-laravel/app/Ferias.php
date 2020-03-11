<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ferias extends Model
{
    protected $fillable = [
        'matricula',
        'data_inicial',
        'data_final',
        'observacao',
    ];
}
