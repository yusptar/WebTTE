<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pegawai; 


class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard.index');
    }

    public function __construct()
    {
        $this->middleware('auth');
    }
}