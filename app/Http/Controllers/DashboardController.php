<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $role = $request->user()->role ?? 'wali_murid';

        return match ($role) {
            'admin' => view('dashboard.admin'),
            'guru' => view('dashboard.guru'),
            default => view('dashboard.wali_murid'),
        };
    }
}
