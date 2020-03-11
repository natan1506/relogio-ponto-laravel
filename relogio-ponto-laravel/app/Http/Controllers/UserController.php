<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Ponto;
use App\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
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
        }
        else {

            $users = DB::table('users')
            ->where('username', $user->username)
            ->get();
        }

        return view('users.index')->with('users', $users);
    }

    public function create()
    {
        if($this->checkAuth() == true){
            return redirect('login');
        }
        return view('users.create');
    }
    public function store(Request $request)
    {
        if($this->checkAuth() == true){
            return redirect('login');
        }
        // Define o valor default para a variável que contém o nome da imagem
        $nameFile = null;
        // Verifica se informou o arquivo e se é válido
        if ($request->hasFile('foto') && $request->file('foto')->isValid()) {
            // Define um aleatório para o arquivo baseado no timestamps atual
            $name = uniqid(date('HisYmd'));
            // Recupera a extensão do arquivo
            $extension = $request->foto->extension();
            // Define finalmente o nome
            $nameFile = "{$name}.{$extension}";

            // Faz o upload:
            $upload = $request->foto->storeAs('img', $nameFile);
            // Se tiver funcionado o arquivo foi armazenado em storage/app/public/img/nomedinamicoarquivo.extensao
        }
        $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        $users = new User([
            'nome' => $request->get('nome'),
            'username' => $request->get('username'),
            'filial' => $request->get('filial'),
            'foto' => $nameFile,
            'email' => $request->get('email'),
            'acesso' => $request->get('acesso'),
            'password' => Hash::make($request->get('password')),
        ]);
          $users->save();
          return redirect('/users')->with('success', 'Stock has been added');
    }

    public function show($id)
    {
        if($this->checkAuth() == true){
            return redirect('login');
        }

    }

    public function edit($id)
    {
        if($this->checkAuth() == true){
            return redirect('login');
        }
        $user = User::find($id);

        return view('users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        if($this->checkAuth() == true){
            return redirect('login');
        }
        // Define o valor default para a variável que contém o nome da imagem
        $nameFile = null;
        // Verifica se informou o arquivo e se é válido
        if ($request->hasFile('foto') && $request->file('foto')->isValid()) {
            // Define um aleatório para o arquivo baseado no timestamps atual
            $name = uniqid(date('HisYmd'));
            // Recupera a extensão do arquivo
            $extension = $request->foto->extension();
            // Define finalmente o nome
            $nameFile = "{$name}.{$extension}";

            // Faz o upload:
            $upload = $request->foto->storeAs('img', $nameFile);
            // Se tiver funcionado o arquivo foi armazenado em storage/app/public/img/nomedinamicoarquivo.extensao
        }

        $user = User::find($id);
        $user->nome = $request->get('nome');
        $user->username = $request->get('username');
        $user->email = $request->get('email');
        $user->filial = $request->get('filial');
        if($request->foto == null){
            unset($user->foto);
        }else{
            $user->foto = $nameFile;
        }
        $user->acesso = $request->get('acesso');
        $user->email = $request->get('email');

        if($request->password == null){
            unset($user->password);
        }else{
            $user->password = Hash::make($request->get('password'));
        }
        $user->save();

        return redirect('/users')->with('atualizado', 'atualizado');
    }

    public function destroy($id)
    {
        if($this->checkAuth() == true){
            return redirect('login');
        }
        $user = User::find($id);
        $user->delete();

        return redirect('/users')->with('apagado', 'apagado');
    }
}
