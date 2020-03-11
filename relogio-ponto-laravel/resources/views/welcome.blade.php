@extends('layout')

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        <br/>
    @endif

    @php
        date_default_timezone_set('America/Sao_Paulo');
        $date = date('d/m/Y');
        $hour = date('H:i:s');
    @endphp

    <style>
        .btn-neon-primary:hover{
            background-color: #007bff;
            box-shadow: 0 0 5px #007bff, 0 0 10px #007bff, 0 0 20px #007bff;
        }
        .form-control-tai {
            height: calc(1.5em + 1rem + 2px);
            padding: 0.5rem 1rem;
            font-size: 1.25rem;
            line-height: 1.5;
            border-radius: 0.3rem;
        }
        .btn-lg{
            padding: 0.5rem 1rem;
            font-size: 1.25rem;
            line-height: 1.5;
            border-radius: 0.3rem;
        }

    </style>

    <body>
        <div class="container fullscreen d-flex justify-content-center">
            <a href="/login">
                <button class="btn btn-outline-primary btn-neon-primary btn-floating btn-lg" style="border-radius: 50%;" stype="button">
                    <i class="far fa-lock-alt text-black"></i>
                </button>
            </a>
            <div class="">

                @if(session()->has('success'))
                    <div class="d-flex justify-content-center">
                        <div class="alert alert-success card" style="width: 30rem;">
                            <div class=" justify-content-center d-flex mt-2 mb-2">
                                <img alt="..." class="avatar avatar-lg" src="../../../storage/img/{{ session('dados')->foto }}"/>
                            </div>
                            <div class="card-body p-1">
                                <h5 class="text-center m-0">{{ session('dados')->nome }}</h5>
                                <h6 class="text-center m-0"><?php echo $date; ?></h6>
                                <h6 class="text-center m-0"><?php echo $hour; ?></h6>
                            </div>
                        </div>
                    </div>
                @endif

                @if(session()->has('sem-matricula'))
                    <div class="d-flex justify-content-center">
                        <div class="alert alert-danger card" style="width: 30rem;">
                            <div class="card-body p-1 d-flex align-items-center">
                                <h5 class="text-center m-0">Matricula não encontrada!</h5>
                            </div>
                        </div>
                    </div>
                @endif

                @if(session()->has('preenchido'))
                    <div class="d-flex justify-content-center">
                        <div class="alert alert-danger card" style="width: 30rem;">
                            <div class="card-body p-1">
                                <h5 class="text-center m-0">{{ session()->get('preenchido') }}</h5>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="card" style="width: 30rem; height: 30rem;">

                    <div class="card-body d-flex align-items-center">

                        <form style="width: 100%" method="post" action="{{ route('pontos.store') }}">
                            @csrf
                            <div class="row justify-content-center">
                                <img src="/assets/img/logo-ml.png" alt="Logo Marca laser">
                            </div>
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <span class="input-group-text p-1">
                                        <i class="fas fa-signature"></i>
                                    </span>
                                </div>
                                <input type="text" class="form-control form-control-tai" autocomplete="off" name="matricula" placeholder="data de nascimento" required>
                            </div>

                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <span class="input-group-text p-1">
                                        <i class="fas fa-calendar-alt"></i>
                                    </span>
                                </div>
                                <input type="text" class="form-control form-control-tai" name="data" disabled value="<?php echo $date?>">

                            </div>
                            <button class="btn btn-lg btn-block btn-primary btn-neon-primary" role="button" type="submit">
                                validar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </body>
@endsection
