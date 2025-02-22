<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    public function index()
    {
        DB::listen(function ($e) {
            dump($e->toRawSql());
        });

        DB::table('users')->where('id', 1)->first();
    }
}
