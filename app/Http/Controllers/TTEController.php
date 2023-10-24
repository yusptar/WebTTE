<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use \App\Models\BerkasDigital;
use \App\Models\MasterBerkas;
use \App\Models\ManajemenTTE;
use \App\Models\TTELog;
use Illuminate\Support\Carbon;

class TTEController extends Controller
{
    public function index()
    {
        $mstr_berkas = MasterBerkas::all();
        $brks_digital = BerkasDigital::get();
        $manj_tte = ManajemenTTE::get();
        return view('form_tte.tte', compact('mstr_berkas', 'brks_digital', 'manj_tte'));
    }

    public function store(Request $request)
    {    
        $validator = $this->validator($request->all());
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Proses Code path
        
        try{
            $tte = ManajemenTTE::create([
                'no_rawat' => $request->no_rawat,
                'jenis_rm' => $request->jenis_rm,
                'tanggal_upload' => Carbon::now()->format('Y/m/d H:i:s'),
                'path' => $pdf,
            ]);
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }
        return response()->json(['success' => 'Berhasil menambahkan data', 'data' => $parkir], 200);
    }

    public function update(Request $request)
    {    
        $validator = $this->validator($request->all());
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
        
        try{
            $tte = ManajemenTTE::create([
                'no_rawat' => $request->no_rawat,
                'jenis_rm' => $request->jenis_rm,
                'tanggal_upload' => Carbon::now()->format('Y/m/d H:i:s'),
                'path' => $pdf,
            ]);
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }
        return response()->json(['success' => 'Berhasil menambahkan data', 'data' => $parkir], 200);
    }

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function kirimTTE(Request $request){
        
        // $rules = [
        //     'passphrase' => 'required',
        // ];
    
        // $customMessages = [
        //     'required' => 'Passphrase is required'
        // ];
    
        // $this->validate($request, $rules, $customMessages);

        if($request->passphrase == "123456"){
            // try{
            //     $tte = ManajemenTTE::where([
            //         'no_rawat' => $request->no_rawat,
            //         'jenis_rm' => $request->jenis_rm,
            //         'tanggal_upload' => $request->tanggal_upload
            //         ])->update([
            //             'tanggal_signed' => Carbon::now()->format('Y/m/d H:i:s'),
            //             'signed_status' => 'SUDAH',
            //         ]);
            // }catch(Exception $e){
            //     return response()->json(['msg' => $e->getMessage()], 500);
            // }
            // return response()->json(['msg' => 'Proses TTE berhasil..!!', 'data' => $tte], 200);
            return response()->json(['msg' => 'Proses TTE berhasil..!!'], 200);
        } else {
            // $user = Session::get('id');
            $user = "20220294535";
            try{
                $tte_log = TTELog::create([
                    'user' => $user,
                    'created_at' => Carbon::now()->format('Y/m/d H:i:s'),
                    'message' => "This is error message",
                ]);
            }catch(Exception $e){
                return response()->json(['msg' => $e->getMessage()], 500);
            }
            return response()->json(['msg' => 'Proses TTE gagal..!!'], 400);
        }

    }

    
}