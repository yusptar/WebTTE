<?php

namespace App\Http\Controllers\API;

use App\Helpers\ApiFormatter;
use App\Http\Controllers\Controller;
use App\Models\ManajemenTTE;
use Exception;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class UploadRMController extends Controller
{
    protected $statusRM;
    
    public function __construct()
    {
      $this->statusRM = new ManajemenTTE();
    }

    public function index()
    {

    }

   
    public function store(Request $request)
    {
        $edit = false;

        # Validation  Rules
        $rules = [
            'no_rawat'=>'required',
            'jenis_rm'=>'required',
            'file' => 'required|mimes:pdf',
        ];
        $messages = [
            'no_rawat.required' =>'Nomor Rawat tidak boleh kosong.',
            'jenis_rm.required' =>'Jenis RM tidak boleh kosong.',
            'file.required' => 'File upload tidak boleh kosong.',
            'file.mimes' => 'File upload harus berupa PDF.',
        ];

        $validator = Validator::make( $request->all(), $rules, $messages);

        if ( $validator->fails() ) {
            # if validations fails redirect back with errors
            return response()->json(['code' => '400','message' => 'Upload gagal, cek kembali parameter anda..!!'], 400);
        }
        # End of Validation

        $no_rawat = $request->no_rawat;
        $jenis_rm = $request->jenis_rm; 
        $f_no_rawat = str_replace('/', '', $no_rawat);
        // $pdf_name = $request->file->getClientOriginalName();
        $pdf_name = 'RM'. $jenis_rm . '_' . $f_no_rawat . '.pdf';

        // $filerm = ManajemenTTE::where('no_rawat', '=', $no_rawat)->where('jenis_rm', '=', $jenis_rm)->find();
        // if($filerm){
        //     if($filerm->signed_status == 'SUDAH'){
        //         return response()->json(['code' => '400','message' => 'RM sudah tertandatangani secara elektronik, tidak bisa upload ulang.'], 400);
        //     } else {
        //         $edit = true;
        //     }
        // }
        
        if($this->statusRM->countStatusSudah($no_rawat,$jenis_rm) > 0){
            return response()->json(['code' => '400','message' => 'RM sudah tertandatangani secara elektronik, tidak bisa upload ulang.'], 400);
        } else {
            $edit = true;
        }
        
        try {
            $pdf_upload = $request->file('file')->storeAs('rekam-medis', $pdf_name);
            
            if($edit){
                ManajemenTTE::where('no_rawat', '=', $no_rawat)->where('jenis_rm', '=', $jenis_rm)->delete();
                // $filerm->tanggal_upload = Carbon::now()->format('Y-m-d H:i:s');
                // $filerm->jenis_rm = $jenis_rm;
                // $filerm->path = $pdf_name;
                // $filerm->signed_status = 'BELUM';
                // $filerm->save();
            }
            
            $data = ManajemenTTE::create([
                'no_rawat' => $request->no_rawat,
                'jenis_rm' => $jenis_rm,
                'tanggal_upload' => Carbon::now()->format('Y-m-d H:i:s'),
                'tanggal_signed' => '0000-00-00 00:00:00',
                'path' => $pdf_name,
                'signed_status' => 'BELUM',
            ]);
        } catch (Exception $errmsg) {
            // return ApiFormatter::createAPI(400, 'Failed' . $errmsg);
            return response()->json(['code' => '400','message' => $errmsg], 400);
        }
        return response()->json(['code' => '200','message' => 'Berhasil upload dengan nama file '.$pdf_name], 200);
        // return ApiFormatter::createAPI(200, 'Success', $data);
        // return ApiFormatter::createAPI(200, 'Success '.$pdf_name);
    }
}
