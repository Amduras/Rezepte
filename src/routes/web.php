<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/recipe', function () {
    return view('recipe', ['title' => 'Rezept']);
});

Route::get('/addrecipe', function () {
    return view('addrecipe', ['title' => '✏️ Rezept hinzufügen']);
});

Route::get('/test/{id}', [UserController::class, 'show']);
