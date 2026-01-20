<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});

Route::get('/naskah', function () {
    return view('naskahKuno');
});

Route::get('/statistik', function () {
    return view('statistik');
});
