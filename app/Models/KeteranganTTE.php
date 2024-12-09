<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KeteranganTTE extends Model
{
    use HasFactory;
    protected $table = 'keterangan_tte_ppa';
    protected $primaryKey = 'id';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'no_rawat',
        'jenis_rm',
        'nip',
        'nm_pegawai',
        'nm_rm',
        'tgl_signed',
    ];
}