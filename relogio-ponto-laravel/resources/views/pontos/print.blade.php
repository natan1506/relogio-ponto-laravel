@extends('layouts.app')

@section('content')
<div class="">

    @can('full', \App\Ponto::class)

        <form method="post" action="index.blade.php" id="frm-filtro">

            <div class="input-group input-group-round mb-2">
                <div class="input-group-prepend">
                    <span class="input-group-text pr-1">
                        <i class="fal fa-search"></i>
                    </span>
                </div>
                <input type="text" id="pesquisar" autocomplete="off" placeholder="Pesquise por um nome" name="pesquisar" size="30" class="form-control"/>

            </div>
        </form>
    @endcan

  <table class="table">
    <thead>
        <tr>
            <th scope="col" style="cursor: pointer;">nome</th>
            <th scope="col" style="cursor: pointer;">data</th>
            <th scope="col" style="cursor: pointer;">entrada 1</th>
            <th scope="col" style="cursor: pointer;">saida 1</th>
            <th scope="col" style="cursor: pointer;">entrada 2</th>
            <th scope="col" style="cursor: pointer;">saida 2</th>
            <th scope="col" style="cursor: pointer;">hora extra entrada</th>
            <th scope="col" style="cursor: pointer;">hora extra saida</th>
            @can('full', \App\Ponto::class)
                <th scope="col">ação</th>
            @endcan
        </tr>
    </thead>
    <tbody>
        @foreach($pontos as $ponto)
        <tr>
            <td>{{$ponto->nome}}</td>
            <td>{{$ponto->data}}</td>
            <td>{{$ponto->entrada1}}</td>
            <td>{{$ponto->saida1}}</td>
            <td>{{$ponto->entrada2}}</td>
            <td>{{$ponto->saida2}}</td>
            <td>{{$ponto->entrada3}}</td>
            <td>{{$ponto->saida3}}</td>

            @can('full', \App\Ponto::class)
                <td class="d-flex justify-content-center">
                    <div class="dropdown">
                        <button class="btn-options" type="button" id="..." data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="material-icons">more_vert</i>
                        </button>
                        <div class="dropdown-menu text-center">
                            <a class="dropdown-item" href="{{ route('pontos.edit',$ponto->id)}}">editar</a>
                            <form action="{{ route('pontos.destroy', $ponto->id)}}" class="w-auto" method="post">
                                @csrf
                                @method('DELETE')
                                <button class="dropdown-item" type="submit">excluir</button>
                            </form>
                        </div>
                    </div>

                </td>
            @endcan
        </tr>
        @endforeach
    </tbody>
  </table>
<div>
<script type="text/javascript">




</script>
@endsection
