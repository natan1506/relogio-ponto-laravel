@extends('layouts.app')

@section('content')
<style>
  .uper {
    margin-top: 40px;
  }
</style>

@if(session()->has('success'))
    <div class="d-flex justify-content-center">
        <div class="alert alert-success">
            <div class=" justify-content-center d-flex mt-2 mb-2">
                <h5 class="m-0">recesso adicionado com sucesso!</h5>
            </div>
        </div>
    </div>
@endif

<div class="card uper">
  <div class="card-body">
    <h5>adicionar novo recesso</h5>
    @if ($errors->any())
      <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
        </ul>
      </div><br />
    @endif
      <form method="post" action="{{ route('recessos.store') }}">
          <div class="form-group">
              @csrf
              <label for="name">nome</label>
              <input type="text" class="form-control" name="nome"/>

              <label for="name">data</label>
              <input type="date" class="form-control" name="recesso"/>
          </div>

          <button type="submit" class="btn-theme btn">adicionar</button>
      </form>
  </div>
</div>
@endsection
