<?php

namespace App\Http\Controllers;

use App\FeriasColetiva;
use App\Ponto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FeriasColetivaController extends Controller
{
    public function checkAuth()
    {
        if(!auth()->check()) {
            return true;
        }
    }

    public function index()
    {
        if($this->checkAuth() == true){
            return redirect('login');
        }

        $user = Auth::user();

        if ($user->can('full', Ponto::class)) {

            $tableFeriasColetiva = DB::table('ferias_coletivas')
                ->get();

            return view('coletivas.index', compact('tableFeriasColetiva'));
        }
        else {
            return redirect('/home')->with('danger', 'você não tem permissão para acessar essa página!');
        }
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        if($this->checkAuth() == true){
            return redirect('login');
        }

        try {
            $request->validate([
                'data_inicial' => 'data_inicial_coletiva',
                'data_final'=> 'data_final_coletiva',
            ]);

            $coletivas = new FeriasColetiva([
                'data_inicial' => $request->get('data_inicial_coletiva'),
                'data_final'=> $request->get('data_final_coletiva'),
                'observacao'=> $request->get('observacao_coletiva')
            ]);

            $coletivas->save();

            return redirect('/ferias-coletiva')->with('success', 'férias coletiva criada com sucesso!');

        } catch (\Exception $exception) {
            return redirect('/ferias-coletiva')
                ->with('error', 'Não foi possível cadastrar as férias. Erro: '.$exception->getMessage());
        }

    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($id)
    {
        if($this->checkAuth() == true){
            return redirect('login');
        }

        try{
            $coletiva = FeriasColetiva::find($id);
            $coletiva->delete();

            return redirect('/ferias-coletiva')->with('success', 'férias coletiva excluida com sucesso');

        } catch (\Exception $exception){
            return redirect('/ferias-coletiva')
                ->with('error', 'Não foi possível cadastrar as férias. Erro: '.$exception->getMessage());
        }
    }
}
