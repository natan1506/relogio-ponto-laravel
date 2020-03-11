@extends('layout')

@section('content')
<style>
.btn-neon-primary:hover{
    /* color: #007bff; */
    box-shadow: 0 0 5px #007bff, 0 0 10px #007bff, 0 0 20px #007bff;
}
.btn-neon-warning:hover{
    background-color: #ffc107;
    box-shadow: 0 0 5px #ffc107 , 0 0 10px #ffc107 , 0 0 20px #ffc107;
}
</style>
<body>
    <div class="main-container fullscreen">
        <div class="container">
            <div class="row justify-content-center">
                <div class="card" style="width: 30rem;">
                    <div class="card-body">
                        <div class="row justify-content-center">
                            <div class="text-center">
                                <h1 class="h2">Welcome</h1>
                                <h1>&#x23F1;</h1>
                                <p class="lead">Entre com a sua conta para continuar</p>
                                <form method="POST" action="{{ route('login') }}">
                                    @csrf
                                    <div class="form-group">
                                        <input id="email" type="text" placeholder="matricula" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>

                                        @if ($errors->has('email'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('email') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <input id="password" type="password" placeholder="senha" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required/>

                                        @if ($errors->has('password'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('password') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <button class="btn btn-lg btn-block btn-primary btn-neon-primary" role="button" type="submit">
                                        Login
                                    </button>
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Esqueceu sua senha?') }}
                                    </a>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
      </div>
    </div>

    <!-- Required vendor scripts (Do not remove) -->
    <script type="text/javascript" src="assets/js/jquery.min.js"></script>
    <script type="text/javascript" src="assets/js/popper.min.js"></script>
    <script type="text/javascript" src="assets/js/bootstrap.js"></script>
</body>

@endsection
