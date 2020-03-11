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

@if(session()->has('atualizado'))
    <div class="alert alert-success card">
        <div class="col d-flex justify-content-center">
            <h3 class="mt-2">funcionário atualizado com sucesso!</h3>
        </div>
    </div>
@endif

@if(session()->has('apagado'))
    <div class="alert alert-success card">
        <div class="col d-flex justify-content-center">
            <h3 class="mt-2">funcionário apagado com sucesso!</h3>
        </div>
    </div>
@endif

    @isset($users)
        <div class="container">
            <diV class="row">
                <div class="col">
                    <h2>lista funcionários</h2>
                </div>
                <div class="col text-right">
                    <a href="{{ route('users.create') }}"><button class="btn btn-theme" type="submit">novo funcionário</button></a>
                </div>
            </div>
            <div class="list-group mb-2">
                @foreach($users as $user)
                <div class="card rounded list-group-item shadow p-12">
                    <div class="card-list-head d-flex justify-content-between align-items-center">
                        <span>{{ $user->nome }}</span>
                        <div class="form-inline d-flex align-items-center">
                            <a href="{{ route('users.edit',$user->id)}}"><i class="pointer fal fa-pen"></i></a>
                            <form action="{{ route('users.destroy', $user->id)}}" method="post" class=" ml-1 m-0">
                                @csrf
                                @method('DELETE')
                                <button class="btn border-0" type="submit" onClick="return confirm('Esta ação irá excluir o funcionario!')"><i class="pointer fal fa-times"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    @endisset

@endsection
