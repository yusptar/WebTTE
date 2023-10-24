<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TTELog extends Model
{
    use HasFactory;
    protected $table = 'log_tte';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'user',
        'created_at',
        'message',
    ];
}