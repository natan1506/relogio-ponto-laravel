@extends('layouts.app')
<style>
    @media print{
        .no-print{
            display: none;
        }
    }
</style>

@section('content')
    @can('full', \App\Ponto::class)

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
                    <h2>relatório de férias coletivas</h2>
                </div>
            </div>
            <h4>opções</h4>

            <div class="">
                <a href="#" data-toggle="modal" data-target="#ModalCreateFeriasColetiva" type="button" class="btn btn-theme mt-1 mb-1">
                    novas férias coletivas
                </a>
            </div>
            <div class="list-group mb-2">

                @foreach($tableFeriasColetiva as $coletiva)

                    <div class="mb-1 shadow rounded list-group-item d-flex justify-content-between">
                        <div class="row w-100">
                            <div class="col-3">
                                <span class="mr-1"> <b>data inicial: </b></span>
                                <span>{{date('d/m/Y', strtotime($coletiva->data_inicial)) }}</span>
                            </div>
                            <div class="col-3">
                                <span class="mr-1"> <b>data final: </b></span>
                                <span>{{ date('d/m/Y', strtotime($coletiva->data_final)) }}</span>
                            </div>
                            <div class="col">
                                <span class="mr-1"> <b>observaçãol: </b></span>
                                <span>{{ $coletiva->observacao }}</span>
                            </div>
                        </div>

                        <div class="d-flex align-items-center justify-content-center">

                            <a href="#" data-toggle="modal" data-target="#ModalEditFeriasColetiva{{$coletiva->id}}" class="mr-1">
                                <i class="fal fa-pen fa-lg"></i>
                            </a>

                            <form action="{{ route('ferias-coletiva.destroy', $coletiva->id) }}" method="post" class="m-0">
                                @csrf
                                @method('DELETE')
                                <a class="" onClick="return confirm('Esta ação irá excluir o registro!')"><button class="btn" type="submit"><i class="fal fa-times fa-lg"></i></button></a>

                            </form>
                        </div>
                    </div>

                    <!-- MODAL FÉRIAS COLETIVA -->
                    <div class="modal fade" id="ModalEditFeriasColetiva{{$coletiva->id}}" tabindex="-1" role="dialog" aria-labelledby="TituloModalCentralizado" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title text-dark" id="TituloModalCentralizado">editar período de férias coletiva</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body pt-0" >
                                    <form  method='post' action="{{ route('ferias-coletiva.update', $coletiva->id) }}">
                                        @csrf
                                        <div class='form-group'>
                                            <label for='inicio_coletiva'>data do ínicio das férias:</label>
                                            <input type='date' class='form-control' name='data_inicial_coletiva' id='inicio_coletiva' value="{{ $coletiva->data_inicial }}">
                                        </div>
                                        <div class='form-group'>
                                            <label for='fim_coletiva'>data do fim das férias:</label>
                                            <input type='date' class='form-control' name='data_final_coletiva' id='fim_coletiva'  value="{{ $coletiva->data_final }}">
                                        </div>
                                        <div class='form-group'>
                                            <label for='observacao_coletiva'>data do fim das férias:</label>
                                            <textarea class='form-control' name='observacao_coletiva' id='observacao_coletiva'>{{$coletiva->observacao}}</textarea>
                                        </div>
                                        <button type='submit' class='btn btn-primary'>confirmar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <!-- MODAL FÉRIAS COLETIVA -->
        <div class="modal fade" id="ModalCreateFeriasColetiva" tabindex="-1" role="dialog" aria-labelledby="TituloModalCentralizado" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-dark" id="TituloModalCentralizado">novo período de férias coletiva</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body pt-0" >
                        <form  method='post' action="{{ route('ferias-coletiva.store') }}">
                            @csrf
                            <div class='form-group'>
                                <label for='inicio'>data do ínicio das férias:</label>
                                <input type='date' class='form-control' name='data_inicial_coletiva' id='inicio_coletiva'>
                            </div>
                            <div class='form-group'>
                                <label for='fim'>data do fim das férias:</label>
                                <input type='date' class='form-control' name='data_final_coletiva' id='fim_coletiva'>
                            </div>
                            <div class='form-group'>
                                <label for='observacao'>data do fim das férias:</label>
                                <textarea class='form-control' name='observacao_coletiva' id='observacao_coletiva'></textarea>
                            </div>
                            <button type='submit' class='btn btn-primary'>confirmar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endcan



@endsection
