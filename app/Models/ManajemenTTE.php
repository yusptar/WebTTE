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
        'tanggal_signed',
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
                    ->selectRaw('`manajemen_rm_tte`.`jenis_rm` as kd_jenis_rm')
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
                    ->selectRaw('`manajemen_rm_tte`.`jenis_rm` as kd_jenis_rm')
                    ->selectRaw('GROUP_CONCAT(pegawai.nama,\' (\',status_tte_ppa.status, \'); \') as petugas')
                // ->toSql();
                ->limit(100)
                    ->get();
                    // dd($result);
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
                    ->selectRaw('`manajemen_rm_tte`.`jenis_rm` as kd_jenis_rm')
                    ->selectRaw('`manajemen_rm_tte`.`tanggal_upload` as tanggal_upload')
                    ->get();
                    
        return $result;
    }

    public function getStatusFileRMAdmin()
    {
        return DB::table('manajemen_rm_tte')
            ->join('status_tte_ppa', function ($join) {
                $join->on('manajemen_rm_tte.no_rawat', '=', 'status_tte_ppa.no_rawat')
                    ->on('manajemen_rm_tte.jenis_rm', '=', 'status_tte_ppa.jenis_rm');
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
            ->selectRaw('`manajemen_rm_tte`.`jenis_rm` as kd_jenis_rm')
            ->selectRaw('`master_berkas_digital`.`nama` as jenis_rm');
    }


    public function getPasienRalan(){
        $result = DB::table('reg_periksa')
                    ->join('pasien', function ($join) {
                        $join->on('reg_periksa.no_rkm_medis', '=', 'pasien.no_rkm_medis');
                    })
                    ->join('poliklinik', function ($join) {
                        $join->on('reg_periksa.kd_poli', '=', 'poliklinik.kd_poli');
                    })
                    ->join('bridging_sep', function ($join) {
                        $join->on('reg_periksa.no_rawat', '=', 'bridging_sep.no_rawat');
                    })
                    // ->join('verifikasi_berkas_klaim_bpjs', function ($join) {
                    //     $join->on('reg_periksa.no_rawat', '=', 'verifikasi_berkas_klaim_bpjs.no_rawat');
                    // })
                    ->selectRaw('`reg_periksa`.`no_rawat` as no_rawat')
                    ->selectRaw('`reg_periksa`.`no_rkm_medis` as no_rkm_medis')
                    ->selectRaw('`pasien`.`nm_pasien` as nm_pasien')
                    ->selectRaw('`reg_periksa`.`tgl_registrasi` as tanggal')
                    ->selectRaw('`poliklinik`.`nm_poli` as nm_ruang')
                    ->selectRaw('`bridging_sep`.`no_sep` as no_sep')
                    // ->selectRaw('`verifikasi_berkas_klaim_bpjs`.`status_akhir` as status_akhir')
                    ->where('reg_periksa.status_lanjut', '=', 'Ralan')
                    ->get();
        return $result;
    }
    
    public function getPasienRanap(){
        $result = DB::table('reg_periksa')
                    ->join('pasien', function ($join) {
                        $join->on('reg_periksa.no_rkm_medis', '=', 'pasien.no_rkm_medis');
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
                    ->join('bridging_sep', function ($join) {
                        $join->on('reg_periksa.no_rawat', '=', 'bridging_sep.no_rawat');
                    })
                    // ->join('verifikasi_berkas_klaim_bpjs', function ($join) {
                    //     $join->on('reg_periksa.no_rawat', '=', 'verifikasi_berkas_klaim_bpjs.no_rawat');
                    // })
                    ->selectRaw('`reg_periksa`.`no_rawat` as no_rawat')
                    ->selectRaw('`reg_periksa`.`no_rkm_medis` as no_rkm_medis')
                    ->selectRaw('`pasien`.`nm_pasien` as nm_pasien')
                    ->selectRaw('`kamar_inap`.`tgl_keluar` as tanggal')
                    ->selectRaw('`bangsal`.`nm_bangsal` as nm_ruang')
                    ->selectRaw('`bridging_sep`.`no_sep` as no_sep')
                    // ->selectRaw('`verifikasi_berkas_klaim_bpjs`.`status_akhir` as status_akhir')
                    ->where('kamar_inap.tgl_keluar', '<>', '0000:00:00')
                    ->where('bridging_sep.jnspelayanan', '=', '1')
                    ->get();
        return $result;
    }
    
        
    public function getDetailRM($no_rawat,$jnsPelayanan){
        $result = DB::table('reg_periksa')
                    ->join('pasien', function ($join) {
                        $join->on('reg_periksa.no_rkm_medis', '=', 'pasien.no_rkm_medis');
                    })
                    ->join('bridging_sep', function ($join) {
                        $join->on('reg_periksa.no_rawat', '=', 'bridging_sep.no_rawat');
                    })
                    ->join('manajemen_rm_tte', function ($join) {
                        $join->on('reg_periksa.no_rawat', '=', 'manajemen_rm_tte.no_rawat');
                    })
                    ->leftjoin('verifikasi_berkas_klaim_bpjs', function ($join) {
                        $join->on('reg_periksa.no_rawat', '=', 'verifikasi_berkas_klaim_bpjs.no_rawat');
                    })
                    ->selectRaw('pasien.nm_pasien')
                    ->selectRaw('reg_periksa.no_rawat')
                    ->selectRaw('reg_periksa.no_rkm_medis')
                    ->selectRaw('bridging_sep.no_sep')
                    ->selectRaw('verifikasi_berkas_klaim_bpjs.status_akhir')
                    ->selectRaw('verifikasi_berkas_klaim_bpjs.status_awal_medis_igd')
                    ->selectRaw('verifikasi_berkas_klaim_bpjs.ket_awal_medis_igd')
                    ->selectRaw('verifikasi_berkas_klaim_bpjs.status_awal_kep_igd')
                    ->selectRaw('verifikasi_berkas_klaim_bpjs.ket_awal_kep_igd')
                    ->selectRaw('verifikasi_berkas_klaim_bpjs.status_awal_medis')
                    ->selectRaw('verifikasi_berkas_klaim_bpjs.ket_awal_medis')
                    ->selectRaw('verifikasi_berkas_klaim_bpjs.status_awal_kep')
                    ->selectRaw('verifikasi_berkas_klaim_bpjs.ket_awal_kep')
                    ->selectRaw('verifikasi_berkas_klaim_bpjs.status_resume_medis')
                    ->selectRaw('verifikasi_berkas_klaim_bpjs.ket_resume_medis')
                    ->selectRaw('verifikasi_berkas_klaim_bpjs.status_laporan_operasi')
                    ->selectRaw('verifikasi_berkas_klaim_bpjs.ket_laporan_operasi')
                    ->selectRaw('verifikasi_berkas_klaim_bpjs.status_hasil_lab')
                    ->selectRaw('verifikasi_berkas_klaim_bpjs.ket_hasil_lab')
                    ->selectRaw('verifikasi_berkas_klaim_bpjs.status_hasil_rad')
                    ->selectRaw('verifikasi_berkas_klaim_bpjs.ket_hasil_rad')
                    ->selectRaw('verifikasi_berkas_klaim_bpjs.status_cppt')
                    ->selectRaw('verifikasi_berkas_klaim_bpjs.ket_cppt')
                    ->selectRaw('IFNULL(GROUP_CONCAT((CASE WHEN manajemen_rm_tte.jenis_rm = \'026\' THEN manajemen_rm_tte.signed_status ELSE NULL END) SEPARATOR \'\'),\'-\') btn_awal_medis_igd')
                    ->selectRaw('IFNULL(GROUP_CONCAT((CASE WHEN manajemen_rm_tte.jenis_rm = \'019\' THEN manajemen_rm_tte.signed_status ELSE NULL END) SEPARATOR \'\'),\'-\') btn_awal_kep_igd')
                    ->selectRaw('IFNULL(GROUP_CONCAT((CASE WHEN manajemen_rm_tte.jenis_rm = \'006\' THEN manajemen_rm_tte.signed_status ELSE NULL END) SEPARATOR \'\'),\'-\') btn_awal_medis')
                    ->selectRaw('IFNULL(GROUP_CONCAT((CASE WHEN manajemen_rm_tte.jenis_rm = \'020\' THEN manajemen_rm_tte.signed_status ELSE NULL END) SEPARATOR \'\'),\'-\') btn_awal_kep')
                    ->selectRaw('IFNULL(GROUP_CONCAT((CASE WHEN manajemen_rm_tte.jenis_rm = \'017\' THEN manajemen_rm_tte.signed_status ELSE NULL END) SEPARATOR \'\'),\'-\') btn_resume_medis')
                    ->selectRaw('IFNULL(GROUP_CONCAT((CASE WHEN manajemen_rm_tte.jenis_rm = \'008\' THEN manajemen_rm_tte.signed_status ELSE NULL END) SEPARATOR \'\'),\'-\') btn_laporan_operasi')
                    ->selectRaw('IFNULL(GROUP_CONCAT((CASE WHEN manajemen_rm_tte.jenis_rm = \'012\' THEN manajemen_rm_tte.signed_status ELSE NULL END) SEPARATOR \'\'),\'-\') btn_hasil_lab')
                    ->selectRaw('IFNULL(GROUP_CONCAT((CASE WHEN manajemen_rm_tte.jenis_rm = \'013\' THEN manajemen_rm_tte.signed_status ELSE NULL END) SEPARATOR \'\'),\'-\') btn_hasil_rad')
                    ->selectRaw('IFNULL(GROUP_CONCAT((CASE WHEN manajemen_rm_tte.jenis_rm = \'022\' THEN manajemen_rm_tte.signed_status ELSE NULL END) SEPARATOR \'\'),\'-\') btn_cppt')
                    ->where('reg_periksa.no_rawat', '=', $no_rawat)
                    ->where('bridging_sep.jnspelayanan', '=', $jnsPelayanan)
                    ->groupBy('reg_periksa.no_rawat')
                    ->get()->first();
        return $result;
    }

    public function getJenisPelayanan($noRawat){
        $result = DB::table('reg_periksa')
                ->selectRaw('status_lanjut')
                ->where('no_rawat', '=', $noRawat)
                ->get()->first();
        return $result;
    }

    public function getFileName($noRawat, $kodeJenisRM){
        $result = ManajemenTTE::where('no_rawat', '=', $noRawat)->where('jenis_rm', '=', $kodeJenisRM)->select('path')->get()->first();
        
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