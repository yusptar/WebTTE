<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use \App\Models\BerkasDigital;
use \App\Models\MasterBerkas;
use \App\Models\ManajemenTTE;
use \App\Models\StatusTTEPPA;
use \App\Models\TTELog;
use Exception;
use Illuminate\Support\Carbon;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class TTEController extends Controller
{
    protected $manajemenTTE;
    public function __construct()
    {
        $this->middleware('auth');
        $this->manajemenTTE = new ManajemenTTE();
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'no_rawat' => ['string'],
            'path' => ['required', 'file', 'mimes:pdf'],
        ]);
    }

    // VIEW UPLOAD RM
    public function index()
    {
        $mstr_berkas = MasterBerkas::all();
        $brks_digital = BerkasDigital::get();
        $manj_tte = ManajemenTTE::get();
        return view('form_tte.upload', compact('mstr_berkas', 'brks_digital', 'manj_tte'));
    }

    // START IEW PEMBUBUHAN TTE PDF
    public function view_pembubuhan_rm(){
        return view('form_tte.pembubuhan');
    }

    public function index_pembubuhan_tte(Request $request)
    {
        $data = $this->manajemenTTE->getStatusFileRM();
  
        if ($request->status == 'BELUM') {
            $data = $data->where('status', $request->status);
        }

        return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function($row){
                    return ($row->status == 'BELUM') ? '<span class="badge rounded-pill bg-secondary" >BELUM</span>' : '<span class="badge rounded-pill bg-success" >SUDAH</span>';
                })
                ->addColumn('action', function($row){
                    return ($row->status == 'BELUM') ? '<button class="btn btn-primary btn-sm cetak-btn" id="open-modal" type="button">Sign Now..!!</button>' : 'No Action';
                })
                ->rawColumns(['status','action'])
                ->make(true);      
    }
    //END

    // START VIEW PEMBUBUHAN TTE 
    public function view_pembubuhan_surat(){
        return view('form_tte.pembubuhan_surat');
    }

    public function pembubuhan_surat_list(Request $request)
    {
        $userNIP = Auth::user()->pegawai->nik;
        $data = ManajemenTTE::with('statustteppa')
            ->whereHas('statustteppa', function ($query) use ($userNIP) {
                $query->where('nip', $userNIP);
            })
            ->get();

        if ($request->status == 'BELUM') {
            $data = $data->where('status', $request->status);
        }

        return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('signed_status', function($row){
                    return ($row->signed_status == 'BELUM') ? '<span class="badge rounded-pill bg-secondary" >BELUM</span>' : '<span class="badge rounded-pill bg-success" >SUDAH</span>';
                })
                ->addColumn('action', function($row){
                    return ($row->signed_status == 'BELUM') ? '<button class="btn btn-primary btn-sm cetak-btn" id="open-modal" type="button">Sign Now..!!</button>' : 'No Action';
                })
                ->rawColumns(['signed_status','action'])
                ->make(true);
    }
    // END

    // VIEW LIST DOKUMEN RANAP
    public function view_dokumen_ranap(){
        return view('list_dokumen.listdokumen');
    }

    public function index_list_dokumen_ri(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->manajemenTTE->getDetailRMRanap();
  
            if ($request->filled('from_date') && $request->filled('to_date')) {
                $data = $data->whereBetween('tgl_registrasi', [$request->from_date, $request->to_date]);
            }
  
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('status', function($row){
                        return ($row->signed_status == 'BELUM') ? '<span class="badge rounded-pill bg-secondary" >BELUM</span>' : '<span class="badge rounded-pill bg-success" >SUDAH</span>';
                    })
                    ->addColumn('action', function($row){
                        return ($row->signed_status == 'SUDAH') ? '<button class="btn btn-primary btn-sm cetak-btn" id="download" type="button">Download</button>' : 'No Action';
                    })
                    ->rawColumns(['status','action'])
                    ->make(true);
        }
            
        return view('list_dokumen.listdokumen');
    }
    //
    
    // START LIST DOKUMEN RALAN
    public function view_dokumen_ralan(){
        return view('list_dokumen.listdokumen');
    }

    public function index_list_dokumen_rj(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->manajemenTTE->getDetailRMRalan();
  
            if ($request->filled('from_date') && $request->filled('to_date')) {
                $data = $data->whereBetween('tgl_registrasi', [$request->from_date, $request->to_date]);
            }
  
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('status', function($row){
                        return ($row->signed_status == 'BELUM') ? '<span class="badge rounded-pill bg-secondary" >BELUM</span>' : '<span class="badge rounded-pill bg-success" >SUDAH</span>';
                    })
                    ->addColumn('action', function($row){
                        return ($row->signed_status == 'SUDAH') ? '<button class="btn btn-primary btn-sm cetak-btn" id="download" type="button">Download</button>' : 'No Action';
                    })
                    ->rawColumns(['status','action'])
                    ->make(true);
        }
        return view('list_dokumen.listdokumen');
    }
    // END

    // START LIST DOKUMEN SURAT
    public function view_dokumen_surat(){
        return view('list_dokumen.surat');
    }

    public function index_list_dokumen_sur(Request $request)
    {

        $userNIP = Auth::user()->pegawai->nik;
        $data = ManajemenTTE::with('statustteppa')
            ->whereHas('statustteppa', function ($query) use ($userNIP) {
                $query->where('nip', $userNIP);
            })
            ->get();

        if ($request->filled('from_date') && $request->filled('to_date')) {
            $data = $data->whereBetween('tanggal_upload', [$request->from_date, $request->to_date]);
        }

        return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function($row){
                    return ($row->signed_status == 'BELUM') ? '<span class="badge rounded-pill bg-secondary" >BELUM</span>' : '<span class="badge rounded-pill bg-success" >SUDAH</span>';
                })
                ->addColumn('action', function($row){
                    return ($row->signed_status == 'SUDAH') ? '<button class="btn btn-primary btn-sm cetak-btn" id="download" type="button">Download</button>' : 'No Action';
                })
                ->rawColumns(['status','action'])
                ->make(true);
       
    }
    // END

    public function download(Request $request)
    {
        $fileName = $request->namaFile;
        //check apakah dokumen ada di storage
        if (Storage::disk('myRM')->exists($fileName)) {
            return Storage::disk('myRM')->download($fileName);
        } else {
            return response()->json(['msg' => 'Dokumen tidak ditemukan, silahkan hubungi Adminstrator..!!'], 400);
        }
    }

    // MENGIRIM PDF KE STORAGE LARAVEL
    public function store(Request $request)
    {  
        $pdf_upload = false;

        $validator = $this->validator($request->all());
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $no_rawat = $request->no_rawat; 
        $f_no_rawat = str_replace('/', '', $no_rawat);
        $pdf_name = 'SURAT_' . $f_no_rawat . '.pdf';

        try{
            if ($request->hasFile('path')) {
                $pdf_upload = $request->file('path')->storeAs('rekam-medis', $pdf_name);
            } else {
                $pdf_upload = true;
            }

            $tte = ManajemenTTE::create([
                'no_rawat' => $request->no_rawat,
                'tanggal_upload' => Carbon::now()->format('Y-m-d H:i:s'),
                'tanggal_signed' => '0000-00-00 00:00:00',
                'path' => $pdf_name,
                'signed_status' => 'BELUM',
            ]);

            $status_tte_ppa = StatusTTEPPA::create([
                'no_rawat' => $request->no_rawat,
                'nip' => $request->nip,
                'status' => 'BELUM',
            ]);
        } catch (Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }
        return response()->json(['success' => 'Berhasil menambahkan data'], 200);
    }

    // public function update(Request $request)
    // {
    //     $no_rawat = $request->no_rawat;
    //     $jenis_rm = $request->jenis_rm;
    //     $tgl_upload = $request->tanggal_upload;

    //     $validator = $this->validator($request->all());
    //     if ($validator->fails()) {
    //         return response()->json(['error' => $validator->errors()], 400);
    //     }
        
    //     try{
    //         $tte = ManajemenTTE::select('no_rawat', 'jenis_rm', 'tgl_upload')->find($no_rawat);
    //         $tte->tanggal_signed = Carbon::now()->format('Y/m/d H:i:s');
    //         $tte->signed_status = 'SUDAH';
    //         $tte->save();
    //     } catch(Exception $e){
    //         return response()->json(['code' => 0, 'msg' => $e->getMessage()], 500);
    //     }
    //     return response()->json(['code' => 1, 'msg' => 'Kirim TTE Berhasil'], 200);
    // }

    // MENGIRIM PDF KE EKSTERNAL URL
    // public function store(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'no_rawat' => 'required|string',
    //         'jenis_rm' => 'required|string',
    //         'path' => 'required|mimes:pdf',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['error' => $validator->errors()], 400);
    //     }

    //     $js_rm = $request->jenis_rm;
    //     $no_rawat = $request->no_rawat;
    //     $f_no_rawat = str_replace('/', '', $no_rawat);
    //     $pdf_name = 'RM' . $js_rm . '_' . $f_no_rawat . '.pdf';

    //     $pdf = $request->file('path');

    //     try {
    //         $client = new Client();
    //         // Mengirim file PDF ke URL eksternal
    //         $response = $client->request('POST', env('UPL_URL'), [
    //             'multipart' => [
    //                 [
    //                     'name' => 'pdf', // Nama field yang digunakan di server eksternal
    //                     'contents' => fopen($pdf->getPathname(), 'r'), // Baca file PDF
    //                     'filename' => $pdf_name, // Nama file yang akan disimpan
    //                 ],
    //             ],
    //         ]);

    //         if ($response->getStatusCode() === 200) {
    //             $tte = ManajemenTTE::create([
    //                 'no_rawat' => $request->no_rawat,
    //                 'jenis_rm' => $request->jenis_rm,
    //                 'tanggal_upload' => Carbon::now()->format('Y/m/d H:i:s'),
    //                 'tanggal_signed' => Carbon::now()->format('Y/m/d H:i:s'),
    //                 'path' => 'pages/upload/' . $pdf_name, // Ubah path sesuai kebutuhan
    //                 'signed_status' => 'BELUM',
    //             ]);
    //         } else {
    //             return response()->json(['error' => 'Gagal mengunggah file PDF'], $response->getStatusCode());
    //         }
    //     } catch (Exception $e) {
    //         return response()->json(['error' => $e->getMessage()], 500);
    //     }

    //     return response()->json(['success' => 'Berhasil menambahkan data'], 200);
    // }



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