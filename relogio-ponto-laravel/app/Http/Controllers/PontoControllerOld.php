<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ponto;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp;
use DateTime;
use DatePeriod;
use DateInterval;

class PontoControllerOld extends Controller
{
    public function checkAuth()
    {
        if(!auth()->check()) {
            return redirect('login');
        }
    }

    public function index()
    {
        dd('entrou');
        $this->checkAuth();
        $user = Auth::user();
        if ($user->can('full', Ponto::class)) {

            $users = DB::table('users')
            ->orderBy('nome', 'asc')
            ->get();

            $tabelaPonto = DB::table('pontos')
            ->get();

        }
        else {

            $users = DB::table('users')
            ->where('matricula', $user->matricula)
            ->get();

            $tabelaPonto = DB::table('pontos')
            ->where('matricula', $user->matricula)
            ->get();
        }
        return view('pontos.index')->with(['users' => $users, 'tabelaPonto'=> $tabelaPonto]);
    }

    public function create()
    {
        return view('welcome');
    }

    public function store(Request $request)
    {
        date_default_timezone_set('America/Sao_Paulo');
        $hora = date('H:i');
        $date = date('Y-m-d');
        $matricula = $request->matricula;
        $manual = $request->manual;

        $tabelaPonto = DB::table('pontos')
            ->where('data','=', $date )
            ->where('matricula', '=', $matricula)
            ->get();

        $tabelaUsers = DB::table('users')
            ->where('matricula','=', $matricula)
            ->get();

        if($tabelaUsers != '[]'){

            $nome = $tabelaUsers[0]->nome;

            if($manual == 'normal'){
                if($hora >= '06:00' && $hora <= '09:59'){
                    if($tabelaPonto == "[]"){
                        $ponto = new Ponto([
                            'nome' => $nome,
                            'matricula'=> $matricula,
                            'data'=> $date,
                            'entrada1'=> $hora
                        ]);

                        $ponto->save();

                        return redirect('/')->with(['success' => 'success', 'dados' => $tabelaUsers[0]]);
                    }else{
                        return redirect('/')->with('preenchido' , 'preenchido');
                    }

                }else if( $tabelaPonto != "[]" ){
                    if($tabelaPonto[0]->entrada1 != null && $hora >= '10:00' && $hora <= '12:30'){
                        if($tabelaPonto[0]->saida1 == null){
                            dd($tabelaPonto);
                            DB::table('pontos')
                            ->where('data','=', $date )
                            ->where('matricula', '=', $matricula)
                            ->update(['saida1' => $hora]);

                            return redirect('/')->with(['success' => 'success', 'dados' => $tabelaUsers[0]]);

                        }else{
                            return redirect('/')->with('preenchido' , 'preenchido');
                        }

                    }else if($tabelaPonto[0]->saida1 != null && $hora >= '12:31' && $hora <= '15:59'){
                        if($tabelaPonto[0]->entrada2 == null){
                            //dd($tabelaPonto);
                            DB::table('pontos')
                            ->where('data','=', $date )
                            ->where('matricula', '=', $matricula)
                            ->update(['entrada2' => $hora]);

                            return redirect('/')->with(['success' => 'success', 'dados' => $tabelaUsers[0]]);
                        }else{
                            return redirect('/')->with('preenchido' , 'preenchido');
                        }

                    }else if($tabelaPonto[0]->entrada2 != null && $hora >= '16:00'){
                        if($tabelaPonto[0]->saida2 == null){
                            DB::table('pontos')
                            ->where('data','=', $date )
                            ->where('matricula', '=', $matricula)
                            ->update(['saida2' => $hora]);

                            return redirect('/')->with(['success' => 'success', 'dados' => $tabelaUsers[0]]);
                        }else{
                            return redirect('/')->with('preenchido' , 'preenchido');
                        }

                    }else if($tabelaPonto[0]->saida2 != null && $hora >= '16:00'){
                        if($tabelaPonto[0]->hora_extra_entrada == null){
                            DB::table('pontos')
                            ->where('data','=', $date )
                            ->where('matricula', '=', $matricula)
                            ->update(['hora_extra_entrada' => $hora]);

                            return redirect('/')->with(['success' => 'success', 'dados' => $tabelaUsers[0]]);
                        }else{
                            return redirect('/')->with('preenchido' , 'preenchido');
                        }

                    }else if($tabelaPonto[0]->hora_extra_entrada != null && $hora >= '16:00'){
                        if($tabelaPonto[0]->hora_extra_saida == null){
                            DB::table('pontos')
                            ->where('data','=', $date )
                            ->where('matricula', '=', $matricula)
                            ->update(['hora_extra_saida' => $hora]);

                            return redirect('/')->with(['success' => 'success', 'dados' => $tabelaUsers[0]]);
                        }else{
                            return redirect('/')->with('preenchido' , 'preenchido');
                        }

                    }
                }
            }else if ($manual == "entrada1" ){
                //dd($tabelaPonto);
                if($tabelaPonto == "[]"){
                    //dd($manual);
                    $ponto = new Ponto([
                        'nome' => $nome,
                        'matricula'=> $matricula,
                        'data'=> $date,
                        'entrada1'=> $hora
                    ]);

                    $ponto->save();

                    return redirect('/')->with(['success' => 'success', 'dados' => $tabelaUsers[0]]);
                }else{
                    return redirect('/')->with('preenchido' , 'preenchido');
                }

            }else if ($manual == "saida1" &&  $tabelaPonto != '[]' && $tabelaPonto[0]->entrada1 != null){
                if($tabelaPonto[0]->saida1 == null){
                    DB::table('pontos')
                    ->where('data','=', $date )
                    ->where('matricula', '=', $matricula)
                    ->update(['saida1' => $hora]);

                    return redirect('/')->with(['success' => 'success', 'dados' => $tabelaUsers[0]]);
                }else{
                    return redirect('/')->with('preenchido' , 'preenchido');
                }

            }else if ($manual == "entrada2" && $tabelaPonto[0]->saida1 != null){
                if($tabelaPonto[0]->entrada2 == null){
                    DB::table('pontos')
                    ->where('data','=', $date )
                    ->where('matricula', '=', $matricula)
                    ->update(['entrada2' => $hora]);

                    return redirect('/')->with(['success' => 'success', 'dados' => $tabelaUsers[0]]);
                }else{
                    return redirect('/')->with('preenchido' , 'preenchido');
                }

            }else if ($manual == "saida2" && $tabelaPonto[0]->entrada2 != null){
                if($tabelaPonto[0]->saida2 == null){
                    DB::table('pontos')
                    ->where('data','=', $date )
                    ->where('matricula', '=', $matricula)
                    ->update(['saida2' => $hora]);

                return redirect('/')->with(['success' => 'success', 'dados' => $tabelaUsers[0]]);
                }else{
                    return redirect('/')->with('preenchido' , 'preenchido');
                }
            }

            return redirect('/')->with('danger' , 'danger');
        }else{
            return redirect('/')->with('sem-matricula' , 'sem-matricula');
        }
    }

    public function show($matricula)
    {
        $this->checkAuth();
        $year = date('Y');
        $date = date('Y-m-d');
        $data = date('Y-m-d', strtotime("+1 days",strtotime($date)));

        $tabelaUsers = DB::table('users')
            ->where('matricula','=', $matricula)
            ->get();

        $tabelaPonto = DB::table('pontos')
            ->where('matricula', '=', $matricula)
            ->get();

        $tabelaFeriados = DB::table('feriados')
            ->where('uteis', '=', '0')
            ->get();

        $anoTabela = array();

        for($i = 0; $i < count($tabelaPonto); $i ++){

            array_push($anoTabela, date('Y', strtotime($tabelaPonto[$i]->data)));
        }
        $anoTabela = array_unique($anoTabela);
        rsort($anoTabela);

        //comeco saldo total
        $inicio = new DateTime($year."-01-01");
        $fim = new DateTime($data);

        $periodo = new DatePeriod($inicio, new DateInterval('P1D'), $fim);

        $diasUteis = [];
        foreach($periodo as $item){

            if(substr($item->format("D"), 0, 1) != 'S'){
                $diasUteis[] = $item->format('Y-m-d');
            }
        }

        $feriados = array();

        for($i = 0; $i < count($tabelaFeriados); $i++){
            array_push($feriados, $tabelaFeriados[$i]->feriado);
        }

        $result = array_diff($diasUteis, $feriados);

        $horasFaltantes = 0;

        foreach($result as $dias){

            $horasFaltantes = $horasFaltantes + 1;

        }

        $horasFaltantes = $horasFaltantes * 9;
        $horasFaltantes = -$horasFaltantes.":00:00";

        $horas = 00;
        $minutos = 00;

        foreach($tabelaPonto as $ponto){
            // Faz o cálculo das horas
            $total = (strtotime($ponto->saida1) - strtotime($ponto->entrada1))
            + (strtotime($ponto->saida2) - strtotime($ponto->entrada2))
            + (strtotime($ponto->hora_extra_saida) - strtotime($ponto->hora_extra_entrada));

            // Encontra as horas trabalhadas
            $hours = floor($total / 60 / 60);

            // Encontra os minutos trabalhados
            $minutes = round(($total - ($hours * 60 * 60)) / 60);

            // Formata a hora e minuto para ficar no formato de 2 números, exemplo 00
            $hours = str_pad($hours, 2, "0", STR_PAD_LEFT);

            $minutes = str_pad($minutes, 2, "0", STR_PAD_LEFT);

            // Exibe no formato "hora:minuto"
            if($hours > 0){

                $horas = $hours + $horas;
                $minutos = $minutes + $minutos;

                while($minutos >= '60'){
                    $minutos = $minutos - 60;
                    $horas++;
                }
                $minutos = $minutos;
            }
        }
        $horasMes = $horas.":".$minutos.":00";

        $horas = array($horasFaltantes, $horasMes);

        $seconds = 0;

        foreach ( $horas as $hora )
        {
            list( $g, $i, $s ) = explode( ':', $hora );
            if ($g < 0) {
                $i *= -1;
                $s *= -1;
            }
            $seconds += $g * 3600;
            $seconds += $i * 60;
            $seconds += $s;
        }

        $hours    = floor( $seconds / 3600 );
        $seconds -= $hours * 3600;
        $minutes  = floor( $seconds / 60 );
        $seconds -= $minutes * 60;
        $hours = $hours + 1;
        $minutes = str_pad($minutes, 2, "0", STR_PAD_LEFT);

        $horasFinais = "{$hours}:{$minutes}";

        if($horasFinais < 0){
            return view('pontos.show')->with(['table'=> $tabelaPonto , 'foto'=> $tabelaUsers ,
            'anoTabela'=>$anoTabela, 'horas'=> 'negativa', 'horasTotais'=> $horasFinais]);
        }else{
            return view('pontos.show')->with(['table'=> $tabelaPonto , 'foto'=> $tabelaUsers ,
            'anoTabela'=>$anoTabela, 'horas'=> 'positiva', 'horasTotais'=> $horasFinais]);
        }
    }

    public function edit($id)
    {
        $this->checkAuth();
        $ponto = Ponto::find($id);
        return view('pontos.edit', compact('ponto'));
    }

    public function update(Request $request, $id)
    {
        $this->checkAuth();
        $ponto = Ponto::find($id);
        $ponto->nome = $request->get('nome');
        $ponto->matricula = $request->get('matricula');
        $ponto->entrada1 = $request->get('entrada1');
        $ponto->saida1 = $request->get('saida1');
        $ponto->entrada2 = $request->get('entrada2');
        $ponto->saida2 = $request->get('saida2');
        $ponto->hora_extra_entrada = $request->get('hora_extra_entrada');
        $ponto->hora_extra_saida = $request->get('hora_extra_saida');
        $ponto->observacao = $request->get('observacao');
        $ponto->save();

        return redirect('/pontos/'.$ponto->matricula)->with('success', 'Informações atualizadas');
    }

    public function destroy($id)
    {
        $this->checkAuth();
        $ponto = Ponto::find($id);
        $ponto->delete();

        return redirect('/pontos/'.$ponto->matricula)->with('apagado', 'apagado');
    }

    public function periodo(Request $request)
    {
        $this->checkAuth();
        $user = $request->nome;
        $inicial = $request->inicial;
        $final = $request->final;

        $tabelaPonto = DB::table('pontos')
            ->where('nome','=', $user)
            ->whereBetween('data', [$inicial, $final])
            ->get();

        return view('pontos.periodo')->with(['table' => $tabelaPonto , 'request' => $request]);
    }

    public function consultaAno(Request $request)
    {
        $this->checkAuth();
        $matricula = $request->matricula;
        $selectAno = $request->selectAno;

        $year = date('Y');
        $date = date('Y-m-d');
        $data = date('Y-m-d', strtotime("+1 days",strtotime($date)));

        $tabelaUsers = DB::table('users')
            ->where('matricula','=', $matricula)
            ->get();

        $tabelaPonto = DB::table('pontos')
            ->whereYear('data', '=', $selectAno)
            ->where('matricula', '=', $matricula)
            ->get();

        $tabelaPontoAno = DB::table('pontos')
            ->where('matricula', '=', $matricula)
            ->get();

        $tabelaFeriados = DB::table('feriados')
            ->where('uteis', '=', '0')
            ->get();

        $anoTabela = array();

        for($i = 0; $i < count($tabelaPontoAno); $i ++){

            array_push($anoTabela, date('Y', strtotime($tabelaPontoAno[$i]->data)));
        }

        $anoTabela = array_unique($anoTabela);
        rsort($anoTabela);

        //comeco saldo total
        $inicio = new DateTime($year."-01-01");
        $fim = new DateTime($data);

        $periodo = new DatePeriod($inicio, new DateInterval('P1D'), $fim);

        $diasUteis = [];
        foreach($periodo as $item){

            if(substr($item->format("D"), 0, 1) != 'S'){
                $diasUteis[] = $item->format('Y-m-d');
            }
        }

        $feriados = array();

        for($i = 0; $i < count($tabelaFeriados); $i++){
            array_push($feriados, $tabelaFeriados[$i]->feriado);
        }

        $result = array_diff($diasUteis, $feriados);

        $horasFaltantes = 0;

        foreach($result as $dias){

            $horasFaltantes = $horasFaltantes + 1;
        }

        $horasFaltantes = $horasFaltantes * 9;
        $horasFaltantes = -$horasFaltantes.":00:00";

        $horas = 00;
        $minutos = 00;

        foreach($tabelaPonto as $ponto){
            // Faz o cálculo das horas
            $total = (strtotime($ponto->saida1) - strtotime($ponto->entrada1))
            + (strtotime($ponto->saida2) - strtotime($ponto->entrada2))
            + (strtotime($ponto->hora_extra_saida) - strtotime($ponto->hora_extra_entrada));

            // Encontra as horas trabalhadas
            $hours = floor($total / 60 / 60);

            // Encontra os minutos trabalhados
            $minutes = round(($total - ($hours * 60 * 60)) / 60);

            // Formata a hora e minuto para ficar no formato de 2 números, exemplo 00
            $hours = str_pad($hours, 2, "0", STR_PAD_LEFT);

            $minutes = str_pad($minutes, 2, "0", STR_PAD_LEFT);

            // Exibe no formato "hora:minuto"
            if($hours > 0){

                $horas = $hours + $horas;
                $minutos = $minutes + $minutos;

                while($minutos >= '60'){
                    $minutos = $minutos - 60;
                    $horas++;
                }
                $minutos = $minutos;
            }
        }
        $horasMes = $horas.":".$minutos.":00";

        $horas = array($horasFaltantes, $horasMes);

        $seconds = 0;

        foreach ( $horas as $hora )
        {
            list( $g, $i, $s ) = explode( ':', $hora );
            if ($g < 0) {
                $i *= -1;
                $s *= -1;
            }
            $seconds += $g * 3600;
            $seconds += $i * 60;
            $seconds += $s;
        }

        $hours    = floor( $seconds / 3600 );
        $seconds -= $hours * 3600;
        $minutes  = floor( $seconds / 60 );
        $seconds -= $minutes * 60;
        $hours = $hours + 1;
        $minutes = str_pad($minutes, 2, "0", STR_PAD_LEFT);

        $horasFinais = "{$hours}:{$minutes}";

        if($horasFinais < 0){
            return view('pontos.show')->with(['table'=> $tabelaPonto , 'foto'=> $tabelaUsers ,
            'anoTabela'=>$anoTabela, 'horas'=> 'negativa', 'horasTotais'=> $horasFinais, 'anoSelect'=>$selectAno]);
        }else{
            return view('pontos.show')->with(['table'=> $tabelaPonto , 'foto'=> $tabelaUsers ,
            'anoTabela'=>$anoTabela, 'horas'=> 'positiva', 'horasTotais'=> $horasFinais, 'anoSelect'=>$selectAno]);
        }
    }
}
