<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ponto extends Model
{
    protected $fillable = [
        'nome',
        'matricula',
        'data',
        'entrada1',
        'saida1',
        'entrada2',
        'saida2',
        'entrada3',
        'saida3',
        'observacao',
        'saida_justificada'
      ];
}
