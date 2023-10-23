<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BerkasDigital extends Model
{
    use HasFactory;
    protected $table = 'berkas_digital_perawatan';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'no_rawat',
        'kode',
        'lokasi_file',
    ];
}