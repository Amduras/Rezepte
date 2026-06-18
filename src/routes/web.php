<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/recipe', function() {
    return view('recipe');
});

Route::get('/addrecipe', function(){
    return view('addrecipe');
});
