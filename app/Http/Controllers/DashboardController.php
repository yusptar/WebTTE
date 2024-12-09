<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pegawai; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class DashboardController extends Controller
{
    
    public function index()
    {
        $jumlah_tte = DB::table('pegawai as p')
            ->join('status_tte_ppa as s', 'p.nik', '=', 's.nip')
            ->join('master_berkas_digital as m', 's.jenis_rm', '=', 'm.kode')
            ->join('manajemen_rm_tte as r', 'r.no_rawat', '=', 's.no_rawat')
            ->select(
                DB::raw('SUM(CASE WHEN s.status = "BELUM" THEN 1 ELSE 0 END) AS belum'),
                DB::raw('SUM(CASE WHEN s.status = "SUDAH" THEN 1 ELSE 0 END) AS sudah')
            )
            ->where('p.nama', Auth::user()->name)
            ->first();

        return view('dashboard.index', compact('jumlah_tte'));
    }

    public function __construct()
    {
        $this->middleware('auth');
    }
}