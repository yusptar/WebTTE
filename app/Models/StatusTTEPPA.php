<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusTTEPPA extends Model
{
    use HasFactory;
    protected $table = 'status_tte_ppa';
    protected $primaryKey = 'no_rawat';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'no_rawat',
        'nip',
        'tgl_upload',
        'tgl_signed',
        'status',
        'jenis_rm'
    ];

    public function countStatusBelum($no_rawat,$jenis_rm,$tgl_upload){
        $result = StatusTTEPPA::where('no_rawat', '=', $no_rawat)->where('jenis_rm', '=', $jenis_rm)->where('tgl_upload', '=', $tgl_upload)->where('status','BELUM')->count();
        
        return $result;
    }

    
}