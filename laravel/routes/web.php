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

use Enqueue\SimpleClient\SimpleClient;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/queue/test', function () {
    $job = new \App\Jobs\EnqueueTest();
    $job->onConnection('interop');

    dispatch($job);

    return 'QueueTest';
});

Route::get('/enqueue/test', function () {
    /** @var SimpleClient $client */
    $client = \App::make(SimpleClient::class);

    $client->sendEvent('enqueue_test', 'The message');

    return 'EnqueueTest';
});