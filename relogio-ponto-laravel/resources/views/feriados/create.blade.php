@extends('layouts.app')

@section('content')
<style>
  .uper {
    margin-top: 40px;
  }
</style>

@if(session()->has('success'))
    <div class="d-flex justify-content-center">
        <div class="alert alert-success card w-100">
            <div class=" justify-content-center d-flex mt-2 mb-2">
                <h5 class="m-0">feriado adicionado com sucesso!</h5>
            </div>
        </div>
    </div>
@endif

<div class="card uper">
    <div class="card-body">
        <h5>adicionar novo feriado</h5>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            <br/>
        @endif
        <form method="post" action="{{ route('feriados.store') }}">
            <div class="form-group">
                @csrf
                <label for="name">nome</label>
                <input type="text" placeholder="nome do feriado" class="form-control" name="nome"/>

                <label for="name">data</label>
                <input type="date" class="form-control" name="feriado"/>
            </div>
            <div class="form-group">
                <select class="custom-select form-control rounded" name="uteis">
                    <option value="0" selected>dia útil</option>
                    <option value="1">fim de semana</option>
                </select>
            </div>

            <div class="form-group">
                <label for="obrigado">feriado obrigatório?</label>
                <select class="custom-select form-control rounded" name="obrigatorio">
                    <option value="0" selected>sim</option>
                    <option value="1">não</option>
                </select>
            </div>
            <button type="submit" class="btn btn-theme">adicionar</button>
        </form>
    </div>
</div>
@endsection
