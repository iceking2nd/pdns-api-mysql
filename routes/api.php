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
    $api->get('comments/all',[ 'uses' => 'CommentsController@list']);
    $api->get('comments/by/{method}/{data}',[ 'uses' => 'CommentsController@GetByMethod']);
    $api->resource('comments','CommentsController');
    $api->get('cryptokeys/all',[ 'uses' => 'CryptoKeysController@list']);
    $api->get('cryptokeys/by/{method}/{data}',[ 'uses' => 'CryptoKeysController@GetByMethod']);
    $api->resource('cryptokeys','CryptoKeysController');
    $api->get('domainmetadata/all',[ 'uses' => 'DomainMetaDataController@list']);
    $api->get('domainmetadata/by/{method}/{data}',[ 'uses' => 'DomainMetaDataController@GetByMethod']);
    $api->resource('domainmetadata','DomainMetaDataController');
    $api->get('domains/all',[ 'uses' => 'DomainsController@list']);
    $api->get('domains/by/{method}/{data}',[ 'uses' => 'DomainsController@GetByMethod']);
    $api->resource('domains','DomainsController');
    $api->get('records/all',[ 'uses' => 'RecordsController@list']);
    $api->get('records/by/{method}/{data}',[ 'uses' => 'RecordsController@GetByMethod']);
    $api->resource('records','RecordsController');
    $api->get('supermasters/all',[ 'uses' => 'SuperMastersController@list']);
    $api->get('supermasters/by/{method}/{data}',[ 'uses' => 'SuperMastersController@GetByMethod']);
    $api->resource('supermasters','SuperMastersController');
    $api->get('tsigkeys/all',[ 'uses' => 'TSIGKeysController@list']);
    $api->get('tsigkeys/by/{method}/{data}',[ 'uses' => 'TSIGKeysController@GetByMethod']);
    $api->resource('tsigkeys','TSIGKeysController');
});