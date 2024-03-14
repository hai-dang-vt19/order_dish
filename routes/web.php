<?php

use App\Http\Controllers\Step3Controller;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});
