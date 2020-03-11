@extends('layouts.app')
@section('content')

@php
    date_default_timezone_set('America/Sao_Paulo');
    $date = date('d/m/Y');
    $hour = date('H:i:s');
@endphp

<div class="container">
    @if(session()->has('danger'))
        <div class="d-flex justify-content-center">
            <div class="alert alert-danger card w-100">
                <div class="card-body p-1 d-flex justify-content-center align-items-center">
                    <h5 class="text-center m-0">{{ session()->get('danger') }}</h5>
                </div>
            </div>
        </div>
    @endif
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    @if($hour >= '05:01' && $hour <= '12:00')
                        <h4>Bom dia</h4>
                    @elseif($hour >= '12:01' && $hour <= '18:00')
                        <h4>Boa tarde</h4>
                    @elseif($hour >= '18:01' && $hour <= '05:00')
                        <h4>Boa noite</h4>
                    @endif

                    @can('full', \App\Ponto::class)
                        <form method="get" action="{{ route('pontos.periodo') }}">
                            <h5 class="pt-2 pb-2">relatório por período</h5>
                            <div class="row">
                                <div class="col">
                                    <label for="name">nome</label>

                                    <select class="custom-select" name="nome">
                                        @foreach ($users as $user)
                                            <option value="{{ $user->nome }}">{{ $user->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col mb-3">
                                    <label for="name">de...</label>
                                    <input type="date" class="form-control" name="inicial"/>
                                </div>
                                <div class="col mb-3">
                                    <label for="name">até...</label>
                                    <input type="date" class="form-control" name="final"/>
                                </div>
                            </div>
                            <button class="btn btn-theme" type="submit">imprimir</button>
                        </form>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
