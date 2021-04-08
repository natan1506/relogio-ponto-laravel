@extends('layouts.app')

@section('content')



@if(session()->has('feriado'))
    <div class="d-flex justify-content-center">
        <div class="alert alert-success rounded w-100 card">
            <div class=" justify-content-center d-flex mt-2 mb-2">
                <h5 class="m-0">feriado excluido com sucesso!</h5>
            </div>
        </div>
    </div>
@endif

@if(session()->has('feriados'))
    <div class="d-flex justify-content-center">
        <div class="alert alert-success rounded w-100 card">
            <div class=" justify-content-center d-flex mt-2 mb-2">
                <h5 class="m-0">todos os feriados foram excluidos com sucesso!</h5>
            </div>
        </div>
    </div>
@endif

@if(session()->has('recesso'))
    <div class="d-flex justify-content-center">
        <div class="alert alert-success rounded w-100 card">
            <div class=" justify-content-center d-flex mt-2 mb-2">
                <h5 class="m-0">recesso excluido com sucesso!</h5>
            </div>
        </div>
    </div>
@endif

@if(session()->has('atualizado'))
    <div class="d-flex justify-content-center">
        <div class="alert alert-success rounded w-100 card">
            <div class=" justify-content-center d-flex mt-2 mb-2">
                <h5 class="m-0">feriado atualizado com sucesso!</h5>
            </div>
        </div>
    </div>
@endif

<div class="container">
    <?php
    date_default_timezone_set('America/Sao_Paulo');
    $year = date('Y');
?>


    @if(session()->has('success'))
        <div class="d-flex justify-content-center">
            <div class="alert alert-success rounded w-100 card">
                <div class=" justify-content-center d-flex mt-2 mb-2">
                    <h5 class="m-0">feriados de {{ $year }} atualizados!</h5>
                </div>
            </div>
        </div>
    @endif

    @if(session()->has('atualizada'))
        <div class="d-flex justify-content-center">
            <div class="alert alert-success rounded w-100 card">
                <div class=" justify-content-center d-flex mt-2 mb-2">
                    <h5 class="m-0">os feriados já estão atualizados!</h5>
                </div>
            </div>
        </div>
    @endif
    <div class="row">
        <div class="col">
            <table class="table">
                <div class="row">
                    <div class="col-3">
                        <h3>feriados</h3>
                    </div>
                    <div class="col justify-content-between d-flex align-items-center mb-2">
                        <a class="btn-neon-primary btn btn-theme " href="{{ route('feriados.create')}}">adicionar</a>
                        <a class="btn-neon-primary btn btn-theme " href="{{ route('feriados.atualiza-feriados')}}">atualizar</a>
                        <form action="{{ route('feriados.destroy-feriados')}}" class="d-none" method="post">
                            @csrf
                            @php
                                $i = 0;
                            @endphp
                            @for($i = 0; $i < count($feriados); $i++)
                                <input type="hidden" name="ids[]" value="{{$feriados[$i]->id}}"/>
                            @endfor

                            <a onClick="return confirm('Esta ação irá excluir todos os feriados!')">
                                <button type="submit" class="btn-neon-primary btn btn-theme">excluir</button>
                            </a>
                        </form>
                    </div>
                </div>
                <thead>
                    <tr id="cabecalho">
                        <th scope="col">nome</th>
                        <th scope="col">data</th>
                        <th scope="col">dia</th>
                        <th scope="col">obrigatório</th>
                        <th scope="col" class="">ação</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($feriados as $ponto)

                        @php
                            $name = 'feriado';
                        @endphp
                        <tr>
                            <td>{{$ponto->nome}}</td>
                            <td>{{ date('d/m/Y', strtotime($ponto->feriado)) }}</td>
                            <td>
                                @if($ponto->uteis == 0)
                                    <span>útil</span>
                                @elseif($ponto->uteis == 1)
                                    <span>fim de semana</span>
                                @endif
                            </td>
                            <td>
                                @if($ponto->obrigatorio == 0)
                                    <span>sim</span>
                                @elseif($ponto->obrigatorio == 1)
                                    <span>não</span>
                                @endif
                            </td>

                            <td class="d-flex justify-content-center">
                                <div class="dropdown ">
                                    <button class="btn-options " type="button" id="..." data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                        <i class="material-icons ">more_vert</i>
                                    </button>
                                    <div class="dropdown-menu text-center">

                                        <a class="dropdown-item" href="{{ route('feriados.edit',$ponto->id)}}">editar</a>

                                        <form action="{{ route('feriados.destroy', $ponto->id)}}"
                                            class="w-auto" method="post">
                                            @csrf
                                            @method('DELETE')
                                            <a onClick="return confirm('Esta ação irá excluir o feriado!')"><button class="dropdown-item" type="submit">excluir</button></a>
                                        </form>
                                    </div>
                                </div>

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="col">
            <table class="table">
                <div class="row">
                    <div class="col">
                        <h3>recessos</h3>
                    </div>
                    <div class="col mb-2 d-flex justify-content-end align-items-center">
                        <a class="btn-neon-primary btn btn-theme" href="{{ route('recessos.create')}}">adicionar recesso</a>
                    </div>
                </div>

                <thead>
                    <tr id="cabecalho">
                        <th scope="col">nome</th>
                        <th scope="col">data</th>
                        <th scope="col">ação</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recessos as $ponto)
                        @php
                            $name = 'recesso';
                        @endphp
                        <tr>
                            <td>{{ $ponto->nome }}</td>
                            <td>{{ date('d/m/Y', strtotime($ponto->recesso)) }}</td>
                            <td class="d-flex justify-content-center">
                                <div class="dropdown">
                                    <button class="btn-options" type="button" id="..." data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                        <i class="material-icons ">more_vert</i>
                                    </button>
                                    <div class="dropdown-menu text-center">
                                        <form action="{{ route('recessos.destroy', $ponto->id)}}"
                                            class="w-auto" method="post">
                                            @csrf
                                            @method('DELETE')
                                            <a onClick="return confirm('Esta ação irá excluir o recesso!')"><button class="dropdown-item" type="submit">excluir</button></a>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
