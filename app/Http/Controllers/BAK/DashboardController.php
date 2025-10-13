<?php

namespace App\Http\Controllers\BAK;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view('bak.dashboard.index');
    }
}
