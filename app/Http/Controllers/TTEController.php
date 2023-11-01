<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use \App\Models\BerkasDigital;
use \App\Models\MasterBerkas;
use \App\Models\ManajemenTTE;
use \App\Models\TTELog;
use Illuminate\Support\Carbon;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use GuzzleHttp\Client;

class TTEController extends Controller
{
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'no_rawat' => ['required', 'string'],
            'jenis_rm' => ['required', 'string'],
            'path' => ['required', 'file', 'mimes:pdf'],
        ]);
    }

    public function index()
    {
        $mstr_berkas = MasterBerkas::all();
        $brks_digital = BerkasDigital::get();
        $manj_tte = ManajemenTTE::get();
        return view('form_tte.upload', compact('mstr_berkas', 'brks_digital', 'manj_tte'));
    }

    public function index_pembubuhan_tte()
    {
        $mstr_berkas = MasterBerkas::all();
        $brks_digital = BerkasDigital::get();
        $manj_tte = ManajemenTTE::get();
        return view('form_tte.pembubuhan', compact('mstr_berkas', 'brks_digital', 'manj_tte'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'no_rawat' => 'required|string',
            'jenis_rm' => 'required|string',
            'path' => 'required|mimes:pdf',
        ]);
            
        $validator = $this->validator($request->all());
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $js_rm = $request->jenis_rm; 
        $no_rawat = $request->no_rawat; 
        $pdf_name = 'RM' . $js_rm . '_' . $no_rawat . '.pdf'; // Format nama file PDF (contoh: RM07_20231012000005.pdf)

        $pdf = $request->file('path');
        // $rm_path = '/pages/upload/' . $pdf_name;
        // file_put_contents('https://rssoepraoen.com/webapps/berkasrawat' . $rm_path, file_get_contents($pdf));

        $client = new Client();
        $response = $client->request('POST', 'https://rssoepraoen.com/webapps/berkasrawat/pages/upload', [
            'multipart' => [
                [
                    'name'     => 'file', // Nama field untuk file pada server
                    'contents' => fopen($pdf->getPathname(), 'r'), // Baca file PDF
                    'filename' => $pdf_name, // Nama file yang akan disimpan
                ],
            ],
        ]);

        if ($response->getStatusCode() === 200) {
            try{
                $tte = ManajemenTTE::create([
                    'no_rawat' => $request->no_rawat,
                    'jenis_rm' => $request->jenis_rm,
                    'tanggal_upload' => Carbon::now()->format('Y/m/d H:i:s'),
                    'path' => '/pages/upload/' . $pdf_name,
                    'signed_status' => 'BELUM',
                ]);
            }catch(Exception $e){
                return response()->json(['error' => $e->getMessage()], 500);
            }
            return response()->json(['success' => 'Berhasil menambahkan data', 'data' => $parkir], 200);
        } else {
            return response()->json(['error' => 'Gagal mengunggah file PDF'], $response->getStatusCode());
        }
    }

    public function update(Request $request)
    {
        $no_rawat = $request->no_rawat;
        $jenis_rm = $request->jenis_rm;
        $tgl_upload = $request->tanggal_upload;

        $validator = $this->validator($request->all());
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
        
        try{
            $tte = ManajemenTTE::select('no_rawat', 'jenis_rm', 'tgl_upload')->find($no_rawat);
            $tte->tanggal_signed = Carbon::now()->format('Y/m/d H:i:s');
            $tte->signed_status = 'SUDAH';
            $tte->save();
        } catch(Exception $e){
            return response()->json(['code' => 0, 'msg' => $e->getMessage()], 500);
        }
        return response()->json(['code' => 1, 'msg' => 'Kirim TTE Berhasil'], 200);
    }

    public function __construct()
    {
        $this->middleware('auth');
    }


    // public function kirimTTE(Request $request){
        
    //     // $rules = [
    //     //     'passphrase' => 'required',
    //     // ];
    
    //     // $customMessages = [
    //     //     'required' => 'Passphrase is required'
    //     // ];
    
    //     // $this->validate($request, $rules, $customMessages);

    //     if($request->passphrase == "123456"){
    //         // try{
    //         //     $tte = ManajemenTTE::where([
    //         //         'no_rawat' => $request->no_rawat,
    //         //         'jenis_rm' => $request->jenis_rm,
    //         //         'tanggal_upload' => $request->tanggal_upload
    //         //         ])->update([
    //         //             'tanggal_signed' => Carbon::now()->format('Y/m/d H:i:s'),
    //         //             'signed_status' => 'SUDAH',
    //         //         ]);
    //         // }catch(Exception $e){
    //         //     return response()->json(['msg' => $e->getMessage()], 500);
    //         // }
    //         // return response()->json(['msg' => 'Proses TTE berhasil..!!', 'data' => $tte], 200);
    //         return response()->json(['msg' => 'Proses TTE berhasil..!!'], 200);
    //     } else {
    //         // $user = Session::get('id');
    //         $user = "20220294535";
    //         try{
    //             $tte_log = TTELog::create([
    //                 'user' => $user,
    //                 'created_at' => Carbon::now()->format('Y/m/d H:i:s'),
    //                 'message' => "This is error message",
    //             ]);
    //         }catch(Exception $e){
    //             return response()->json(['msg' => $e->getMessage()], 500);
    //         }
    //         return response()->json(['msg' => 'Proses TTE gagal..!!'], 400);
    //     }

    // } 
}