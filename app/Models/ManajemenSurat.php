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
                    ->where('status_tte_ppa.nip', '=', Auth::user()->pegawai->nik)
                    ->where('status_tte_ppa.jenis_rm', '=', '999');
                })
                ->get();
        return $result;
    }

}