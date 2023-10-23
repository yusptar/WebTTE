<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use \App\Models\BerkasDigital;
use \App\Models\MasterBerkas;
use \App\Models\ManajemenTTE;
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
                'jam_upload' => Carbon::now()->format('H:i:s'),
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
                'jam_upload' => Carbon::now()->format('H:i:s'),
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

    
}