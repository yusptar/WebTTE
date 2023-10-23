<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    protected $table = 'pegawai';
    // protected $allowedFields = ['fullname', 'username', 'password', 'id_rs'];
    protected $primaryKey = 'id';
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';

    public function getData($nik = false)
    {
        if ($nik == false) {
            return $this->findAll();
        }
        return $this->where(['nik' => $nik])->first();
    }

    public function getJabatan($nip)
    {
        $_sql = "SELECT LEFT(nm_jbtn,12) AS jns, nm_jbtn FROM jabatan WHERE kd_jbtn = (SELECT kd_jbtn FROM petugas WHERE nip = '$nip')  AND nm_jbtn LIKE 'Perawat%'";
        $query = $this->db->query($_sql)->getResult();
        // dd($query);
        return $query;
    }

    public function getDataUserRS()
    {
        $query =  $this->db->table('users')
            ->select('users.id')
            ->select('users.username')
            ->select('users.fullname')
            ->select('users.id_rs')
            ->select('users.password')
            ->select('rumahsakit.nama')
            ->select('rumahsakit.kota')
            ->join('rumahsakit', 'users.id_rs = rumahsakit.id')
            ->get();
        return $query;
    }

    public function checkLogin($uname, $pwd)
    {
        $_sql = $uname == "rst" ? "select * from admin where usere=AES_ENCRYPT('$uname','nur') and passworde=AES_ENCRYPT('$pwd','windi')" : "select * from user where id_user=AES_ENCRYPT('$uname','nur') and password=AES_ENCRYPT('$pwd','windi')";
        $query = $this->db->query($_sql)->getResult();
        return $query;
    }
}