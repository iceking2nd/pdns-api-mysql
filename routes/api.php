<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'v1'],function (){
    Route::apiResource('comments','V1\CommentsController');
    Route::apiResource('cryptokeys','V1\CryptoKeysController');
    Route::apiResource('domainmetadata','V1\DomainMetaDataController');
    Route::apiResource('domains','V1\DomainsController');
    Route::apiResource('records','V1\RecordsController');
    Route::apiResource('supermasters','V1\SuperMastersController');
    Route::apiResource('tsigkeys','V1\TSIGKeysController');
});