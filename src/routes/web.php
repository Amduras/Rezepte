<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/recipe', function () {
    return view('recipe', ['title' => 'Rezept']);
});

Route::get('/addrecipe', function () {
    return view('addrecipe', ['title' => '✏️ Rezept hinzufügen']);
});
