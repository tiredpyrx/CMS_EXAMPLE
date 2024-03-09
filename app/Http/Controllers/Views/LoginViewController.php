<?php

namespace App\Http\Controllers\Views;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginViewController extends Controller
{
    public function __invoke()
    {
        return view('admin.auth.login.index');
    }
}
