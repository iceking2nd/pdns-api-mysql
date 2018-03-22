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

$api = app('Dingo\Api\Routing\Router');
$api->version(['v1'],['namespace' => 'App\Http\Controllers','middleware' => ['api']],function($api){
    $api->post('user/login','AuthController@authenticate')->name('user.login');
});
$api->version('v1',['namespace' => 'App\Http\Controllers\V1','middleware' => ['api','jwt.auth']],function($api){
    $api->resource('comments','CommentsController');
    $api->resource('cryptokeys','CryptoKeysController');
    $api->resource('domainmetadata','DomainMetaDataController');
    $api->resource('domains','DomainsController');
    $api->resource('records','RecordsController');
    $api->resource('supermasters','SuperMastersController');
    $api->resource('tsigkeys','TSIGKeysController');
});