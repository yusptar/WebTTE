<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;

class Pegawai extends Model implements Authenticatable
{
    protected $table = 'pegawai';

    public function getData($nik = null)
    {
        if ($nik === null) {
            return $this->get(); // Replace 'get()' with your query builder if needed
        }
        return $this->where('nik', $nik)->first();
    }

    public function getAuthIdentifierName()
    {
        return 'nik'; // Replace with the actual column name representing the NIK
    }

    public function getAuthIdentifier()
    {
        return $this->{$this->getAuthIdentifierName()};
    }

    public function getAuthPassword()
    {
        return $this->password; // Replace with the actual password column name
    }

    public function getRememberToken()
    {
        return $this->remember_token; // Replace with the actual remember token column name
    }

    public function setRememberToken($value)
    {
        $this->remember_token = $value;
    }

    public function getRememberTokenName()
    {
        return 'remember_token'; // Replace with the actual remember token column name
    }

    // public function usertte()
    // {
    //     return $this->belongsTo(User::class);
    // }
}