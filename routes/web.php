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

Route::get('/cache', function () {
    \Illuminate\Support\Facades\Cache::add('teste', 'teste dsadsa1', config('services.sitemap.cachetime'));
    return \Illuminate\Support\Facades\Cache::get('teste');
});

Route::get('/test', function () {
    $httpClient = new \App\Services\HtmlClient\GuzzleAsyncHttpClient();
    $cache = new \App\Services\Cache\ZipCache();
    $siteMap = new \App\Services\SiteMap\Investire24SiteMap($httpClient, $cache);
    $urls = $siteMap->load();
    dd($siteMap->extract($urls));
});
