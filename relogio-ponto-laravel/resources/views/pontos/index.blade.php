@extends('layouts.app')
<style>
    @media print{
        .no-print{
            display: none;
        }
    }
</style>

@section('content')
    @isset($users)
        <div class="container">
            @if(session()->has('success'))
                <div class="alert alert-success rounded card">
                    <div class="col d-flex justify-content-center">
                        {{ session()->get('success') }}
                    </div>
                </div>
            @endif
            @if(session()->has('error'))
                <div class="alert alert-danger rounded card">
                    <div class="col d-flex justify-content-center">
                        {{ session()->get('error') }}
                    </div>
                </div>
            @endif
            <div class="row">
                <div class="col">
                    <h2>relatório funcionários</h2>
                </div>
            </div>
            @can('full', \App\Ponto::class)
                <h4>opções</h4>

                <div class="">
                    <a href="{{ route('pontos.relatorio') }}" class="btn btn-theme mt-1 mb-1">
                        relatório de horas totais
                    </a>
                    <a href="#" data-toggle="modal" data-target="#ModalCreateFerias" type="button" class="btn btn-theme mt-1 mb-1">
                        novas férias
                    </a>

                </div>
            @endcan

            <div class="list-group mb-2">

                @foreach($users as $user)

                    <div class="mb-1 shadow rounded list-group-item d-flex justify-content-between">
                        <span>{{ $user->nome }}</span>

                        <div class="d-flex align-items-center">


                            <form action="{{ route('ferias.show' , $user->username ) }}" method="post" class="m-0 p-0">
                                @csrf
                                <a href="{{ route('ferias.show' , $user->username ) }}" data-toggle="tooltip" data-placement="top" title="relatório de férias"
                                   class="mr-1" value ="{{ $user->nome }}">
                                    <i class="fal fa-calendar-alt fa-lg"></i>
                                </a>
                            </form>
                            <form action="{{ route('pontos.show' , $user->username) }}" method="post" class="m-0 p-0">
                                @csrf
                                <a href="{{ route('pontos.show' , $user->username) }}" data-toggle="tooltip" data-placement="top" title="relatório de ponto"
                                   class="mr-1" value ="{{ $user->nome }}">
                                    <i class="fal fa-alarm-clock fa-lg"></i>
                                </a>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
            <!-- MODAL FÉRIAS -->
            <div class="modal fade" id="ModalCreateFerias" tabindex="-1" role="dialog" aria-labelledby="TituloModalCentralizado" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title text-dark" id="TituloModalCentralizado">novo período de férias</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body pt-0" >
                            <form  method='post' action="{{ route('ferias.store') }}">
                                @csrf
                                <div class="form-group">
                                    <label for="funcionario">funcionário:</label>
                                    <select class="form-control" id="funcionario" name="matricula">
                                        @foreach($users as $user)
                                            <option value="{{ $user->username }}">{{ $user->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class='form-group'>
                                    <label for='inicio'>data do ínicio das férias:</label>
                                    <input type='date' class='form-control' name='data_inicial' id='inicio'>
                                </div>
                                <div class='form-group'>
                                    <label for='fim'>data do fim das férias:</label>
                                    <input type='date' class='form-control' name='data_final' id='fim'>
                                </div>
                                <div class='form-group'>
                                    <label for='observacao'>data do fim das férias:</label>
                                    <textarea class='form-control' name='observacao' id='observacao'></textarea>
                                </div>
                                <button type='submit' class='btn btn-primary'>confirmar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>



        </div>
    @endisset


@endsection
