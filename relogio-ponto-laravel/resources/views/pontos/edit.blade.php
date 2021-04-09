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
        <div class="form-row">
            <div class="col">
                <div class="form-group">
                    <label for="data">data :</label>
                    <input type="date" class="form-control" name="data" value="{{ $ponto->data }}">
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <label for="saidaJustificada">saída justificada?</label>
                    <select class="form-control" id="saidaJustificada" name="saida_justificada">
                    <option @if($ponto->saida_justificada === '0' || null) selected @endif value="0">não</option>
                    <option @if($ponto->saida_justificada === '1') selected @endif value="1">atestado</option>
                    <option @if($ponto->saida_justificada === '2') selected @endif value="2">outros</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="form-row">
            <div class="col">
                <div class="form-group">
                    <label for="entrada1">entrada 1 :</label>
                    <input type="time" class="form-control" name="entrada1" value= @if($ponto->entrada1) "{{ date('H:i', strtotime($ponto->entrada1)) }}" @endif>
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <label for="saida1">saida 1:</label>
                    <input type="time" class="form-control" name="saida1" value= @if($ponto->saida1) "{{ date('H:i', strtotime($ponto->saida1)) }}" @endif>
                </div>
            </div>
        </div>
        <div class="form-row">
            <div class="col">
                <div class="form-group">
                    <label for="entrada2">entrada 2 :</label>
                    <input type="time" class="form-control" name="entrada2" value= @if($ponto->entrada2) "{{ date('H:i', strtotime($ponto->entrada2)) }}" @endif>
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <label for="saida2">saida 2:</label>
                    <input type="time" class="form-control" name="saida2" value= @if($ponto->saida2) "{{ date('H:i', strtotime($ponto->saida2)) }}" @endif>
                </div>
            </div>
        </div>
        <div class="form-row">
            <div class="col">
                <div class="form-group">
                    <label for="hora-extraentrada">entrada 3 :</label>
                    <input type="time" class="form-control" name="entrada3" value= @if($ponto->entrada3) "{{ date('H:i', strtotime($ponto->entrada3)) }}" @endif>
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <label for="hora-extra-saida">saida 3 :</label>
                    <input type="time" class="form-control" name="saida3" value= @if($ponto->saida3) "{{ date('H:i', strtotime($ponto->saida3)) }}" @endif>
                </div>
            </div>
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
