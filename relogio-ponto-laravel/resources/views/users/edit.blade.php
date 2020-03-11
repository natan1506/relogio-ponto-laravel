@extends('layouts.app')

@section('content')
<style>
  .uper {
    margin-top: 40px;
  }
</style>
<div class="card uper">
    <div class="card-body">
        <h5>editar funcionario</h5>
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div><br/>
        @endif
        <form method="post" action="{{ route('users.update', $user->id) }}"  enctype="multipart/form-data">
            @method('PATCH')
            @csrf

            <div class="avatar-upload">
                <div class="avatar-edit">
                    <input type='file' name="foto" id="imageUpload" accept=".png, .jpg, .jpeg"/>
                    <label for="imageUpload" class="d-flex justify-content-center align-items-center"><i class="fas fa-pencil"></i></label>
                    @error('foto')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="avatar-preview">
                    <div id="imagePreview" style='background-image: url("../../../storage/img/{{$user->foto}}");'>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label for="nome" class="col-md-4 col-form-label text-md-right">nome</label>

                <div class="col-md-6">
                    <input id="nome" type="text" class="form-control" name="nome" value="{{ $user->nome }}" autocomplete="nome" autofocus>
                </div>
            </div>

            <div class="form-group row">
                <label for="username" class="col-md-4 col-form-label text-md-right">matricula</label>

                <div class="col-md-6">
                    <input id="username" type="text" class="form-control" name="username" value="{{ $user->username }}"
                    autocomplete="username" autofocus>
                </div>
            </div>

            <div class="form-group row">
                <label for="email" class="col-md-4 col-form-label text-md-right">email</label>

                <div class="col-md-6">
                    <input id="email" type="text" class="form-control" name="email" value="{{ $user->email }}"
                    autocomplete="email" autofocus>
                </div>
            </div>

            <div class="form-group row">
                <label for="filial" class="col-md-4 col-form-label text-md-right">filial</label>
                <div class="col-md-6">
                    <select class="custom-select" name="filial">
                        <option @if ($user->filial == 'AGG') {{ 'selected' }} @endif value="AGG">AGG</option>
                        <option @if ($user->filial == 'AMA') {{ 'selected' }} @endif value="AMA">AMA</option>
                        <option @if ($user->filial == 'DUDE') {{ 'selected' }} @endif value="DUDE">DUDE</option>
                        <option @if ($user->filial == 'LC') {{ 'selected' }} @endif value="LC">LC</option>
                        <option @if ($user->filial == 'ML') {{ 'selected' }} @endif value="ML">ML</option>
                        <option @if ($user->filial == 'YOUDU') {{ 'selected' }} @endif value="YOUDU">YOUDU</option>
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <label for="acesso" class="col-md-4 col-form-label text-md-right">acesso</label>
                <div class="col-md-6">
                    <select class="custom-select" name="acesso">
                        <option @if ($user->acesso == 'IND') {{ 'selected' }} @endif vvlue="IND">individual</option>
                        <option @if ($user->acesso == 'FULL') {{ 'selected' }} @endif value="FULL">total</option>
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <label for="password" class="col-md-4 col-form-label text-md-right">senha</label>

                <div class="col-md-6">
                    <input id="password" type="password" class="form-control" name="password" autocomplete="new-password">
                </div>
            </div>

            <div class="form-group row">
                <label for="password-confirm" class="col-md-4 col-form-label text-md-right">confirme a senha</label>

                <div class="col-md-6">
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" autocomplete="new-password">
                </div>
            </div>
            <div class="d-flex justify-content-center">
                <button type="submit" class="btn-neon-primary btn btn-theme">atualizar</button>
            </div>
        </form>
    </div>
</div>
@endsection


