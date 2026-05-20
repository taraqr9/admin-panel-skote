<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    public function index()
    {
        $page_title = 'Dashboard';

        return view('dashboard', compact('page_title'));
    }
}
