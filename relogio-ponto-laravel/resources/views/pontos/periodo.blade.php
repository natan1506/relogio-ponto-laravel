@extends('layouts.app')
<style>
    @media print{
    .no-print{
        display: none;
    }
}
</style>

@section('content')

    @isset($table)

        <div class="container">
            <div class="row mb-2">
                <div class="col no-print">

                    <input type="button" value="imprimir" class="btn btn-outline-primary mr-1 no-print" onclick="window.print();"/>
                    <a href="/home"><button type="button" class="btn btn-outline-primary no-print">Voltar</button></a>

                </div>
                <div class="col d-flex justify-content-end">

                    <h5>relatório de horas do dia {{ date('d/m/Y', strtotime($request->inicial)) }} à {{ date('d/m/Y', strtotime($request->final)) }}</h5>

                </div>
            </div>
            <table class="table">
                <thead>
                    <tr id="cabecalho">
                        <th scope="col">nome</th>
                        <th scope="col">data</th>
                        <th scope="col">entrada 1</th>
                        <th scope="col">saida 1</th>
                        <th scope="col">entrada 2</th>
                        <th scope="col">saida 2</th>
                        <th scope="col">entrada 3</th>
                        <th scope="col">saida 3</th>

                    </tr>
                </thead>
                <tbody>
                    @foreach($table as $ponto)
                        <tr>
                            <td>{{$ponto->nome}}</td>
                            <td>{{ date('d/m/Y', strtotime($ponto->data)) }}</td>
                            <td>{{$ponto->entrada1}}</td>
                            <td>{{$ponto->saida1}}</td>
                            <td>{{$ponto->entrada2}}</td>
                            <td>{{$ponto->saida2}}</td>
                            <td>{{$ponto->entrada3}}</td>
                            <td>{{$ponto->saida3}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        <div>

    @endisset

@endsection
