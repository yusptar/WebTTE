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
        
        try{
            $tarif = 0;

            if($request->jns_kendaraan === 'Sepeda Motor') {
                $tarif = 3000; 
            } else {
                $tarif = 5000; 
            }
            
            $parkir = Parkir::create([
                'no_plat' => $request->no_plat,
                'jns_kendaraan' => $request->jns_kendaraan,
                'tarif' => $tarif,    
                'jam_masuk' => Carbon::now()->format('Y/m/d H:i:s'),
                'jam_keluar' => $request->jam_keluar,
            ]);
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }
        return response()->json(['success' => 'Berhasil menambahkan data', 'data' => $parkir], 200);
    }

    
}