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
    <div class="container no-print mb-2 d-flex justify-content-start">

        <div class="no-print">
            <input type="button" value="imprimir" class="btn btn-theme no-print" onclick="window.print();"/>
            <a href="/pontos"><button type="button" class="btn btn-theme">Voltar</button></a>
        </div>
    </div>
        @if($horas == "negativa")
            <div class="rounded row m-2 ">
                <div class="col-4 w-50 p-2">
                    <img class="avatar avatar-lg border-danger" style="width: 7rem; height: 6.8rem;"
                    src="../../../storage/img/{{$tableUsers->foto}}"/>
                </div>
                <div class="col flex-row w-50 p-2">
                    <h5> {{ $tableUsers->nome }}</h5>

                    <h5>Saldo total: {{ $horasFinais }}</h5>
                    <h5>Filial: {{ $tableUsers->filial }}</h5>
                </div>
            </div>

        @elseif($horas == "positiva")
            <div class="rounded row m-2 ">
                <div class="col-4 w-50 p-2">
                    <img class="avatar avatar-lg border-success" style="width: 7rem; height: 6.8rem;"
                    src="../../../storage/img/{{$tableUsers->foto}}"/>
                </div>
                <div class="col w-50 p-2">
                    <div><h4> {{ $tableUsers->nome }}</h4>
                        <h4>Saldo total: {{ $horasFinais }}</h4></div>
                </div>
            </div>
        @endif

        <div class="container">
            <form action="{{ route('pontos.search' ) }}" method="get" id="form-filtro" class="mb-1 shadow">

                <input type="hidden" name="matricula" value="{{$tableUsers->username}}">

                <ul class="nav nav-tabs d-flex rounded form-inline justify-content-between mb-2">
                    <li class="nav-item">
                       <a class="nav-link pointer @if($mesSelect == '01') {{ 'active' }} @endif" onclick="busca(this.id)" id="01">Jan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link pointer @if($mesSelect == '02') {{ 'active' }} @endif" onclick="busca(this.id)" id="02">Fev</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link pointer @if($mesSelect == '03') {{ 'active' }} @endif" onclick="busca(this.id)" id="03">Mar</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link pointer @if($mesSelect == '04') {{ 'active' }} @endif" onclick="busca(this.id)" id="04">Abr</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link pointer @if($mesSelect == '05') {{ 'active' }} @endif" onclick="busca(this.id)" id="05">Mai</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link pointer @if($mesSelect == '06') {{ 'active' }} @endif" onclick="busca(this.id)" id="06">Jun</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link pointer @if($mesSelect == '07') {{ 'active' }} @endif" onclick="busca(this.id)" id="07">Jul</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link pointer @if($mesSelect == '08') {{ 'active' }} @endif" onclick="busca(this.id)" id="08">Ago</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link pointer @if($mesSelect == '09') {{ 'active' }} @endif" onclick="busca(this.id)" id="09">Set</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link pointer @if($mesSelect == '10') {{ 'active' }} @endif" onclick="busca(this.id)" id="10">Out</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link pointer @if($mesSelect == '11') {{ 'active' }} @endif" onclick="busca(this.id)" id="11">Nov</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link pointer @if($mesSelect == '12') {{ 'active' }} @endif" onclick="busca(this.id)" id="12">Dez</a>
                    </li>
                    <li class="nav-item no-print">
                        <select name="ano" class="custom-select no-print"
                                onChange="mudaAno(this); $('#form-filtro').submit();" id="ano">

                            @isset($anoTabela)
                                @foreach ( $anoTabela as $item )
                                    <option value="{{ $item }}" @if ($item == $ano ) ? { selected } @endif>{{ $item }}</option>
                                @endforeach
                            @endisset
                        </select>
                    </li>
                </ul>

            </form>

            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" >
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr id="cabecalho">
                                    <th scope="col">observação</th>
                                    <th scope="col">data</th>
                                    <th scope="col">entrada 1</th>
                                    <th scope="col">saida 1</th>
                                    <th scope="col">entrada 2</th>
                                    <th scope="col">saida 2</th>
                                    <th scope="col">entrada 3</th>
                                    <th scope="col">saida 3</th>
                                    <th scope="col">horas trabalhadas </th>
                                    @can('full', \App\Ponto::class)
                                        <th scope="col" class="no-print">ação</th>
                                    @endcan
                                </tr>
                            </thead>
                            <tbody>

                                @php
                                    $saldoHoras = 0;
                                    $saldoMinutos = 0;
                                    $i = 0;
                                @endphp

                                @foreach($tablePontos as $ponto)

                                    @php
                                        $data =  0;
                                        $data = date('m', strtotime($ponto->data));


                                        // Faz o cálculo das horas
                                        $total = (strtotime($ponto->saida1) - strtotime($ponto->entrada1))
                                        + (strtotime($ponto->saida2) - strtotime($ponto->entrada2))
                                        + (strtotime($ponto->saida3) - strtotime($ponto->entrada3));

                                        // Encontra as horas trabalhadas
                                        $hours = floor($total / 60 / 60);

                                        // Encontra os minutos trabalhados
                                        $minutes = round(($total - ($hours * 60 * 60)) / 60);

                                        // Formata a hora e minuto para ficar no formato de 2 números, exemplo 00
                                        $hours = str_pad($hours, 2, "0", STR_PAD_LEFT);

                                        $minutes = str_pad($minutes, 2, "0", STR_PAD_LEFT);

                                        // Exibe no formato "hora:minuto"
                                        if($hours < 0 ){

                                            $saldoTotal = '00:00';

                                        }else{

                                            // echo $hours;
                                            $saldoTotal = $hours.':'.$minutes;

                                            $saldoHoras = $hours + $saldoHoras;
                                            $saldoMinutos = $minutes + $saldoMinutos;

                                            while($saldoMinutos >= '60'){
                                                $saldoMinutos = $saldoMinutos - 60;
                                                $saldoHoras++;
                                            }

                                            $saldoHoras = str_pad($saldoHoras, 2, "0", STR_PAD_LEFT);
                                            $saldoMinutos = str_pad($saldoMinutos, 2, "0", STR_PAD_LEFT);


                                            $saldoMinutos = $saldoMinutos;
                                        }

                                    @endphp
                                    <tr>
                                        @if($ponto->observacao != null)

                                                <td class="text-center">
                                                    <button type="button" class="border-0 btn btn-theme" data-toggle="modal" data-target="#ExemploModalCentralizado{{ $i }}">
                                                        <i class="fas fa-exclamation"></i>
                                                    </button>
                                                </td>
                                            <!-- Modal-->
                                            <div class="modal fade" id="ExemploModalCentralizado{{ $i }}" tabindex="-1" role="dialog" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header d-flex justify-content-end">
                                                            <button type="button" class="close rounded-pill" data-dismiss="modal" aria-label="Fechar">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                        {{ $ponto->observacao }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                        <td></td>
                                        @endif
                                        <td>{{ date('d/m/Y', strtotime($ponto->data)) }}</td>
                                        <td>{{ date('H:i', strtotime($ponto->entrada1)) }}</td>
                                        <td>{{ date('H:i', strtotime($ponto->saida1)) }}</td>
                                        <td>{{ date('H:i', strtotime($ponto->entrada2)) }}</td>
                                        <td>{{ date('H:i', strtotime($ponto->saida2))}}</td>
                                        <td>{{ date('H:i', strtotime($ponto->entrada3)) }}</td>
                                        <td>{{ date('H:i', strtotime($ponto->saida3)) }}</td>
                                        <td>{{$saldoTotal}}</td>

                                        @can('full', \App\Ponto::class)
                                            <td class="d-flex justify-content-center no-print">
                                                <div class="dropdown no-print">
                                                    <button class="btn-options no-print" type="button" id="..."
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <i class="material-icons no-print">more_vert</i>
                                                    </button>
                                                    <div class="dropdown-menu text-center">
                                                        <a class="dropdown-item" href="{{ route('pontos.edit',$ponto->id)}}">editar</a>
                                                        <form action="{{ route('pontos.destroy', $ponto->id)}}" class="m-0" method="post">
                                                            @csrf
                                                            @method('DELETE')
                                                            <a onClick="return confirm('Esta ação irá excluir o registro!')"><button class="dropdown-item" type="submit">excluir</button></a>
                                                        </form>
                                                    </div>
                                                </div>

                                            </td>
                                        @endcan
                                    </tr>
                                    @php
                                        $i++;
                                    @endphp
                                @endforeach
                                <span><b>saldo do mês: </b> {{$saldoHoras.':'.$saldoMinutos}}</span>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        <div>
    @isset($tableUsers)
    <!-- Modal -->
        <div class="modal fade" id="ExemploModalCentralizado" tabindex="-1" role="dialog" aria-labelledby="TituloModalCentralizado" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        {{-- <h5 class="modal-title text-dark" id="TituloModalCentralizado">criar registro</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                            <span aria-hidden="true">&times;</span>
                        </button> --}}
                    </div>
                    <div class="modal-body">
                        <form method="post" action="{{ route('pontos.create.manual') }}">
                            @csrf
                            <input type="hidden" class="form-control" name="matricula" value="{{ $tableUsers->username }}">
                            <div class="form-group row">
                                <div class="col">
                                    <label for="name">nome:</label>
                                    <input type="text" readonly class="form-control" name="nome" value="{{ $tableUsers->nome }}">
                                </div>
                                <div class="col">
                                    <label for="data">data :</label>
                                    <input type="date" class="form-control" name="data">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col">
                                    <label for="entrada1">entrada 1 :</label>
                                    <input type="time" class="form-control" name="entrada1">
                                </div>
                                <div class="col">
                                    <label for="saida1">saida 1:</label>
                                    <input type="time" class="form-control" name="saida1">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col">
                                    <label for="entrada2">entrada 2 :</label>
                                    <input type="time" class="form-control" name="entrada2">
                                </div>
                                <div class="col">
                                    <label for="saida2">saida 2:</label>
                                    <input type="time" class="form-control" name="saida2">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col">
                                    <label for="hora-extraentrada">entrada 3 :</label>
                                    <input type="time" class="form-control" name="entrada3">
                                </div>
                                <div class="col">
                                    <label for="hora-extra-saida">saida 3 :</label>
                                    <input type="time" class="form-control" name="saida3">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="observacao">observação:</label>
                                <textarea type="text" class="form-control" name="observacao"></textarea>
                            </div>
                            <button type="submit" class="btn btn-theme">Criar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    @endisset

    <button class="border-0 btn btn-theme btn-circle btn-md btn-floating" data-toggle="modal" data-target="#ExemploModalCentralizado" type="button">
        <i class="fal fa-plus"></i>
    </button>

    <script type="text/javascript">

        function goBack() {
            window.history.back();
        };

        function mudaAno(el) {
            var ano = el.value;
            console.log(ano);
        }
    </script>

@endsection
