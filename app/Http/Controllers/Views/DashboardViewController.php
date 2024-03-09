<?php

namespace App\Http\Controllers\Views;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardViewController extends Controller
{
    public function __invoke()
    {
        return view('admin.pages.dashboard.index');
    }
}
