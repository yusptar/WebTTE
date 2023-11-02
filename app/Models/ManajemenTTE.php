<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManajemenTTE extends Model
{
    use HasFactory;
    protected $table = 'manajemen_rm_tte';
    protected $primaryKey = 'no_rawat';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'no_rawat',
        'jenis_rm',
        'tanggal_upload',
        'path',
        'signed_status',
    ];
}