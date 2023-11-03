<?php

namespace App\Http\Controllers\API;

use App\Helpers\ApiFormatter;
use App\Http\Controllers\Controller;
use App\Models\ManajemenTTE;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class UploadRMController extends Controller
{
    public function index()
    {

    }

   
    public function store(Request $request)
    {
        $js_rm = $request->jenis_rm; 
        $no_rawat = $request->no_rawat; 
        $f_no_rawat = str_replace('/', '', $no_rawat);
        $pdf_name = 'RM' . $js_rm . '_' . $f_no_rawat . '.pdf';

        try {
            $request->validate([
                'no_rawat' => 'required',
                'jenis_rm' => 'required',
                'path' => 'required'
            ]);

            $data = ManajemenTTE::create([
                'no_rawat' => $request->no_rawat,
                'jenis_rm' => $request->jenis_rm,
                'tanggal_upload' => Carbon::now()->format('Y-m-d H:i:s'),
                'tanggal_signed' => '0000-00-00 00:00:00',
                'path' => $pdf_name,
                'signed_status' => $request->signed_status,
            ]);
        } catch (Exception $errmsg) {
            return ApiFormatter::createAPI(400, 'Failed' . $errmsg);
        }
        return ApiFormatter::createAPI(200, 'Success', $data);
    }
}
