<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pegawai; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class DashboardController extends Controller
{
    // public function index()
    // {
    //     $jumlah_tte = DB::table('pegawai as p')
    //         ->join('status_tte_ppa as s', 'p.nik', '=', 's.nip')
    //         ->join('master_berkas_digital as m', 's.jenis_rm', '=', 'm.kode')
    //         ->join('manajemen_rm_tte as r', 'r.no_rawat', '=', 's.no_rawat')
    //         ->select(
    //             'p.nama', 
    //             's.nip', 
    //             DB::raw('SUM(CASE WHEN s.status = "BELUM" THEN 1 ELSE 0 END) AS belum'),
    //             DB::raw('SUM(CASE WHEN s.status = "SUDAH" THEN 1 ELSE 0 END) AS sudah')
    //         )
    //         ->where('p.nama', Auth::user()->pegawai->nama) 
    //         ->first();

    //     return view('dashboard.index', compact('jumlah_tte'));
    // }

    public function index()
    {
        $user_name = Auth::user()->pegawai->nama;
        $years = [2025, 2024]; // Tahun terbaru diletakkan pertama
        $tte_data = [];
        
        foreach ($years as $year) {
            $tte_per_month = DB::table('status_tte_ppa as s')
                ->join('pegawai as p', 's.nip', '=', 'p.nik')
                ->join('manajemen_rm_tte as r', 'r.no_rawat', '=', 's.no_rawat')
                ->select(
                    DB::raw('MONTH(r.tanggal_upload) as month'),
                    DB::raw('SUM(CASE WHEN s.status = "BELUM" THEN 1 ELSE 0 END) as belum'),
                    DB::raw('SUM(CASE WHEN s.status = "SUDAH" THEN 1 ELSE 0 END) as sudah')
                )
                ->where('p.nama', $user_name)
                ->whereYear('r.tanggal_upload', $year)
                ->groupBy(DB::raw('MONTH(r.tanggal_upload)'))
                ->orderBy(DB::raw('MONTH(r.tanggal_upload)'))
                ->get();
        
            $months = [];
            $belum = [];
            $sudah = [];
            foreach ($tte_per_month as $data) {
                $months[] = Carbon::create()->month($data->month)->format('F');
                $belum[] = $data->belum;
                $sudah[] = $data->sudah;
            }
        
            $tte_data[$year] = [
                'months' => $months,
                'belum' => $belum,
                'sudah' => $sudah,
            ];
        }
        
        $jumlah_tte = DB::table('pegawai as p')
            ->join('status_tte_ppa as s', 'p.nik', '=', 's.nip')
            ->join('master_berkas_digital as m', 's.jenis_rm', '=', 'm.kode')
            ->join('manajemen_rm_tte as r', 'r.no_rawat', '=', 's.no_rawat')
            ->select(
                DB::raw('GROUP_CONCAT(m.nama SEPARATOR ", ") AS dokumen'),
                DB::raw('SUM(CASE WHEN s.status = "BELUM" THEN 1 ELSE 0 END) as belum'),
                DB::raw('SUM(CASE WHEN s.status = "SUDAH" THEN 1 ELSE 0 END) as sudah')
            )
            ->where('p.nama', $user_name)
            ->first();

        $jumlah_dokumen_sudah = DB::table('pegawai as p')
            ->join('status_tte_ppa as s', 'p.nik', '=', 's.nip')
            ->join('master_berkas_digital as m', 's.jenis_rm', '=', 'm.kode')
            ->join('manajemen_rm_tte as r', 'r.no_rawat', '=', 's.no_rawat')
            ->select(
                DB::raw('GROUP_CONCAT(m.nama SEPARATOR ", ") AS dokumen'),
            )
            ->where('p.nama', $user_name)
            ->where('s.status', 'SUDAH')
            ->first();

        $jumlah_dokumen_belum = DB::table('pegawai as p')
            ->join('status_tte_ppa as s', 'p.nik', '=', 's.nip')
            ->join('master_berkas_digital as m', 's.jenis_rm', '=', 'm.kode')
            ->join('manajemen_rm_tte as r', 'r.no_rawat', '=', 's.no_rawat')
            ->select(
                DB::raw('GROUP_CONCAT(m.nama SEPARATOR ", ") AS dokumen'),
            )
            ->where('p.nama', $user_name)
            ->where('s.status', 'BELUM')
            ->first();

        $jumlah_dokumen_sudah->dokumen_list = explode(', ', $jumlah_dokumen_sudah->dokumen);
        $jumlah_dokumen_belum->dokumen_list = explode(', ', $jumlah_dokumen_belum->dokumen);
        $jumlah_dokumen_sudah->dokumen_count = array_count_values($jumlah_dokumen_sudah->dokumen_list);
        $jumlah_dokumen_belum->dokumen_count = array_count_values($jumlah_dokumen_belum->dokumen_list);

        return view('dashboard.index', compact('tte_data', 'jumlah_dokumen_belum', 'jumlah_dokumen_sudah', 'jumlah_tte'));
    }



    public function __construct()
    {
        $this->middleware('auth');
    }
}