<?php

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

Route::get('/test', function () {
    $baseUrl = \App\Services\SiteMap\Investire24SiteMap::getBaseUrl();
    $httpClient = new \App\Services\HtmlClient\GuzzleAsyncHttpClient($baseUrl);
    $siteMap = new \App\Services\SiteMap\Investire24SiteMap($httpClient);
    $urls = $siteMap->load();
    dd($siteMap->extract($urls));
});
