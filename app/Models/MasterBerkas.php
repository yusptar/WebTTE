<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterBerkas extends Model
{
    use HasFactory;
    protected $table = 'master_berkas_digital';
    protected $primaryKey = 'kode';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'kode',
        'nama',
    ];
}