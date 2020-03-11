<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Recesso;

class RecessoController extends Controller
{

    public function checkAuth()
    {
        if(!auth()->check()) {
            return true;
        }
    }

    public function index()
    {
        //
    }

    public function create()
    {
        if($this->checkAuth() == true){
            return redirect('login');
        }

        return view('recessos.create');
    }

    public function store(Request $request)
    {
        if($this->checkAuth() == true){
            return redirect('login');
        }

        $recesso = new Recesso([
            'nome' => $request->get('nome'),
            'recesso' => $request->get('recesso')
        ]);
        $recesso->save();
        return redirect('/recessos/create')->with('success', 'success');
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

        $recesso = Recesso::find($id);
        $recesso->delete();

        return redirect('/feriados/show')->with('recesso', 'recesso');
    }
}
