<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Feriados extends Model
{
    protected $fillable = [
        'nome',
        'feriado',
        'uteis',
        'obrigatorio'
      ];
}
