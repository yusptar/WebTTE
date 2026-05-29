<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pegawai; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Hashids\Hashids;
use \App\Models\KeteranganTTE;


class NaskahTTEController extends Controller
{
    public function index_ket_tte($id)
    {
        $hashids = new Hashids('RSTDS JAYA');
        $_id = $hashids->decode($id);
        //dd($_id);
        if (empty($_id)) {
            abort(404, 'Invalid or malformed ID');
        }
        $id_kedua_3_digit = str_pad($_id[1], 3, '0', STR_PAD_LEFT);
        $decoded_id = $_id[0] . id_kedua_3_digit;
        $ket_tte = KeteranganTTE::find($decoded_id);
        if (!$ket_tte) {
            abort(404, 'Data not found');
        }
        // dd($ket_tte);
        return view('naskah-tte.index', compact('ket_tte'));
    }

}

