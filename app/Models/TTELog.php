<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TTELog extends Model
{
    use HasFactory;
    protected $table = 'log_tte';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'user',
        'created_at',
        'message',
    ];
}