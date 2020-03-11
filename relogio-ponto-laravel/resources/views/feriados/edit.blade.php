@extends('layouts.app')

@section('content')
<style>
  .uper {
    margin-top: 40px;
  }
</style>
<div class="card uper">
  <div class="card-header">
    editar feriado
  </div>
  <div class="card-body">
      <form method="post" action="{{ route('feriados.update', $feriado->id) }}">
        @method('PATCH')
        @csrf
          <div class="w-100">
            <input type="hidden" class="border-0" name="nome" value= "{{ $feriado->nome }}">
            <span><b>nome: </b>{{ $feriado->nome }}</span>
        </div>
          <div class="w-100 mt-1">
            <input type="hidden" class="border-0" name="feriado" value= "{{ $feriado->feriado }}">
            <span><b>data: </b>{{ date('d/m/Y', strtotime($feriado->feriado)) }}</span>
        </div>
        <div class="form-group mt-1">
          <label for="uteis"><b>dia da semana:</b></label>
          <select class="custom-select" name="uteis">
            @if($feriado->uteis == "0")
                <option value="0" selected>útil</option>
                <option value="1">fim de semana</option>
            @elseif ($feriado->uteis == "1")
                <option value="0">útil</option>
                <option value="1" selected>fim de semana</option>
            @endif
          </select>
        </div>
        <div class="form-group mt-1">
          <label for="obrigatorio"><b>feriado obrigatório?</b></label>
          <select class="custom-select" name="obrigatorio">
            @if($feriado->obrigatorio == "0")
                <option value="0" selected>sim</option>
                <option value="1">não</option>
            @elseif ($feriado->obrigatorio == "1")
                <option value="0">sim</option>
                <option value="1" selected>não</option>
            @endif
          </select>
        </div>
        <button type="submit" class="btn btn-theme">atualizar</button>
      </form>
  </div>
</div>
@endsection
