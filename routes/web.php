<?php

use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/test', function () {
    DB::listen(function ($e) {
        dump($e->toRawSql());
    });

    DB::table('users')->where('id', 1)->first();
});
