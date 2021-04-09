<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::namespace('api')->name('api.')->group(function(){
    Route::prefix('moedas')->group(function(){
        //Endpoints???
        Route::get('/', 'MoedaController@index')->name('moedas'); //GET - http://127.0.0.1:8000/api/moedas/
        Route::get('/{id}', 'MoedaController@show')->name('show_moeda');//GET - http://127.0.0.1:8000/api/moedas/id
        Route::post('/', 'MoedaController@store')->name('store_moeda');//POST - http://127.0.0.1:8000/api/moedas/
        Route::put('/{id}', 'MoedaController@update')->name('update_moeda');// PUT http://127.0.0.1:8000/api/moedas/id
        Route::delete('/{id}', 'MoedaController@delete')->name('delete_moeda');// DELETE - http://127.0.0.1:8000/api/moedas/id
    });
});

Route::namespace('api')->name('api.')->group(function(){// http://127.0.0.1:8000/api/
    Route::prefix('hoteis')->group(function(){

        Route::get('/', 'HotelController@index')->name('hotel'); //GET - http://127.0.0.1:8000/api/hoteis/
        Route::get('/{id}', 'HotelController@show')->name('show_hotel');//GET - http://127.0.0.1:8000/api/hoteis/id
        Route::post('/', 'HotelController@store')->name('store_hotel');//POST - http://127.0.0.1:8000/api/hoteis/
        Route::put('/{id}', 'MoedaController@update')->name('update_hotel');// PUT http://127.0.0.1:8000/api/hoteis/id
        Route::delete('/{id}', 'HotelController@delete')->name('delete_hotel');// DELETE - http://127.0.0.1:8000/api/hoteis/id

    });
});

Route::namespace('api')->name('api.')->group(function(){// http://127.0.0.1:8000/api/
    Route::prefix('quartos')->group(function(){

        Route::get('/', 'QuartoController@index')->name('hotel'); //GET - http://127.0.0.1:8000/api/quartos/
        Route::get('/{id}', 'QuartoController@show')->name('show_quarto');//GET - http://127.0.0.1:8000/api/quartos/id
        Route::post('/', 'QuartoController@store')->name('store_quarto');//POST - http://127.0.0.1:8000/api/quartos/
        Route::put('/{id}', 'QuartoController@update')->name('update_quarto');// PUT http://127.0.0.1:8000/api/quartos/id
        Route::delete('/{id}', 'QuartoController@delete')->name('delete_quarto');// DELETE - http://127.0.0.1:8000/api/quartos/id
        Route::get('/cambio', 'QuartoController@cambio')->name('cambio');// GET - http://127.0.0.1:8000/api/quartos/cambio
        Route::post('/consulta', 'QuartoController@getValue')->name('consulta');// POST - http://127.0.0.1:8000/api/quartos/consulta


    });
});


Route::namespace('api')->name('api.')->group(function(){// http://127.0.0.1:8000/api/
    Route::prefix('vendedores')->group(function(){

        Route::get('/', 'VendedorController@index')->name('vendedor'); //GET - http://127.0.0.1:8000/api/vendedores/
        Route::get('/{id}', 'VendedorController@show')->name('show_vendedor');//GET - http://127.0.0.1:8000/api/vendedores/id
        Route::post('/', 'VendedorController@store')->name('store_vendedor');//POST - http://127.0.0.1:8000/api/vendedores/
        Route::put('/{id}', 'VendedorController@update')->name('update_vendedor');// PUT http://127.0.0.1:8000/api/vendedores/id
        Route::delete('/{id}', 'VendedorController@delete')->name('delete_vendedor');// DELETE - http://127.0.0.1:8000/api/vendedores/id

    });
});
