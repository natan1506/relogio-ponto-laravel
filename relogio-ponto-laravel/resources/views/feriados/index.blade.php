@extends('layouts.app')


@section('content')

<?php
    date_default_timezone_set('America/Sao_Paulo');
    $year = date('Y');
?>

<div class="container">

    @if(session()->has('success'))
        <div class="d-flex justify-content-center">
            <div class="alert alert-success rounded card">
                <div class=" justify-content-center d-flex mt-2 mb-2">
                    <h5 class="m-0">feriados de {{ $year }} atualizados!</h5>
                </div>
            </div>
        </div>
    @endif

    @if(session()->has('atualizada'))
        <div class="d-flex justify-content-center">
            <div class="alert alert-success rounded card">
                <div class=" justify-content-center d-flex mt-2 mb-2">
                    <h5 class="m-0">os feriados já estão atualizados!</h5>
                </div>
            </div>
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="card w-100">
            <div class="card-body input-group d-flex justify-content-around">
                <a class="btn btn-outline-primary m-2" href="{{ route('feriados.atualiza-feriados')}}">atualizar feriados</a>
                <a class="btn btn-outline-primary m-2" href="{{ route('recessos.create')}}",>adicionar recesso</a>
                <a class="btn btn-outline-primary m-2" href="{{ route('feriados.create')}}">adicionar feriado</a>
                <a class="btn btn-outline-primary m-2" href="{{ '/feriados/show' }}">vizualizar feriados</a>
            </div>
        </div>
    </div>
</div>
@endsection
