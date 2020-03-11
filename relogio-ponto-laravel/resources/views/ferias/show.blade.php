@extends('layouts.app')
<style>
    @media print{
        .no-print{
            display: none;
        }
    }
    .table thead th{
        padding-right: 6px;
        padding-left: 6px;
    }
    .table tbody td{
        padding-right: 6px;
        padding-left: 6px;
    }
</style>

@section('content')
    <h3>relatório de férias</h3>
    <hr>

    @if(session()->has('success'))
        <div class="alert alert-success rounded card">
            <div class="col d-flex justify-content-center">
                {{ session()->get('success') }}
            </div>
        </div>
    @endif

    <div class="container no-print mb-2 d-flex justify-content-start">
        <div class="no-print">
            <input type="button" value="imprimir" class="btn btn-theme no-print" onclick="window.print();"/>
            <a href="/pontos"><button type="button" class="btn btn-theme">Voltar</button></a>
        </div>
    </div>
    <div class="rounded row m-2 ">
        <div class="col-3 w-50 p-2">
            <img class="avatar avatar-lg border-success" style="width: 7rem; height: 6.8rem;"
                 src="../../../storage/img/{{$tableUsers->foto}}"/>
        </div>
        <div class="col w-50 p-2 d-flex align-items-center">
            <h4> {{ $tableUsers->nome }}</h4>
        </div>
    </div>

    <div class="container">
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" >
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr id="cabecalho">
                                <th scope="col">data inicial</th>
                                <th scope="col">data final</th>
                                <th scope="col">tipo</th>
                                <th scope="col">observação</th>
                                <th scope="col" class="no-print text-center">ação</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tableFerias as $ferias)
                                <tr>
                                    <td>{{ date('d/m/Y', strtotime($ferias->data_inicial)) }}</td>
                                    <td>{{ date('d/m/Y', strtotime($ferias->data_final)) }}</td>
                                    <td>{{ $ferias->tipo }}</td>
                                    <td>{{ $ferias->observacao }}</td>
                                    <td class="d-flex justify-content-center no-print">
                                        <div class="dropdown no-print">
                                            <button class="btn-options no-print" type="button" data-toggle="dropdown">
                                                <i class="material-icons no-print">more_vert</i>
                                            </button>
                                            <div class="dropdown-menu text-center">
                                                <a class="dropdown-item" data-toggle="modal" data-target="#ModalEditFerias{{$ferias->id}}" >
                                                    editar
                                                </a>
                                                <form action="{{ route('ferias.destroy', $ferias->id)}}" class="m-0" method="post">
                                                    @csrf
                                                    @method('DELETE')
                                                    <a onClick="return confirm('Esta ação irá excluir o registro!')">
                                                        <button class="dropdown-item" type="submit">excluir</button>
                                                    </a>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                <!--MODAL EDIT -->
                                <div class="modal fade" id="ModalEditFerias{{$ferias->id}}" tabindex="-1" role="dialog">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title text-dark" id="TituloModalCentralizado">editar período de férias</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body" >
                                                <form  method='post' action="{{ route('ferias.update', $ferias->id) }}">
                                                    @method('PATCH')
                                                    @csrf
                                                    <input type="hidden" name="matricula" value="{{ $ferias->matricula }}">
                                                    <div class='form-group'>
                                                        <label for='inicio'>data do ínicio das férias:</label>
                                                        <input type='date' class='form-control' name='data_inicial' id='inicio' value="{{ date('Y-m-d', strtotime($ferias->data_inicial)) }}">
                                                    </div>
                                                    <div class='form-group'>
                                                        <label for='fim'>data do fim das férias:</label>
                                                        <input type='date' class='form-control' name='data_final' id='fim' value="{{ date('Y-m-d', strtotime( $ferias->data_final)) }}">
                                                    </div>
                                                    <div class='form-group'>
                                                        <label for='observacao'>data do fim das férias:</label>
                                                        <textarea class='form-control' name='observacao' id='observacao'>{{$ferias->observacao}}</textarea>
                                                    </div>
                                                    <button type='submit' class='btn btn-primary'>confirmar</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


@endsection
