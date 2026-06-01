<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    public function index()
    {
        abort_unless(auth()->user()->can('dashboard-view'), 403);

        $page_title = 'Dashboard';

        return view('dashboard', compact('page_title'));
    }
}
