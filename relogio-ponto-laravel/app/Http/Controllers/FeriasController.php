<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ferias;
use App\Ponto;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use phpDocumentor\Reflection\Element;
use GuzzleHttp;
use DateTime;
use DatePeriod;
use DateInterval;
use function GuzzleHttp\Psr7\str;

class FeriasController extends Controller
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
            $users = DB::table('users')
                ->orderBy('nome', 'asc')
                ->get();

            $tabelaFerias = DB::table('ferias')
                ->get();

            return view('ferias.index')->with(['users' => $users, 'tabelaFerias'=> $tabelaFerias]);

        }
        else {
            return redirect('/home')->with('danger', 'você não tem permissão para acessar essa página!');
        }
    }
    public function create()
    {
        if($this->checkAuth() == true){
            return redirect('login');
        }
        //
    }
    public function store(Request $request)
    {
        if($this->checkAuth() == true){
            return redirect('login');
        }

        try{
            $ferias = new Ferias([
                'matricula'=> $request->get('matricula'),
                'data_inicial'=> $request->data_inicial,
                'data_final'=> $request->data_final,
                'observacao'=> $request->get('observacao'),
            ]);

            $ferias->save();
             return redirect('/pontos')->with('success', 'férias adicionada com sucesso');

        } catch (\Exception $exception) {
            return redirect('/pontos')
                ->with('error', 'Não foi possível cadastrar as férias. Erro: '.$exception->getMessage());
        }

    }
    public function show($id)
    {
        if($this->checkAuth() == true){
            return redirect('login');
        }

        $tableUsers = DB::table('users')
            ->select('nome', 'foto')
            ->where('username', $id)
            ->first();

        $tableFerias = DB::table('ferias')
            ->where('matricula', $id)
            ->get();


        return view('ferias.show',
            compact(
                'tableUsers',
                'tableFerias'
            )
        );

    }
    public function edit($id)
    {
        if($this->checkAuth() == true){
            return redirect('login');
        }
        //
    }
    public function update(Request $request, $id)
    {
        if($this->checkAuth() == true){
            return redirect('login');
        }

        $farias = Ferias::find($id);
        $farias->data_inicial = $request->get('data_inicial');
        $farias->data_final = $request->get('data_final');
        $farias->observacao = $request->get('observacao');
        $farias->save();

        return redirect('/ferias/'.$request->matricula.'')->with('success', 'férias atualizadas com sucesso');
    }
    public function destroy($id)
    {
        if($this->checkAuth() == true){
            return redirect('login');
        }

        $ferias = Ferias::find($id);
        $matricula = $ferias->matricula;
        $ferias->delete();

        return redirect('/ferias/'.$matricula.'')->with('success', 'férias excluida com sucesso!');
    }

}
