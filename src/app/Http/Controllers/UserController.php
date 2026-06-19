<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\View\View;

class UserController extends Controller
{
    public function show(string $id): View
    {
        return view('test', ['user' => User::findOrFail($id), 'title' => 'test']);
    }
}
