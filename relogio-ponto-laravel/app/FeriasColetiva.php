<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FeriasColetiva extends Model
{
    protected $fillable = [
        'data_inicial',
        'data_final',
        'observacao',
    ];
}
