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

class PontoController extends Controller
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

        date_default_timezone_set('America/Sao_Paulo');

        $user = Auth::user();
        if ($user->can('full', Ponto::class)) {
            $users = DB::table('users')
                ->orderBy('nome', 'asc')
                ->get();

            $tablePontos = DB::table('pontos')
                ->get();
        }
        else {
            $users = DB::table('users')
                ->where('username', $user->username)
                ->get();

            $tablePontos = DB::table('pontos')
                ->where('matricula', $user->username)
                ->get();
        }
        return view('pontos.index')->with(['users' => $users, 'tabelaPonto'=> $tablePontos]);

    }
    public function create()
    {
        if($this->checkAuth() == true){
            return redirect('login');
        }

        return view('welcome');
    }
    public function store(Request $request)
    {
        date_default_timezone_set('America/Sao_Paulo');
        $date_time = new DateTime();

        $date = $date_time->format('Y-m-d');

        $matricula = $request->matricula;

        $tableUsers = DB::table('users')
            ->select('nome', 'username', 'foto')
            ->where('username','=', $matricula)
            ->first();

        if($tableUsers){
            $tablePonto = DB::table('pontos')
                ->where([['data' , $date],['matricula' , $matricula]])
                ->first();

            if(!$tablePonto){
                $ponto = new Ponto([
                    'nome' => $tableUsers->nome,
                    'matricula'=> $matricula,
                    'data'=> $date,
                    'entrada1'=> $date_time
                ]);

                $ponto->save();
                return redirect('/')->with(['success' => 'success', 'dados' => $tableUsers]);

            } else {
                foreach ($tablePonto as $key => $value){
                    if(!$value){
                        if($key === 'saida1'){
                            $prev = 'entrada1';

                        }elseif ($key === 'entrada2'){
                            $prev = 'saida1';

                        }elseif ($key === 'saida2'){
                            $prev = 'entrada2';

                        }elseif ($key === 'entrada3'){
                            $prev = 'saida2';

                        }elseif ($key === 'saida3') {
                            $prev = 'entrada3';

                        }

                        $ponto = DB::table('pontos')
                            ->where([['data', '=', $date],['matricula', '=', $matricula]])
                            ->first();

                        $date_time_ponto = new DateTime($ponto->$prev);

                        $dataBanco = explode('-', $ponto->data);
                        $time = explode(':', $date_time_ponto->format('H:i:s'));


                        $date_time_final_ponto = new DateTime();
                        $date_time_final_ponto->setDate($dataBanco[0], $dataBanco[1], $dataBanco[2]);
                        $date_time_final_ponto->setTime($time[0], $time[1], $time[2]);

                        $diff = date_diff($date_time_final_ponto, $date_time);

                        if ($diff->h < 1 && $diff->i <= 15 ){
                            return redirect('/')->with('preenchido' , 'você já bateu a '.$prev);

                        } else{
                            DB::table('pontos')
                                ->where([['data','=', $date], ['matricula', '=', $matricula]])
                                ->update([$key => $date_time]);

                            return redirect('/')->with(['success' => 'success', 'dados' => $tableUsers]);
                        }
                    }
                }

            }

        }else{
            return redirect('/')->with('sem-matricula' , 'sem-matricula');
        }
    }
    public function show( $matricula)
    {
        if($this->checkAuth() == true){
            return redirect('login');
        }

        $mesSelect = '01';

        $ano = date('Y');
        $data = date('Y-m-d');
        // $data = date('Y-m-d', strtotime("+1 days", strtotime($date)));

        $tableUsers = DB::table('users')
            ->where('username','=', $matricula)
            ->first();

        $tablePontosMes = DB::table('pontos')
            ->where('matricula', '=', $matricula)
            ->whereYear('data', $ano)
            ->orderBy('data', 'asc')
            ->get();

        $tableFerias = DB::table('ferias')
            ->where ('matricula', '=', $matricula)
            ->whereYear('data_inicial', $ano)
            ->whereYear('data_final', $ano)
            ->get();

        $tableColetivaInicial = DB::table('ferias_coletivas')
            ->whereYear('data_inicial', $ano)
            ->get();

        $tableColetivaFinal = DB::table('ferias_coletivas')
            ->whereYear('data_final', $ano)
            ->get();

        $tableFeriados = DB::table('feriados')
            ->select('feriado', 'uteis')
            ->where([
                ['uteis', '=', '0'],
                ['obrigatorio', '=', "0"]
            ])
            ->get();

        $valuesPonto = $tablePontosMes;

        $tablePontos = [];

        for($i = 0; $i < count($tablePontosMes); $i++){

            $mes = date('m', strtotime($tablePontosMes[$i]->data));

            if($mes == $mesSelect){
                array_push($tablePontos, $tablePontosMes[$i]);
            }
        }

        $anoTabela = array();

        $tableYears = DB::table('pontos')
            ->where('matricula', '=', $matricula)
            ->get();

        for($i = 0; $i < count($tableYears); $i ++){
            array_push($anoTabela, date('Y', strtotime($tableYears[$i]->data)));
        }

        $anoTabela = array_unique($anoTabela);
        rsort($anoTabela);

        //comeco saldo total
        $inicio = new DateTime($ano."-01-01");
        $fim = new DateTime($data);

        $periodo = new DatePeriod($inicio, new DateInterval('P1D'), $fim);

        $diasUteis = [];
        $sextas = [];
        foreach($periodo as $item){

            if(substr($item->format("D"), 0, 1) != 'S'){
                $diasUteis[] = $item->format('Y-m-d');
            }
            if(substr($item->format("D"), 0, 1) === 'F'){
                $sextas[] = $item->format('Y-m-d');
            }
        }

        $feriados = array();

        if(count($tableFerias) > 0){
            foreach ($tableFerias as $ferias){
                $inicial = new DateTime($ferias->data_inicial);
                $final = new DateTime($ferias->data_final);

                $periodoFerias = new DatePeriod($inicial, new DateInterval('P1D'), $final);

                foreach($periodoFerias as $item){
                    if(substr($item->format("D"), 0, 1) != 'S'){
                        array_push($feriados, $item->format('Y-m-d'));
                    }
                }
            }
        }
        if(count($tableColetivaInicial) > 0){
            foreach ($tableColetivaInicial as $coletivaInicial){

                $inicial = new DateTime($coletivaInicial->data_inicial);
                $final = new DateTime($coletivaInicial->data_final);

                $periodoColetivaInicial = new DatePeriod($inicial, new DateInterval('P1D'), $final);

                foreach($periodoColetivaInicial as $item){
                    if(substr($item->format("D"), 0, 1) != 'S'){
                        if(!in_array($item->format('Y-m-d'), $feriados)) {
                            array_push($feriados, $item->format('Y-m-d'));
                        }
                    }
                }
            }
        }
        if(count($tableColetivaFinal) > 0){

            foreach ($tableColetivaFinal as $coletivaFinal){

                $final = new DateTime($coletivaFinal->data_final);

                //$inicio vem da mesma variavel principal de periodo para dias uteis no comeco do saldo total
                $periodoColetivaFinal = new DatePeriod($inicio, new DateInterval('P1D'), $final);

                foreach($periodoColetivaFinal as $item){
                    if(substr($item->format("D"), 0, 1) != 'S'){
                        if(!in_array($item->format('Y-m-d'), $feriados)) {
                            array_push($feriados, $item->format('Y-m-d'));
                        }
                    }

                }
            }
        }
        for($i = 0; $i < count($tableFeriados); $i++){

            if(!in_array($tableFeriados[$i]->feriado, $feriados)) {
                array_push($feriados, $tableFeriados[$i]->feriado);
            }
        }

        asort($feriados);

        $feriados = array_values($feriados);

        $result = array_diff($diasUteis, $feriados);
        $result = array_values($result);

        $horasFaltantes = 0;

        foreach($result as $dias){

            $horasFaltantes = $horasFaltantes + 1;
        }
        $horasFaltantes = $horasFaltantes * 9;
        $horasFaltantes = $horasFaltantes - count($sextas);

        $horasFaltantes = -$horasFaltantes.":00:00";

        $horas = 00;
        $minutos = 00;

        foreach($valuesPonto as $ponto){

            // Faz o cálculo das horas
            $total = (strtotime($ponto->saida1) - strtotime($ponto->entrada1))
            + (strtotime($ponto->saida2) - strtotime($ponto->entrada2))
            + (strtotime($ponto->saida3) - strtotime($ponto->entrada3));

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
            $horas = 'negativa';

            return view('pontos.show',
                compact(
                    'tablePontos',
                    'tableUsers',
                    'anoTabela',
                    'horas',
                    'horasFinais',
                    'mesSelect',
                    'ano'
                )
            );
        }else{
            $horas = 'positiva';

            return view('pontos.show',
                compact(
                    'tablePontos',
                    'tableUsers',
                    'anoTabela',
                    'horas',
                    'horasFinais',
                    'mesSelect',
                    'ano'
                )
            );
        }
    }
    public function edit($id)
    {
        if($this->checkAuth() == true){
            return redirect('login');
        }

        $ponto = Ponto::find($id);
        return view('pontos.edit', compact('ponto'));
    }
    public function update(Request $request, $id)
    {
        if($this->checkAuth() == true){
            return redirect('login');
        }

        date_default_timezone_set('America/Sao_Paulo');

        $date_time = new DateTime($request->data);

        if($request->entrada1){
            $time = explode(':', $request->entrada1);
            $entrada1 = $date_time->setTime($time[0], $time[1]);
        }else{
            $entrada1 = null;
        }
        if($request->saida1){
            $time = explode(':', $request->saida1);
            $saida1 = $date_time->setTime($time[0], $time[1]);
        }else{
            $saida1 = null;
        }
        if($request->entrada2){
            $time = explode(':', $request->entrada2);
            $entrada2 = $date_time->setTime($time[0], $time[1]);
        }else{
            $entrada2 = null;
        }
        if($request->saida2){
            $time = explode(':', $request->saida2);
            $saida2 = $date_time->setTime($time[0], $time[1]);
        }else{
            $saida2 = null;
        }
        if($request->entrada3){
            $time = explode(':', $request->entrada3);
            $entrada3 = $date_time->setTime($time[0], $time[1]);
        }else{
            $entrada3 = null;
        }
        if($request->saida3){
            $time = explode(':', $request->saida3);
            $saida3 = $date_time->setTime($time[0], $time[1]);
        }else{
            $saida3 = null;
        }


        $ponto = Ponto::find($id);
        $ponto->nome = $request->get('nome');
        $ponto->matricula = $request->get('matricula');
        $ponto->entrada1 =  $entrada1;
        $ponto->saida1 = $saida1;
        $ponto->entrada2 = $entrada2;
        $ponto->saida2 = $saida2;
        $ponto->entrada3 = $entrada3;
        $ponto->saida3 = $saida3;
        $ponto->observacao = $request->get('observacao');
        $ponto->save();

        return redirect('/pontos/'.$ponto->matricula)->with('success', 'registro atualizado com sucesso!');
    }
    public function destroy($id)
    {
        if($this->checkAuth() == true){
            return redirect('login');
        }

        $ponto = Ponto::find($id);
        $ponto->delete();
        return redirect('/pontos/'.$ponto->matricula)->with('success', 'registro apagado com sucesso!');
    }
    public function periodo(Request $request)
    {
        if($this->checkAuth() == true){
            return redirect('login');
        }

        $user = $request->nome;
        $inicial = $request->inicial;
        $final = $request->final;

        $tablePontos = DB::table('pontos')
            ->where('nome','=', $user)
            ->whereBetween('data', [$inicial, $final])
            ->get();

        return view('pontos.periodo')->with(['table' => $tablePontos , 'request' => $request]);
    }
    public function search(Request $request)
    {
        if($this->checkAuth() == true){
            return redirect('login');
        }

        $matricula = $request->matricula;

        if(!$request->mes) {
            $mesSelect = '01';
        }else{
            $mesSelect = $request->mes;
        }

        if(!$request->ano){
            $ano = date('Y');
        }else{
            $ano = $request->ano;
        }

        if ($request->mes && !$request->ano){
            $tablePontosMes = DB::
            select("SELECT * FROM relogio_ponto.pontos WHERE MONTH(data) = {$mesSelect} AND matricula = {$matricula} ORDER BY data asc");

        }elseif (!$request->mes && $request->ano){

            $tablePontosMes = DB::
            select("SELECT * FROM relogio_ponto.pontos WHERE YEAR(data) = {$request['ano']} AND MONTH(data) = {$mesSelect} AND matricula = {$matricula} ORDER BY data asc");

        }elseif ($request->mes && $request->ano){

            $tablePontosMes = DB::
            select("SELECT * FROM relogio_ponto.pontos WHERE MONTH(data) = {$mesSelect} AND YEAR(data) = {$request['ano']}
                    AND matricula = {$matricula} ORDER BY data asc");

        }elseif(!$request->mes && !$request->ano){
            return redirect('pontos.show');
        }

        $tablePontosMes = collect($tablePontosMes);

        $data = date('Y-m-d');
        // $data = date('Y-m-d', strtotime("+1 days", strtotime($date)));

        $tableUsers = DB::table('users')
            ->where('username','=', $matricula)
            ->first();

        $tableFeriados = DB::table('feriados')
            ->select('feriado', 'uteis')
            ->where([
                ['uteis', '=', '0'],
                ['obrigatorio', '=', "0"]
            ])
            ->get();

        $tableFerias = DB::table('ferias')
            ->where ('matricula', '=', $matricula)
            ->whereYear('data_inicial', $ano)
            ->whereYear('data_final', $ano)
            ->get();

        $tableColetivaInicial = DB::table('ferias_coletivas')
            ->whereYear('data_inicial', $ano)
            ->get();

        $tableColetivaFinal = DB::table('ferias_coletivas')
            ->whereYear('data_final', $ano)
            ->get();

        $valuesPonto = DB::table('pontos')
            ->where('matricula', '=', $matricula)
            ->whereYear('data', $ano)
            ->orderBy('data', 'asc')
            ->get();

        $tablePontos = [];

        for($i = 0; $i < count($tablePontosMes); $i++){

            $mes = date('m', strtotime($tablePontosMes[$i]->data));

            if($mes == $mesSelect){
                array_push($tablePontos, $tablePontosMes[$i]);
            }
        }

        $anoTabela = array();

        $anos = DB::table('pontos')
            ->where('matricula', '=', $matricula)
            ->get();

        for($i = 0; $i < count($anos); $i ++){
            array_push($anoTabela, date('Y', strtotime($anos[$i]->data)));

        }

        $anoTabela = array_unique($anoTabela);
        rsort($anoTabela);

        //comeco saldo total
        $inicio = new DateTime($ano."-01-01");
        $fim = new DateTime($data);

        $periodo = new DatePeriod($inicio, new DateInterval('P1D'), $fim);

        $diasUteis = [];
        $sextas = [];
        foreach($periodo as $item){

            if(substr($item->format("D"), 0, 1) != 'S'){
                $diasUteis[] = $item->format('Y-m-d');
            }
            if(substr($item->format("D"), 0, 1) === 'F'){
                $sextas[] = $item->format('Y-m-d');
            }
        }

        $feriados = array();

        if(count($tableFerias) > 0){
            foreach ($tableFerias as $ferias){
                $inicial = new DateTime($ferias->data_inicial);
                $final = new DateTime($ferias->data_final);

                $periodoFerias = new DatePeriod($inicial, new DateInterval('P1D'), $final);

                foreach($periodoFerias as $item){
                    if(substr($item->format("D"), 0, 1) != 'S'){
                        array_push($feriados, $item->format('Y-m-d'));
                    }
                }
            }
        }
        if(count($tableColetivaInicial) > 0){
            foreach ($tableColetivaInicial as $coletivaInicial){

                $inicial = new DateTime($coletivaInicial->data_inicial);
                $final = new DateTime($coletivaInicial->data_final);

                $periodoColetivaInicial = new DatePeriod($inicial, new DateInterval('P1D'), $final);

                foreach($periodoColetivaInicial as $item){
                    if(substr($item->format("D"), 0, 1) != 'S'){
                        if(!in_array($item->format('Y-m-d'), $feriados)) {
                            array_push($feriados, $item->format('Y-m-d'));
                        }
                    }
                }
            }
        }
        if(count($tableColetivaFinal) > 0){

            foreach ($tableColetivaFinal as $coletivaFinal){

                $final = new DateTime($coletivaFinal->data_final);

                //$inicio vem da mesma variavel principal de periodo para dias uteis no comeco do saldo total
                $periodoColetivaFinal = new DatePeriod($inicio, new DateInterval('P1D'), $final);

                foreach($periodoColetivaFinal as $item){
                    if(substr($item->format("D"), 0, 1) != 'S'){
                        if(!in_array($item->format('Y-m-d'), $feriados)) {
                            array_push($feriados, $item->format('Y-m-d'));
                        }
                    }
                }
            }
        }
        for($i = 0; $i < count($tableFeriados); $i++){

            if(!in_array($tableFeriados[$i]->feriado, $feriados)) {
                array_push($feriados, $tableFeriados[$i]->feriado);
            }
        }

        asort($feriados);

        $feriados = array_values($feriados);

        $result = array_diff($diasUteis, $feriados);
        $result = array_values($result);

        $horasFaltantes = 0;

        $horasFaltantes = count($result) * 9;

        $horasFaltantes = $horasFaltantes - count($sextas);

        $horasFaltantes = -$horasFaltantes.":00:00";

        $horas = 00;
        $minutos = 00;


        foreach($valuesPonto as $ponto){
            // Faz o cálculo das horas
            $total = (strtotime($ponto->saida1) - strtotime($ponto->entrada1))
                + (strtotime($ponto->saida2) - strtotime($ponto->entrada2))
                + (strtotime($ponto->saida3) - strtotime($ponto->entrada3));

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

        $request->flash();

        if($horasFinais < 0){
            $horas = 'negativa';

            return view('pontos.show',
                compact(
                    'tablePontos',
                    'tableUsers',
                    'anoTabela',
                    'horas',
                    'horasFinais',
                    'mesSelect',
                    'ano'
                )
            );
        }else{
            $horas = 'positiva';

            return view('pontos.show',
                compact(
                    'tablePontos',
                    'tableUsers',
                    'anoTabela',
                    'horas',
                    'horasFinais',
                    'mesSelect',
                    'ano'
                )
            );
        }
    }
    public function relatorioTotal()
    {
        if($this->checkAuth() == true){
            return redirect('login');
        }

        $user = Auth::user();
        if ($user->can('full', Ponto::class)) {
            $ano = date('Y');
            $date = date('Y-m-d');
            $data = date('Y-m-d', strtotime("+1 days",strtotime($date)));

            $tableUsers = DB::table('users')
                ->orderBy('nome', 'asc')
                ->get();


            $totaisUser = [];

            foreach($tableUsers as $user){

                $control = [];

                $tablePontos = DB::table('pontos')
                    ->where('matricula', '=', $user->username)
                    ->whereYear('data', $ano)
                    ->orderBy('data', 'asc')
                    ->get();

                $tableFerias = DB::table('ferias')
                    ->where ('matricula', '=', $user->username)
                    ->whereYear('data_inicial', $ano)
                    ->whereYear('data_final', $ano)
                    ->get();

                $tableColetivaInicial = DB::table('ferias_coletivas')
                    ->whereYear('data_inicial', $ano)
                    ->get();

                $tableColetivaFinal = DB::table('ferias_coletivas')
                    ->whereYear('data_final', $ano)
                    ->get();

                $name = $user->nome;

                $tableFeriados = DB::table('feriados')
                    ->select('feriado', 'uteis')
                    ->where('uteis', '=', '0')
                    ->get();

                //COMEÇO CALCULO TOTAL

                $inicio = new DateTime($ano."-01-01");
                $fim = new DateTime($data);

                $periodo = new DatePeriod($inicio, new DateInterval('P1D'), $fim);
                $diasUteis = [];
                foreach($periodo as $item){
                    if(substr($item->format("D"), 0, 1) != 'S'){
                        $diasUteis[] = $item->format('Y-m-d');
                    }
                }

                $feriados = array();

                if(count($tableFerias) > 0){
                    foreach ($tableFerias as $ferias){
                        $inicial = new DateTime($ferias->data_inicial);
                        $final = new DateTime($ferias->data_final);

                        $periodoFerias = new DatePeriod($inicial, new DateInterval('P1D'), $final);

                        foreach($periodoFerias as $item){
                            if(substr($item->format("D"), 0, 1) != 'S'){
                                array_push($feriados, $item->format('Y-m-d'));
                            }
                        }
                    }
                }

                if(count($tableColetivaInicial) > 0){
                    foreach ($tableColetivaInicial as $coletivaInicial){

                        $inicial = new DateTime($coletivaInicial->data_inicial);
                        $final = new DateTime($coletivaInicial->data_final);

                        $periodoColetivaInicial = new DatePeriod($inicial, new DateInterval('P1D'), $final);

                        foreach($periodoColetivaInicial as $item){
                            if(substr($item->format("D"), 0, 1) != 'S'){
                                if(!in_array($item->format('Y-m-d'), $feriados)) {
                                    array_push($feriados, $item->format('Y-m-d'));
                                }
                            }
                        }
                    }
                }

                if(count($tableColetivaFinal) > 0){

                    foreach ($tableColetivaFinal as $coletivaFinal){

                        $final = new DateTime($coletivaFinal->data_final);

                        //$inicio vem da mesma variavel principal de periodo para dias uteis no comeco do saldo total
                        $periodoColetivaFinal = new DatePeriod($inicio, new DateInterval('P1D'), $final);

                        foreach($periodoColetivaFinal as $item){
                            if(substr($item->format("D"), 0, 1) != 'S'){
                                if(!in_array($item->format('Y-m-d'), $feriados)) {
                                    array_push($feriados, $item->format('Y-m-d'));
                                }
                            }
                        }
                    }
                }

                for($i = 0; $i < count($tableFeriados); $i++){

                    if(!in_array($tableFeriados[$i]->feriado, $feriados)) {
                        array_push($feriados, $tableFeriados[$i]->feriado);
                    }
                }

                asort($feriados);

                $feriados = array_values($feriados);

                $result = array_diff($diasUteis, $feriados);
                $result = array_values($result);

                $horasFaltantes = 0;

                foreach($result as $dias){

                    $horasFaltantes = $horasFaltantes + 1;

                }

                $horasFaltantes = $horasFaltantes * 9;

                $horasFaltantes = -$horasFaltantes.":00:00";

                $horas = 00;
                $minutos = 00;

                foreach($tablePontos as $ponto){

                    // Faz o cálculo das horas
                    $total = (strtotime($ponto->saida1) - strtotime($ponto->entrada1))
                    + (strtotime($ponto->saida2) - strtotime($ponto->entrada2))
                    + (strtotime($ponto->saida3) - strtotime($ponto->entrada3));

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

                //FINAL CALCULO TOTAL
                array_push($control, $name, $horasFinais);
                array_push($totaisUser, $control);
            }
            return view('pontos.relatorio', compact('totaisUser'));
        }
    }
    public function storeManual(Request $request)
    {
        if($this->checkAuth() == true){
            return redirect('login');
        }

        date_default_timezone_set('America/Sao_Paulo');

        $tableUsers = DB::table('users')
                        ->where('username','=', $request->matricula)
                        ->first();

        if($tableUsers){

            $nome = $tableUsers->nome;

            $ponto = new Ponto([
                'nome' => $nome,
                'matricula'=> $request->matricula,
                'data'=> $request->data,
                'entrada1'=> new DateTime($request->entrada1),
                'saida1' => $request->saida1 ? new DateTime($request->saida1) : null,
                'entrada2' => $request->entrada2 ? new DateTime($request->entrada2) : null,
                'saida2' => $request->saida2 ? new DateTime($request->saida2) : null,
                'entrada3' => $request->entrada3 ? new DateTime($request->entrada3) : null,
                'saida3' => $request->saida3 ? new DateTime($request->saida3) : null,
                'observacao' => $request->observacao,
                ]);

            $ponto->save();

            return redirect('/pontos')->with(['success' => 'ponto criado com sucesso!', 'dados' => $tableUsers]);

        }
    }

    public function atualizaAut(Request $request)
    {
        if($request->key === 'natan'){
            $tableUsers = DB::table('users')
                ->where('username','=', $request->matricula)
                ->first();

            $ano = date('Y');
            $date = date('Y-m-d');
            $data = date('Y-m-d', strtotime("+1 days", strtotime($date)));

            //comeco saldo total
            $inicio = new DateTime($ano."-01-01");
            $fim = new DateTime($data);

            $periodo = new DatePeriod($inicio, new DateInterval('P1D'), $fim);

            foreach($periodo as $item){

                if(substr($item->format("D"), 0, 1) != 'S'){
                    if(substr($item->format("D"), 0, 1) === 'F') {
                        $date_time = new DateTime($item->format('Y-m-d'));

                        $entrada1 =  new DateTime($item->format('Y-m-d'));
                        $entrada1->setTime('08', '00');

                        $saida1 = new DateTime($item->format('Y-m-d'));
                        $saida1->setTime('12', '00');

                        $entrada2 = new DateTime($item->format('Y-m-d'));
                        $entrada2->setTime('13', '00');

                        $saida2 = new DateTime($item->format('Y-m-d'));
                        $saida2->setTime('17', '00');

                        $ponto = new Ponto([
                            'nome' => $tableUsers->nome,
                            'matricula'=> $tableUsers->username,
                            'data'=> $item->format('Y-m-d'),
                            'entrada1' =>  $entrada1,
                            'saida1' => $saida1,
                            'entrada2' => $entrada2,
                            'saida2' => $saida2,
                        ]);

                        $ponto->save();
                    } else {
                        $date_time = new DateTime($item->format('Y-m-d'));

                        $entrada1 =  new DateTime($item->format('Y-m-d'));
                        $entrada1->setTime('08', '00');

                        $saida1 = new DateTime($item->format('Y-m-d'));
                        $saida1->setTime('12', '00');

                        $entrada2 = new DateTime($item->format('Y-m-d'));
                        $entrada2->setTime('13', '00');

                        $saida2 = new DateTime($item->format('Y-m-d'));
                        $saida2->setTime('18', '00');

                        $ponto = new Ponto([
                            'nome' => $tableUsers->nome,
                            'matricula'=> $tableUsers->username,
                            'data'=> $item->format('Y-m-d'),
                            'entrada1' =>  $entrada1,
                            'saida1' => $saida1,
                            'entrada2' => $entrada2,
                            'saida2' => $saida2,
                        ]);

                        $ponto->save();
                    }


                }
            }

            echo 'ok';

        }else {
            return redirect('/');
        }
    }
}
