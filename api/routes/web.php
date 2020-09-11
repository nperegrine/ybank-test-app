<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

/**
 * yBank API ROUTE definitions
 */
Route::prefix('api')->group(function() {
    Route::get('accounts/{id}', 'AccountController@getAccount');
    Route::get('accounts/{id}/transactions', 'AccountController@getAccountTransactions');
    Route::post('accounts/{id}/transactions', 'TransactionController@createTransaction');
    Route::get('currencies', 'CurrencyController@getCurrencies');
});