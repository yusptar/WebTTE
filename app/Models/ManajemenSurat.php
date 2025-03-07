<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ManajemenSurat extends Model
{
    use HasFactory;
    protected $table = 'manajemen_rm_tte';
    protected $primaryKey = 'no_rawat';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'no_rawat',
        'tanggal_upload',
        'path',
        'jenis_rm',
        'signed_status',
    ];

    
    public function getDetailFileSurat(){
        $result = DB::table('manajemen_rm_tte')
                ->join('status_tte_ppa', function ($join) {
                $join->on('manajemen_rm_tte.no_rawat', '=', 'status_tte_ppa.no_rawat')
                    // ->where('status_tte_ppa.nip', '=', Auth::user()->pegawai->nik)
                    ->where('manajemen_rm_tte.jenis_rm', 'like', '9%')
                    ->where('status_tte_ppa.jenis_rm', 'like', '9%');
                })
                ->leftJoin('reg_periksa', function ($join) {
                    $join->on('manajemen_rm_tte.no_rawat', '=', 'reg_periksa.no_rawat')
                    ->where('reg_periksa.status_lanjut', '=', 'Ralan');
                })
                ->leftJoin('pasien', function ($join) {
                    $join->on('reg_periksa.no_rkm_medis', '=', 'pasien.no_rkm_medis');
                })
                ->leftJoin('penjab', function ($join) {
                    $join->on('reg_periksa.kd_pj', '=', 'penjab.kd_pj');
                })
                ->leftJoin('poliklinik', function ($join) {
                    $join->on('reg_periksa.kd_poli', '=', 'poliklinik.kd_poli');
                })
                ->leftJoin('master_berkas_digital', function ($join) {
                    $join->on('master_berkas_digital.kode', '=', 'manajemen_rm_tte.jenis_rm');
                })
                ->leftJoin('pegawai', function ($join) {
                    $join->on('status_tte_ppa.nip', '=', 'pegawai.nik');
                })
                ->get();
        // $result = DB::table('manajemen_rm_tte')
        //     ->join('status_tte_ppa', function ($join) {
        //         $join->on('manajemen_rm_tte.no_rawat', '=', 'status_tte_ppa.no_rawat')
        //         ->where('manajemen_rm_tte.jenis_rm', 'like', '9%')
        //         ->where('status_tte_ppa.jenis_rm', 'like', '9%');
        //     })
        //     ->leftJoin('reg_periksa', function ($join) {
        //         $join->on('manajemen_rm_tte.no_rawat', '=', 'reg_periksa.no_rawat')
        //         ->where('reg_periksa.status_lanjut', '=', 'Ralan');
        //     })
        //     ->leftJoin('pasien', function ($join) {
        //         $join->on('reg_periksa.no_rkm_medis', '=', 'pasien.no_rkm_medis');
        //     })
        //     ->leftJoin('penjab', function ($join) {
        //         $join->on('reg_periksa.kd_pj', '=', 'penjab.kd_pj');
        //     })
        //     ->leftJoin('poliklinik', function ($join) {
        //         $join->on('reg_periksa.kd_poli', '=', 'poliklinik.kd_poli');
        //     })
        //     ->leftJoin('master_berkas_digital', function ($join) {
        //         $join->on('master_berkas_digital.kode', '=', 'manajemen_rm_tte.jenis_rm');
        //     })
        //     ->leftJoin('pegawai', function ($join) {
        //         $join->on('status_tte_ppa.nip', '=', 'pegawai.nik');
        //     })
        //     ->groupBy('manajemen_rm_tte.no_rawat')
        //     ->groupBy('manajemen_rm_tte.jenis_rm')
        //     ->groupBy('manajemen_rm_tte.path')
        //     ->groupBy('manajemen_rm_tte.signed_status')
        //     ->groupBy('reg_periksa.no_rawat')
        //     ->groupBy('reg_periksa.no_rkm_medis')
        //     ->groupBy('pasien.nm_pasien')
        //     ->groupBy('reg_periksa.tgl_registrasi')
        //     ->groupBy('poliklinik.nm_poli')
        //     ->groupBy('penjab.png_jawab')
        //     ->groupBy('master_berkas_digital.nama')
        //     ->selectRaw('`reg_periksa`.`no_rawat` as no_rawat')
        //     ->selectRaw('`reg_periksa`.`no_rkm_medis` as no_rkm_medis')
        //     ->selectRaw('`pasien`.`nm_pasien` as nm_pasien')
        //     ->selectRaw('`reg_periksa`.`tgl_registrasi` as tgl_registrasi')
        //     ->selectRaw('`poliklinik`.`nm_poli` as nm_ruang')
        //     ->selectRaw('`penjab`.`png_jawab` as png_jawab')
        //     ->selectRaw('`manajemen_rm_tte`.`path` as path')
        //     ->selectRaw('`manajemen_rm_tte`.`signed_status` as signed_status')
        //     ->selectRaw('`master_berkas_digital`.`nama` as jenis_rm')
        //     ->selectRaw('GROUP_CONCAT(pegawai.nama,\' (\',status_tte_ppa.status, \'); \') as petugas')
        //     ->get();
        return $result;
    }

    public function getDataPasien($fileName){
        $result = DB::table('manajemen_rm_tte')
                ->join('reg_periksa', function ($join) {
                    $join->on('manajemen_rm_tte.no_rawat', '=', 'reg_periksa.no_rawat');
                })
                ->join('pasien', function ($join) {
                    $join->on('pasien.no_rkm_medis', '=', 'reg_periksa.no_rkm_medis');
                })
                ->where('manajemen_rm_tte.path', '=', $fileName)
                ->get();
        return $result; 
    }
}