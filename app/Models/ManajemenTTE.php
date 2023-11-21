<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ManajemenTTE extends Model
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
        'signed_status',
    ];

    public function getStatusFileRM(){
        $result = DB::table('manajemen_rm_tte')
                    ->join('status_tte_ppa', function ($join) {
                        $join->on('manajemen_rm_tte.no_rawat', '=', 'status_tte_ppa.no_rawat')
                            ->where('status_tte_ppa.nip', '=', Auth::user()->pegawai->nik);
                    })
                    ->join('reg_periksa', function ($join) {
                        $join->on('manajemen_rm_tte.no_rawat', '=', 'reg_periksa.no_rawat');
                    })
                    ->join('pasien', function ($join) {
                        $join->on('reg_periksa.no_rkm_medis', '=', 'pasien.no_rkm_medis');
                    })
                    ->join('penjab', function ($join) {
                        $join->on('reg_periksa.kd_pj', '=', 'penjab.kd_pj');
                    })
                    ->join('poliklinik', function ($join) {
                        $join->on('reg_periksa.kd_poli', '=', 'poliklinik.kd_poli');
                    })
                    ->selectRaw('`status_tte_ppa`.`no_rawat` as no_rawat')
                    ->selectRaw('`reg_periksa`.`no_rkm_medis` as no_rkm_medis')
                    ->selectRaw('`pasien`.`nm_pasien` as nm_pasien')
                    ->selectRaw('`reg_periksa`.`tgl_registrasi` as tgl_registrasi')
                    ->selectRaw('`poliklinik`.`nm_poli` as nm_poli')
                    ->selectRaw('`penjab`.`png_jawab` as png_jawab')
                    ->selectRaw('`status_tte_ppa`.`status` as status')
                    ->selectRaw('`manajemen_rm_tte`.`path` as path')
                    ->get();
        return $result;
    }

    public function getDetailRM(){
        $result = DB::table('manajemen_rm_tte')
                    ->join('reg_periksa', function ($join) {
                        $join->on('manajemen_rm_tte.no_rawat', '=', 'reg_periksa.no_rawat');
                    })
                    ->join('pasien', function ($join) {
                        $join->on('reg_periksa.no_rkm_medis', '=', 'pasien.no_rkm_medis');
                    })
                    ->join('penjab', function ($join) {
                        $join->on('reg_periksa.kd_pj', '=', 'penjab.kd_pj');
                    })
                    ->join('poliklinik', function ($join) {
                        $join->on('reg_periksa.kd_poli', '=', 'poliklinik.kd_poli');
                    })
                    ->get();
        return $result;
    }
}