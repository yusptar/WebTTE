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
        'tanggal_signed',
        'path',
        'jenis_rm',
        'signed_status',
    ];

    
    public function getDetailFileSurat(){
        $result = DB::table('manajemen_rm_tte')
                ->join('status_tte_ppa', function ($join) {
                $join->on('manajemen_rm_tte.no_rawat', '=', 'status_tte_ppa.no_rawat')
                    // ->where('status_tte_ppa.nip', '=', Auth::user()->pegawai->nik)
                    ->where('manajemen_rm_tte.jenis_rm', 'like', '999')
                    ->where('status_tte_ppa.jenis_rm', 'like', '999');
                })
                ->leftJoin('pegawai', function ($join) {
                    $join->on('status_tte_ppa.nip', '=', 'pegawai.nik');
                })
                // ->toSql();
                ->get();
        // dd($result);
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