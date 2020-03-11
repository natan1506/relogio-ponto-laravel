@extends('layouts.app')
<style>
    @media print{
        .no-print{
            display: none !important;
        }
    }
</style>

@section('content')
    <div class="container">
        <div class="row">
            <div class="col">
                <h2>relatório total de horas</h2>
            </div>
            <div class="col d-flex justify-content-end allign-items-center p-1">
                <input type="button" value="imprimir" class="btn btn-theme no-print" onclick="window.print();"/>
            </div>
        </div>
            <table class="table">
                <thead>
                    <tr>
                    <th class="border-top-0" scope="col">nome</th>
                    <th class="border-top-0" scope="col">horas totais</th>
                    </tr>
                </thead>
                @foreach($totaisUser as $user)
                    <tbody>
                        <tr>
                            <td>{{ $user[0] }}</td>
                            <td>{{ $user[1] }}</td>
                        </tr>
                    </tbody>
                @endforeach
            </table>
        </div>
    </div>
@endsection
