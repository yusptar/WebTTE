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
        'status',
        'jenis_rm'
    ];

    public function countStatusBelum($no_rawat,$jenis_rm){
        $result = StatusTTEPPA::where('no_rawat', '=', $no_rawat)->where('jenis_rm', '=', $jenis_rm)->where('status','BELUM')->count();
        
        return $result;
    }

    
}