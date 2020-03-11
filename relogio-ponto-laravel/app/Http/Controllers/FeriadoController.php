<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Feriados;
use App\Ponto;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp;

class FeriadoController extends Controller
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
        $tabelaFeriados = DB::table('feriados')
            ->orderBy('feriado', 'asc')
            ->get();

        $tabelaRecesso = DB::table('recessos')
            ->orderBy('recesso', 'asc')
            ->get();

        return view('feriados.show')->with(['feriados'=> $tabelaFeriados , 'recessos'=> $tabelaRecesso ]);
    }

    public function create(Request $request)
    {
        if($this->checkAuth() == true){
            return redirect('login');
        }
        return view('feriados.create');
    }

    public function store(Request $request)
    {
        if($this->checkAuth() == true){
            return redirect('login');
        }
        $feriado = new Feriados([
            'nome' => $request->get('nome'),
            'feriado' => $request->get('feriado'),
            'uteis' => $request->get('uteis'),
            'obrigatorio' => $request->get('obrigatorio')
        ]);

        $feriado->save();

        if($request->uteis == 1 && $request->obrigatorio == 0){
            $users = DB::table('users')
                ->orderBy('nome', 'asc')
                ->get();

                foreach ($users as $user) {
                    $ponto = new Ponto([
                        'nome' => $user->nome,
                        'matricula'=> $user->username,
                        'data'=> $request->feriado,
                        'entrada1'=> '08:00:00',
                        'saida1'=> '12:00:00',
                    ]);

                    $ponto->save();
                }
        }
        return redirect('/feriados/create')->with('success', 'success');
    }

    public function show()
    {
        if($this->checkAuth() == true){
            return redirect('login');
        }
        $tabelaFeriados = DB::table('feriados')
            ->orderBy('feriado', 'asc')
            ->get();

        $tabelaRecesso = DB::table('recessos')
            ->orderBy('recesso', 'asc')
            ->get();

        return view('feriados.show')->with(['feriados'=> $tabelaFeriados , 'recessos'=> $tabelaRecesso ]);
    }

    public function edit($id)
    {
        if($this->checkAuth() == true){
            return redirect('login');
        }
        $feriado = Feriados::find($id);
        return view('feriados.edit', compact('feriado'));
    }

    public function update(Request $request, $id)
    {
        if($this->checkAuth() == true){
            return redirect('login');
        }
        $feriado = Feriados::find($id);
        $feriado->nome = $request->get('nome');
        $feriado->feriado = $request->get('feriado');
        $feriado->uteis = $request->get('uteis');
        $feriado->obrigatorio = $request->get('obrigatorio');
        $feriado->save();

        if($request->uteis == 1 && $request->obrigatorio == 0){
            $pontos = DB::table('pontos')
            ->where('data', '=', $request->feriado)
            ->first();

            if(!$pontos){
                $users = DB::table('users')
                ->orderBy('nome', 'asc')
                ->get();

                foreach ($users as $user) {
                    $ponto = new Ponto([
                        'nome' => $user->nome,
                        'matricula'=> $user->username,
                        'data'=> $request->feriado,
                        'entrada1'=> '08:00:00',
                        'saida1'=> '12:00:00',
                    ]);
                    $ponto->save();
                }
            }
        }else{
            $pontos = DB::table('pontos')
            ->where('data', '=', $request->feriado)
            ->get();

            if($pontos){
                foreach ($pontos as $ponto) {
                    $ponto = Ponto::find($ponto->id);
                    $ponto->delete();
                }
            }
        }

        return redirect('/feriados/show')->with('atualizado', 'atualizado');
    }

    public function destroy($id)
    {
        if($this->checkAuth() == true){
            return redirect('login');
        }
        $feriado = DB::table('feriados')
        ->where('id', '=', $id)
        ->get();

        $pontos = DB::table('pontos')
        ->where('data', '=', $feriado[0]->feriado)
        ->get();

        $count = count($pontos);

        if($count){
            if($pontos[0]->entrada1 == "08:00:00" && $pontos[0]->saida1 == "12:00:00" && $pontos[0]->entrada2 == null){
                foreach ($pontos as $ponto) {
                    $ponto = Ponto::find($ponto->id);
                    $ponto->delete();
                }
            }
        }
        $feriado = Feriados::find($id);
        $feriado->delete();

        return redirect('/feriados/show')->with('feriado', 'feriado');
    }
    public function destroyFeriados(Request $request)
    {
        if($this->checkAuth() == true){
            return redirect('login');
        }
        if($request->ids){
            $ids = [$request->ids];
            for($i = 0; $i < count($ids[0]); $i++){
                $ids[0][$i] = Feriados::find($ids[0][$i]);
                $ids[0][$i]->delete();
            }

            return redirect('/feriados/show')->with('feriados', 'feriados');
        }
        return redirect('/feriados/show');
    }

    public function atualizaFeriados(Request $request)
    {
        if($this->checkAuth() == true){
            return redirect('login');
        }
        $year = date('Y');
        $client = new GuzzleHttp\Client();
        $feriados = $client->request('GET',
            "https://api.calendario.com.br/?json=true&ano="
            .$year.
            "&&estado=pr&cidade=pinhais&token=ZnJvbnRlbmRAbWFyY2FsYXNlci5jb20uYnImaGFzaD0xODg1NzI5OTA");
        $feriados = $feriados->getBody();
        $feriados = json_decode($feriados, true);

        $tabelaFeriados = DB::table('feriados')
            ->get();

        if( $tabelaFeriados == '[]'){
            for($i = 0; $i < count($feriados); $i++){
                $datas = implode("-",array_reverse(explode("/", $feriados[$i]['date'])));

                $feriado = new Feriados([
                    'nome' => $feriados[$i]['name'],
                    'feriado' => $datas,
                    'uteis' => 0,
                    'obrigatorio' => 1,
                ]);
                $feriado->save();
            };
        }elseif ( $tabelaFeriados != '[]'){
            for($i = 0; $i < count($tabelaFeriados); $i++){

                $anoFeriado = date('Y', strtotime($tabelaFeriados[$i]->feriado));

                if($anoFeriado != $year){
                    for($i = 0; $i < count($feriados); $i++){
                        $datas = implode("-",array_reverse(explode("/", $feriados[$i]['date'])));

                        $feriado = new Feriados([
                            'nome' => $feriados[$i]['name'],
                            'feriado' => $datas,
                            'uteis' => 0,
                            'obrigatorio' => 1,
                        ]);
                        $feriado->save();
                    };
                }else{
                    return redirect('/feriados')->with('atualizada', 'atualizada');
                }
            };
        }
        return redirect('/feriados')->with('success', 'success');
    }
}
