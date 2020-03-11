@extends('layouts.app')

@section('content')
<style>
  .uper {
    margin-top: 40px;
  }
</style>
<div class="card uper">
  <div class="card-body">
    <h5>editar registro ponto</h5>
    @if ($errors->any())
      <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
        </ul>
      </div><br />
    @endif
      <form method="post" action="{{ route('pontos.update', $ponto->id) }}">
        @method('PATCH')
        @csrf
        <input type="hidden" class="form-control" name="matricula" value="{{ $ponto->matricula }}">
        <div class="form-group">
            <label for="name">nome:</label>
            <input type="text" class="form-control" name="nome" value="{{ $ponto->nome }}">
        </div>
        <div class="form-group">
            <label for="data">data :</label>
            <input type="date" class="form-control" name="data" value="{{ $ponto->data }}">
        </div>
        <div class="form-group">
            <label for="entrada1">entrada 1 :</label>
            <input type="time" class="form-control" name="entrada1" value="{{ $ponto->entrada1 }}">
        </div>
        <div class="form-group">
            <label for="saida1">saida 1:</label>
            <input type="time" class="form-control" name="saida1" value="{{ $ponto->saida1 }}">
        </div>
        <div class="form-group">
            <label for="entrada2">entrada 2 :</label>
            <input type="time" class="form-control" name="entrada2" value="{{ $ponto->entrada2 }}">
        </div>
        <div class="form-group">
            <label for="saida2">saida 2:</label>
            <input type="time" class="form-control" name="saida2" value="{{ $ponto->saida2 }}">
        </div>
        <div class="form-group">
            <label for="hora-extraentrada">entrada 3 :</label>
            <input type="time" class="form-control" name="entrada3" value="{{ $ponto->entrada3 }}">
        </div>
        <div class="form-group">
            <label for="hora-extra-saida">saida 3 :</label>
            <input type="time" class="form-control" name="saida3" value="{{ $ponto->saida3 }}">
        </div>
        <div class="form-group">
            <label for="observacao">observação:</label>
            <textarea type="text" class="form-control" name="observacao">{{ $ponto->observacao }}</textarea>
        </div>
        <button type="submit" class="btn btn-theme">Atualizar</button>
      </form>
  </div>
</div>
@endsection
