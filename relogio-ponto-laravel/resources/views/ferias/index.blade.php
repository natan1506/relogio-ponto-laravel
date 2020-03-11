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
        @isset($users)
        <div class="container">
            @if(session()->has('success'))
                <div class="alert alert-success rounded card">
                    <div class="col d-flex justify-content-center">
                        {{ session()->get('success') }}
                    </div>
                </div>
            @endif
            <div class="row">
                <div class="col">
                    <h2>férias</h2>
                </div>
            </div>

            <div class="list-group mb-2">

                @foreach($users as $user)

                    <div class="mb-1 shadow rounded list-group-item d-flex justify-content-between">
                        <span>{{ $user->nome }}</span>

                        <div class="d-flex align-items-center">
                            <a href="#" data-toggle="modal" data-target="#ModalCreateFerias" onclick="adicionaFerias({{ $user->username }});" type="button" class="mr-1"
                                value ="{{ $user->nome }}">
                                <i data-toggle="tooltip" data-placement="top" title="novo período de férias" class="fal fa-plus fa-lg"></i>
                            </a>
                            <form action="{{ route('ferias.show' , $user->username ) }}" method="post" class="m-0 p-0">
                                @csrf
                                <a href="{{ route('ferias.show' , $user->username ) }}" data-toggle="tooltip" data-placement="top" title="relatório de férias" class="mr-1"
                                    value ="{{ $user->nome }}">
                                    <i class="fal fa-calendar-alt fa-lg"></i>
                                </a>
                            </form>

                        </div>
                    </div>
                @endforeach

                <!-- Modal -->
                <div class="modal fade" id="ModalCreateFerias" tabindex="-1" role="dialog" aria-labelledby="TituloModalCentralizado" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title text-dark" id="TituloModalCentralizado">novo período de férias</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body" >
                                <form  method='post' action="{{ route('ferias.store') }}">
                                    @csrf
                                    <div id="modal-form-ferias">

                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    @endisset
    @endcan
@endsection
