<?php

Auth::routes(['register' => false]);
Route::get('/test', function () {
    return view('/auth/passwords/reset');
});

Route::get('/', function () {
    return view('welcome');
});
Route::get('/home', 'HomeController@index')->name('home');

Route::get('pontos/periodo', 'PontoController@periodo')->name('pontos.periodo');
Route::post('pontos/create/manual', 'PontoController@storeManual')->name('pontos.create.manual');
Route::get('pontos/relatorio', 'PontoController@relatorioTotal')->name('pontos.relatorio');
Route::get('pontos/search', 'PontoController@search')->name('pontos.search');
Route::get('pontos/atualiza', 'PontoController@atualizaAut')->name('pontos.atualizaAut');

Route::resource('pontos', 'PontoController');

Route::get('feriados/index', 'FeriadoController@atualizaFeriados')->name('feriados.atualiza-feriados');
Route::post('feriados/excluir-feriados', 'FeriadoController@destroyFeriados')->name('feriados.destroy-feriados');
Route::get('feriados/excluir-recessos', 'FeriadoController@destroyRecessos')->name('feriados.destroy-recessos');
Route::resource('feriados', 'FeriadoController');
Route::resource('ferias', 'FeriasController');
Route::resource('ferias-coletiva', 'FeriasColetivaController');

Route::resource('users', 'UserController');

Route::resource('recessos', 'RecessoController');
