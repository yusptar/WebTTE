<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $table = 'users_tte';
    protected $primaryKey = 'id';
    public $timestamps = false;
    

    protected $fillable = [
        'username',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'username', 'nik');
    }
    

    public function getUserSIMRS(){
        $result = DB::table('users_tte')
                    ->join('pegawai', function ($join) {
                        $join->on('users_tte.username', '=', 'pegawai.nik');
                    })
                    ->selectRaw('`users_tte`.`id` as id')
                    ->selectRaw('`users_tte`.`username` as username')
                    ->selectRaw('`users_tte`.`role` as role')
                    ->selectRaw('`pegawai`.`nama` as nama')
                    ->selectRaw('`pegawai`.`no_ktp` as no_ktp')
                    ->get();
        return $result;
    }
}