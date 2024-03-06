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
        'jenis_rm',
        'signed_status',
    ];


    public function getDetailRMRanap(){
        $result = DB::table('manajemen_rm_tte')
                    ->join('reg_periksa', function ($join) {
                        $join->on('manajemen_rm_tte.no_rawat', '=', 'reg_periksa.no_rawat')
                        ->where('reg_periksa.status_lanjut', '=', 'Ranap');
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
                    ->join('kamar_inap', function ($join) {
                        $join->on('reg_periksa.no_rawat', '=', 'kamar_inap.no_rawat');
                    })
                    ->join('kamar', function ($join) {
                        $join->on('kamar_inap.kd_kamar', '=', 'kamar.kd_kamar');
                    })
                    ->join('bangsal', function ($join) {
                        $join->on('kamar.kd_bangsal', '=', 'bangsal.kd_bangsal');
                    })
                    ->join('master_berkas_digital', function ($join) {
                        $join->on('master_berkas_digital.kode', '=', 'manajemen_rm_tte.jenis_rm');
                    })
                    ->join('status_tte_ppa', function ($join) {
                        $join->on('manajemen_rm_tte.no_rawat', '=', 'status_tte_ppa.no_rawat');
                        $join->on('manajemen_rm_tte.jenis_rm', '=', 'status_tte_ppa.jenis_rm');
                    })
                    ->join('pegawai', function ($join) {
                        $join->on('status_tte_ppa.nip', '=', 'pegawai.nik');
                    })
                    ->groupBy('manajemen_rm_tte.no_rawat')
                    ->groupBy('manajemen_rm_tte.jenis_rm')
                    ->groupBy('bangsal.nm_bangsal')
                    ->groupBy('manajemen_rm_tte.path')
                    ->groupBy('manajemen_rm_tte.signed_status')
                    ->groupBy('reg_periksa.no_rawat')
                    ->groupBy('reg_periksa.no_rkm_medis')
                    ->groupBy('pasien.nm_pasien')
                    ->groupBy('reg_periksa.tgl_registrasi')
                    ->groupBy('poliklinik.nm_poli')
                    ->groupBy('penjab.png_jawab')
                    ->groupBy('master_berkas_digital.nama')
                    ->selectRaw('`reg_periksa`.`no_rawat` as no_rawat')
                    ->selectRaw('`reg_periksa`.`no_rkm_medis` as no_rkm_medis')
                    ->selectRaw('`pasien`.`nm_pasien` as nm_pasien')
                    ->selectRaw('`reg_periksa`.`tgl_registrasi` as tgl_registrasi')
                    ->selectRaw('`bangsal`.`nm_bangsal` as nm_ruang')
                    ->selectRaw('`penjab`.`png_jawab` as png_jawab')
                    ->selectRaw('`manajemen_rm_tte`.`path` as path')
                    ->selectRaw('`manajemen_rm_tte`.`signed_status` as signed_status')
                    ->selectRaw('`master_berkas_digital`.`nama` as jenis_rm')
                    ->selectRaw('GROUP_CONCAT(pegawai.nama,\' (\',status_tte_ppa.status, \'); \') as petugas')
                    ->get();
        return $result;
    }

    public function getDetailRMRalan(){
        $result = DB::table('manajemen_rm_tte')
                    ->join('reg_periksa', function ($join) {
                        $join->on('manajemen_rm_tte.no_rawat', '=', 'reg_periksa.no_rawat')
                        ->where('reg_periksa.status_lanjut', '=', 'Ralan');
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
                    ->join('master_berkas_digital', function ($join) {
                        $join->on('master_berkas_digital.kode', '=', 'manajemen_rm_tte.jenis_rm');
                    })
                    ->join('status_tte_ppa', function ($join) {
                        $join->on('manajemen_rm_tte.no_rawat', '=', 'status_tte_ppa.no_rawat');
                        $join->on('manajemen_rm_tte.jenis_rm', '=', 'status_tte_ppa.jenis_rm');
                    })
                    ->join('pegawai', function ($join) {
                        $join->on('status_tte_ppa.nip', '=', 'pegawai.nik');
                    })
                    ->groupBy('manajemen_rm_tte.no_rawat')
                    ->groupBy('manajemen_rm_tte.jenis_rm')
                    ->groupBy('manajemen_rm_tte.path')
                    ->groupBy('manajemen_rm_tte.signed_status')
                    ->groupBy('reg_periksa.no_rawat')
                    ->groupBy('reg_periksa.no_rkm_medis')
                    ->groupBy('pasien.nm_pasien')
                    ->groupBy('reg_periksa.tgl_registrasi')
                    ->groupBy('poliklinik.nm_poli')
                    ->groupBy('penjab.png_jawab')
                    ->groupBy('master_berkas_digital.nama')
                    ->selectRaw('`reg_periksa`.`no_rawat` as no_rawat')
                    ->selectRaw('`reg_periksa`.`no_rkm_medis` as no_rkm_medis')
                    ->selectRaw('`pasien`.`nm_pasien` as nm_pasien')
                    ->selectRaw('`reg_periksa`.`tgl_registrasi` as tgl_registrasi')
                    ->selectRaw('`poliklinik`.`nm_poli` as nm_ruang')
                    ->selectRaw('`penjab`.`png_jawab` as png_jawab')
                    ->selectRaw('`manajemen_rm_tte`.`path` as path')
                    ->selectRaw('`manajemen_rm_tte`.`signed_status` as signed_status')
                    ->selectRaw('`master_berkas_digital`.`nama` as jenis_rm')
                    ->selectRaw('GROUP_CONCAT(pegawai.nama,\' (\',status_tte_ppa.status, \'); \') as petugas')
                    ->get();
        return $result;
    }

    public function getStatusFileRM(){
        $result = DB::table('manajemen_rm_tte')
                    ->join('status_tte_ppa', function ($join) {
                        $join->on('manajemen_rm_tte.no_rawat', '=', 'status_tte_ppa.no_rawat')
                            ->on('manajemen_rm_tte.jenis_rm', '=', 'status_tte_ppa.jenis_rm')
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
                    ->join('master_berkas_digital', function ($join) {
                        $join->on('manajemen_rm_tte.jenis_rm', '=', 'master_berkas_digital.kode');
                    })
                    ->selectRaw('`reg_periksa`.`no_rawat` as no_rawat')
                    ->selectRaw('`manajemen_rm_tte`.`path` as path')
                    ->selectRaw('`reg_periksa`.`no_rkm_medis` as no_rkm_medis')
                    ->selectRaw('`pasien`.`nm_pasien` as nm_pasien')
                    ->selectRaw('`master_berkas_digital`.`nama` as jenis_rm')
                    ->selectRaw('`reg_periksa`.`tgl_registrasi` as tgl_registrasi')
                    ->selectRaw('`poliklinik`.`nm_poli` as nm_ruang')
                    ->selectRaw('`penjab`.`png_jawab` as png_jawab')
                    ->selectRaw('`manajemen_rm_tte`.`signed_status` as signed_status')
                    ->selectRaw('`status_tte_ppa`.`status` as status_ppa')
                    ->selectRaw('`master_berkas_digital`.`nama` as jenis_rm')
                    ->get();
                    
        return $result;
    }

    
    
    public function getKamar($no_rawat){
        if(DB::table('kamar_inap')->where('no_rawat', '=', $no_rawat)->exists()){
            $result = DB::table('kamar_inap')
                ->join('kamar', function ($join) {
                    $join->on('kamar_inap.kd_kamar', '=', 'kamar.kd_kamar');
                })
                ->join('bangsal', function ($join) {
                    $join->on('kamar.kd_bangsal', '=', 'bangsal.kd_bangsal');
                })
                ->selectRaw('bangsal.nm_bangsal as nm_ruang')
                ->where('kamar_inap.no_rawat', '=', $no_rawat)
                ->first();
        }else{
            $result = DB::table('reg_periksa')
                ->join('poliklinik', function ($join) {
                    $join->on('poliklinik.kd_poli', '=', 'reg_periksa.kd_poli');
                })
                ->selectRaw('poliklinik.nm_poli as nm_ruang')
                ->where('reg_periksa.no_rawat', '=', $no_rawat)
                ->first();
        }

        return $result;
    }

    public function statustteppa()
    {
        return $this->belongsTo(StatusTTEPPA::class, 'no_rawat', 'no_rawat');
    }
    
    public function countStatusSudah($no_rawat,$jenis_rm){
        $result = ManajemenTTE::where('no_rawat', '=', $no_rawat)->where('jenis_rm', '=', $jenis_rm)->where('signed_status','SUDAH')->count();
        
        return $result;
    }
}