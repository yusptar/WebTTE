<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use \App\Models\BerkasDigital;
use \App\Models\MasterBerkas;
use \App\Models\ManajemenTTE;
use \App\Models\ManajemenSurat;
use \App\Models\StatusTTEPPA;
use \App\Models\KeteranganTTE;
use \App\Models\TTELog;
use \App\Models\Pegawai;
use Exception;
use Illuminate\Support\Carbon;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Hashids\Hashids;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Psr7;


class TTEController extends Controller
{
    protected $manajemenTTE;
    protected $manajemenTTESurat;
    public function __construct()
    {
        $this->middleware('auth');
        $this->manajemenTTE = new ManajemenTTE();
        $this->manajemenTTESurat = new ManajemenSurat();
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'no_rawat' => ['string'],
            'path' => ['required', 'file', 'mimes:pdf'],
        ]);
    }

    // VIEW 
    public function index_surat()
    {
        $pegawai = Pegawai::all();
        $m_berkas = MasterBerkas::all();
        return view('form_tte.upload', compact('m_berkas', 'pegawai'));
    }

    public function index_rm()
    {
        $pegawai = Pegawai::all();
        $m_berkas = MasterBerkas::all();
        return view('form_tte.upload_rm', compact('m_berkas', 'pegawai'));
    }


    // public function index_ket_tte($id)
    // {
    //     $id = 'eBkQKEvwZVsn';
    //     $hashids = new Hashids('this is my salt'); 
    //     $_id = $hashids->decode($id);
    //     $ket_tte = KeteranganTTE::find($id);
    //     dd($_id);
    //     return view('naskah-tte.index', compact('ket_tte'));
    // }

    public function view_pembubuhan_rm(){
        return view('form_tte.pembubuhan');
    }

    public function view_pembubuhan_surat(){
        return view('form_tte.pembubuhan_surat');
    }

    public function view_dokumen_ranap(){
        return view('list_dokumen.listdokumen');
    }

    public function view_dokumen_ralan(){
        return view('list_dokumen.listdokumen');
    }

    public function view_dokumen_surat(){
        return view('list_dokumen.surat');
    }

    public function view_berkas_tte(){
        return view('settings.cek_tte');
    }

    // DATATABLE 
    public function index_pembubuhan_rm(Request $request)
    {
        $data = $this->manajemenTTE->getStatusFileRM();
        // var_dump($data);
        if ($request->status == 'BELUM') {
            $data = $data->where('status_ppa', $request->status);
        } else {
            $data = $data->where('status_ppa', $request->status);
        }

        return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('nm_ruang', function($row){
                    return  $this->manajemenTTE->getKamar($row->no_rawat)->nm_ruang;
                })
                ->addColumn('signed_status', function($row){
                    return ($row->status_ppa == 'BELUM') ? '<span class="badge rounded-pill bg-secondary" >BELUM</span>' : '<span class="badge rounded-pill bg-success" >SUDAH</span>';
                })
                ->addColumn('action', function($row){
                    // return ($row->status_ppa == 'BELUM') ? '<button class="btn btn-primary btn-sm cetak-btn" id="open-modal" type="button">Sign Now..!!</button> &nbsp; <button class="btn btn-danger btn-sm cetak-btn" id="hapus" type="button">Hapus</button>' : '<button class="btn btn-danger btn-sm cetak-btn" id="hapus" type="button">Hapus</button>';
                    return ($row->status_ppa == 'BELUM') ? '<button class="btn btn-primary btn-sm cetak-btn" id="open-modal" type="button">Sign Now..!!</button>' : 'No Action';
                })
                ->rawColumns(['signed_status','action'])
                ->make(true);
    }

    public function index_cek_tte()
    {

        $data = $this->manajemenTTE->getStatusFileRMAdmin();
        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('nm_ruang', function ($row) {
                return $this->manajemenTTE->getKamar($row->no_rawat)->nm_ruang;
            })
            ->addColumn('signed_status', function($row){
                return ($row->status_ppa == 'BELUM') ? '<span class="badge rounded-pill bg-secondary" >BELUM</span>' : '<span class="badge rounded-pill bg-success" >SUDAH</span>';
            })
            ->rawColumns(['signed_status'])
            ->make(true);
    }

    public function delete_berkas(Request $request)
    {
        try {
            if ($request->type === 'hapus_semua') {
                DB::table('manajemen_rm_tte')->delete();
                DB::table('status_tte_ppa')->delete();
                DB::table('keterangan_tte_ppa')->delete();
    
                return response()->json([
                    'msg' => 'Semua data berhasil dihapus.',
                ]);
            } else {
                $noRawat = $request->no_rawat;
                $namaFile = $request->nama_file;
                $jenisRM = $request->jenis_rm;
                $noRawatFormatted = str_replace("/", "", $noRawat);

                DB::table('manajemen_rm_tte')
                    ->where('no_rawat', $noRawat)
                    ->where('path', $namaFile)
                    ->delete();

                DB::table('status_tte_ppa')
                    ->where('no_rawat', $noRawat)
                    ->where('jenis_rm', $jenisRM)
                    ->delete();

                DB::table('keterangan_tte_ppa')
                    ->where('id', $noRawatFormatted)
                    ->delete();

                return response()->json([
                    'msg' => 'Data berhasil dihapus.',
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'msg' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function pembubuhan_surat_list(Request $request)
    {
        $data = $this->manajemenTTESurat->getDetailFileSurat();

        if ($request->status == 'BELUM') {
            $data = $data->where('signed_status', $request->status);
        } else {
            $data = $data->where('signed_status', $request->status);
        }

        return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('signed_status', function($row){
                    return ($row->signed_status == 'BELUM') ? '<span class="badge rounded-pill bg-secondary" >BELUM</span>' : '<span class="badge rounded-pill bg-success" >SUDAH</span>';
                })
                ->addColumn('action', function($row){
                    return ($row->signed_status == 'BELUM') ? '<button class="btn btn-primary btn-sm cetak-btn" id="open-modal" type="button">Sign Now..!!</button>' : 'No Action';
                    // return ($row->signed_status == 'BELUM') ? '<button class="btn btn-primary btn-sm cetak-btn" id="open-modal" type="button">Sign Now..!!</button> &nbsp; <button class="btn btn-danger btn-sm cetak-btn" id="hapus" type="button">Hapus</button>' : '<button class="btn btn-danger btn-sm cetak-btn" id="hapus" type="button">Hapus</button>';
                })
                ->rawColumns(['signed_status','action'])
                ->make(true);
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
                        // return ($row->signed_status == 'SUDAH') ? '<button class="btn btn-primary btn-sm cetak-btn" id="download" type="button" data-id="'.$row->path.'">Download</button>' : 'No Action';
                        return ($row->signed_status == 'SUDAH') ? 
                        '<div class="btn-group" role="group">
                            <button id="download" type="button" class="btn btn-outline-primary" data-id="' . $row->path . '" data-jenisrm="' . $row->kd_jenis_rm . '" style="cursor:pointer;"title="Download File"><i class="fas fa-download"></i></button>
                            <button id="kirim_wa"  type="button" class="btn btn-outline-success" data-id="' . $row->path . '" data-jenisrm="' . $row->kd_jenis_rm . '" style="cursor:pointer;"title="Kirim Whatsapp"><i class="fas fa-paper-plane"></i></button>
                        </div>' : 'No Action';
                    })
                    ->rawColumns(['status','action'])
                    ->make(true);
        }
            
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
                        // return ($row->signed_status == 'SUDAH') ? '<button class="btn btn-primary btn-sm cetak-btn" id="download" type="button" data-id="'.$row->path.'">Download</button>' : 'No Action';
                        return ($row->signed_status == 'SUDAH') ? 
                        '<div class="btn-group" role="group">
                            <button id="download" type="button" class="btn btn-outline-primary" data-id="' . $row->path . '"  data-jenisrm="' . $row->kd_jenis_rm . '" style="cursor:pointer;"title="Download File"><i class="fas fa-download"></i></button>
                            <button id="kirim_wa"  type="button" class="btn btn-outline-success" data-id="' . $row->path . '"  data-jenisrm="' . $row->kd_jenis_rm . '" style="cursor:pointer;"title="Kirim Whatsapp"><i class="fas fa-paper-plane"></i></button>
                        </div>' : 'No Action';
                    })
                    ->rawColumns(['status','action'])
                    ->make(true);
        }
        return view('list_dokumen.listdokumen');
    }
   
    public function index_list_dokumen_sur(Request $request)
    {

        $data = $this->manajemenTTESurat->getDetailFileSurat();

        if ($request->filled('from_date') && $request->filled('to_date')) {
            $data = $data->whereBetween('tanggal_upload', [$request->from_date.' 00:00:00', $request->to_date.' 23:59:59']);
        }
        
        return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function($row){
                    return ($row->signed_status == 'BELUM') ? '<span class="badge rounded-pill bg-secondary" >BELUM</span>' : '<span class="badge rounded-pill bg-success" >SUDAH</span>';
                })
                ->addColumn('action', function($row){
                    return ($row->signed_status == 'SUDAH') ? 
                    '<div class="btn-group" role="group">
                        <button id="download" type="button" class="btn btn-outline-primary" data-id="' . $row->path . '" style="cursor:pointer;"title="Download File"><i class="fas fa-download"></i></button>
                        <button id="kirim_wa"  type="button" class="btn btn-outline-success" data-id="' . $row->path . '" style="cursor:pointer;"title="Kirim Whatsapp"><i class="fas fa-paper-plane"></i></button>
                    </div>' : 'No Action';
                    // '<button class="btn btn-primary btn-sm cetak-btn" id="download" type="button" value="'.$row->path.'">Download</button> &nbsp <button class="btn btn-primary btn-sm cetak-btn" id="download1" type="button" value="'.$row->path.'">KIRIM</button>' 
                    // : 'No Action';
                })
                ->rawColumns(['status','action'])
                ->make(true);
       
    }

    public function index_list_dokumen_rm_rj(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->manajemenTTE->getPasienRalan();
  
            if ($request->filled('from_date') && $request->filled('to_date')) {
                $data = $data->whereBetween('tanggal', [$request->from_date, $request->to_date]);
            }
  
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        return '<button class="btn btn-primary btn-sm cetak-btn" id="detail" type="button" data-id="'.$row->no_rawat.'">Detail</button>' ;
                    })
                    ->make(true);
                    // return ($row->status_akhir == '1') ? '<button class="btn btn-primary btn-sm cetak-btn" id="detail" type="button" value="'.$row->path.'">Detail</button>' : 'Belum Lengkap';
        }
        return view('list_dokumen.listpasien');
    }
    
    public function index_list_dokumen_rm_ri(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->manajemenTTE->getPasienRanap();
  
            if ($request->filled('from_date') && $request->filled('to_date')) {
                $data = $data->whereBetween('tanggal', [$request->from_date, $request->to_date]);
            }
  
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        return '<button class="btn btn-primary btn-sm cetak-btn" id="detail" type="button" data-id="'.$row->no_rawat.'">Detail</button>' ;
                    })
                    ->make(true);
                    // return ($row->status_akhir == '1') ? '<button class="btn btn-primary btn-sm cetak-btn" id="detail" type="button" value="'.$row->path.'">Detail</button>' : 'Belum Lengkap';
        }
            
        return view('list_dokumen.listpasien');
    }
   
    public function rm_detail(Request $request)
    {
        $no_rawat = $request->id;
        $jenis_pelayanan = ($this->manajemenTTE->getJenisPelayanan($no_rawat)=="Ralan")?"2":"1";
        $rm_details = $this->manajemenTTE->getDetailRM($no_rawat,$jenis_pelayanan);
        return response()->json(['details' => $rm_details]);
    }
    // END DATATABLE
    

    // CETAK 
    public function download(Request $request)
    {
        // dd($request->namaFile);
        $fileName = $request->namaFile;
        $jenisRM = $request->jenisRM;
        
        if (Storage::disk('myRM')->exists('/' . $jenisRM . '/' . $fileName)) {
            return Storage::disk('myRM')->download('/' . $jenisRM . '/' . $fileName);
        } else {
            return response()->json(['msg' => 'Dokumen tidak ditemukan, silahkan hubungi Adminstrator..!! '], 400);
        }
    }

    public function downloadberkas(Request $request)
    {
        $noRawat = $request->noRawat;
        $jenisRM = $request->jenisRM;
        $kodeJenisRM="";
        switch($jenisRM){
            case "btn-awal-medis-igd":
                $kodeJenisRM="026";
                break;
            case "btn-awal-kep-igd":
                $kodeJenisRM="019";
                break;
            case "btn-awal-medis":
                $kodeJenisRM="006";
                break;
            case "btn-awal-kep":
                $kodeJenisRM="020";
                break;
            case "btn-resume-medis":
                $kodeJenisRM="017";
                break;
            case "btn-laporan-operasi":
                $kodeJenisRM="008";
                break;
            case "btn-hasil-lab":
                $kodeJenisRM="012";
                break;
            case "btn-hasil-rad":
                $kodeJenisRM="013";
                break;
            case "btn-cppt":
                $kodeJenisRM="022";
                break;
        }
        $fileName = $this->manajemenTTE->getFileName($noRawat,$kodeJenisRM)['path'];
        if (Storage::disk('myRM')->exists('/' . $jenisRM . '/' . $fileName)) {
            return Storage::disk('myRM')->download('/' . $jenisRM . '/' . $fileName);
        } else {
            return response()->json(['msg' => 'Dokumen tidak ditemukan, silahkan hubungi Adminstrator..!! '.$fileName.' | '.$kodeJenisRM], 400);
        }
    }

    public function kirimWA(Request $request)
    {
        // $fileName = $request->namaFile;
        // $jenisRM = $request->jenisRM;

        // if (Storage::disk('myRM')->exists('/' . $jenisRM . '/' . $fileName)) {

        //     $url = config('app.url_api_wa').'/api/messages/send';

        //     $dataPasien = $this->manajemenTTESurat->getDataPasien($fileName);
        //     // dd($dataPasien);
        //     $nomorTelp = substr($dataPasien[0]->no_tlp,1,strlen($dataPasien[0]->no_tlp));
            
        //     $number = '62'.$nomorTelp.'@s.whatsapp.net';
        //     $namaPasien = $dataPasien[0]->nm_pasien;
        //     $noRM = $dataPasien[0]->no_rkm_medis;

        //     $requested_data = [
        //         'number' => $number,
        //         'message' => 'Terimakasih atas kepercayaan yg telah diberikan kepada RS Tk. II dr. Soepraoen
        //              Yth. Bpk/Ibu '.$namaPasien.'
        //              Dengan ini kami kirimkan surat keterangan buta warna.
        //                Nomor RM     : '.$noRM.'
        //                Nama         : '.$namaPasien.'
        //              Untuk pendaftaran online, unduh aplikasi Halo Soepraoen/ JKN Mobile.
        //              Pendaftaran pasien BPJS Rumah Sakit Tk.II dr.Soepraoen per tanggal 03 September 2024 *Wajib Melakukan Pendaftaran Melalui Mobile JKN*
        //              Download Aplikasi JKN Mobile dengan klik link di bawah ini. https://play.google.com/store/apps/details?id=app.bpjs.mobile
        //              Terima kasih
        //              Hormat kami,
        //              RST dr.SOEPRAOEN',
        //     ];
    
        //     $jsonData = json_encode($requested_data);
                
        //     $header = [
        //         'Accept: application/json',
        //         'Content-Type: application/json',
        //     ];
    
        //     $ch = curl_init($url);
        //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //     curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        //     curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
    
        //     $res = curl_exec($ch);
        //     $data['response'] = json_decode($res, true);
        //     $data['requested_data'] = $jsonData;
        //     curl_close($ch);


        //     //kirim file
        //     $client = new Client();
            
        //     $url = config('app.url_api_wa').'/api/messages/send-media';
        //     try{
        //         $response = $client->request('POST', $url, [
        //             'multipart' => [
        //                 [
        //                     'name' => 'file',
        //                     'contents' => Psr7\Utils::tryFopen(storage_path('app/rekam-medis/') . '/' . $jenisRM . '/' . $fileName, 'r'),
        //                     'filename' => $fileName,
        //                     'headers'  => [
        //                         'Content-Type' => 'application/pdf',
        //                     ]
        //                 ],
        //                 [
        //                     'name' => 'number',
        //                     'contents' => $number
        //                 ],
        //                 [
        //                     'name' => 'caption',
        //                     'contents' => 'Surat_'.$namaPasien
        //                 ]
        //             ],
        //         ]);
        //         // dd($response);
        //         $statusCode = $response->getStatusCode();
        //         $reason = $response->getReasonPhrase(); 
        //         if($statusCode == '200'){
        //             $response_ = json_decode($response->getBody(),true);
        //             // dd($response_);
        //             return response()->json(['msg' => $response_['status']], 200);
        //             // return response()->json(['msg' => 'Proses Berhasil..!!!', ], 200);
        //         }
        //     } catch (Exception $e) {
        //         // dd($e);
        //         return response()->json(['msg' => $e->getMessage()], 400);
        //     }

        // } else {
        //     return response()->json(['msg' => 'Dokumen tidak ditemukan, silahkan hubungi Adminstrator..!! '], 400);
        // }
    }

    public function kirimWAOCA(Request $request)
    {
        $fileName = $request->namaFile;
        $jenisRM = $request->jenisRM;

        if (Storage::disk('myRM')->exists('/' . $jenisRM . '/' . $fileName)) {

            //copy file
            $content = Storage::disk('myRM')->get('/' . $jenisRM . '/' . $fileName);
            Storage::put('public/'.$fileName, $content);

            $url = config('app.url_api_wa').'/api/v2/push/message';

            $dataPasien = $this->manajemenTTESurat->getDataPasien($fileName);
            // dd($dataPasien);
            $nomorTelp = substr($dataPasien[0]->no_tlp,1,strlen($dataPasien[0]->no_tlp));
            // $nomorTelp = '85755554151';
            $number = '62'.$nomorTelp;
            $namaPasien = $dataPasien[0]->nm_pasien;
            $noRM = $dataPasien[0]->no_rkm_medis;

            $jenisDokumen = DB::table('manajemen_rm_tte as m')
                ->join('master_berkas_digital as d', 'd.kode', '=', 'm.jenis_rm')
                ->select(DB::raw('d.nama'))
                ->where('m.path', $fileName)
                ->first();

            $templateID = DB::table('daftar_api_internal')
                ->select('url')
                ->where('jenis', 'id_template_document_oca')
                ->first();
                
            $postfield = '{
                "phone_number": "'.$number.'",
                "message": {
                    "type": "template",
                    "template": {
                        "template_code_id": "'.$templateID->url.'",
                        "payload": [
                            {
                                "position": "header",
                                "parameters": [
                                    {
                                        "type": "document",
                                        "document": {
                                            "url": "https://rssoepraoen.com/tte/storage/'. $jenisRM . '/' .$fileName.'"
                                        }
                                    }
                                ]
                            },
                            {
                                "position": "body",
                                "parameters": [
                                    {
                                        "type": "text",
                                        "text": "'.$namaPasien.'"
                                    },
                                    {
                                        "type": "text",
                                        "text": "'.$jenisDokumen->nama.'"
                                    },
                                    {
                                        "type": "text",
                                        "text": "'.$namaPasien.'"
                                    },
                                    {
                                        "type": "text",
                                        "text": "'.$noRM.'"
                                    }
                                ]
                            }
                        ]
                    }
                }
            }';

            $curl = curl_init();
            curl_setopt_array($curl, array(
              CURLOPT_URL => $url,
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_SSL_VERIFYPEER => false, //add line 
              CURLOPT_SSL_VERIFYHOST => false, //add line
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_HEADER => true,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS => $postfield,
              CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Bearer eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9.eyJhcHBsaWNhdGlvbiI6IjY3ZDhmYTU2OTY3ZThmMDAxMmEyOTIyZiIsImlhdCI6MTc0MjI3Njg1N30.PMgFwFHhKoHczwVlcdn0z1eak3Mix5VWQpG9etGUurYJZ9vvyVCAZMn3kZ4gHd0XXrfLp-_sLvE1Q8pm2DtlXfk13bDLeO7NgKDUKTCijGYMXF4GA1dfBDfDP8bUMpvn8cQi2H0fxcIxRZn594afoJnD_Sk0xv_LU7yUSmbTDxEaYSFTElGzvOno7pMqhNjdg7cbRBCiBKxzjZYWLb-c811YUxZ86WWTABImkrYyDT2DRcI-vajBC6s9de-UmvNrgGc-XojM1N_avAuzJXOvc9F6QBSePD73onotKg4k-EbgB0H2Bq4kUbDjPP5AZoH-F3pq3AZkyQYWyHHkmxAYbA'
              ),
            ));
            
            $response = curl_exec($curl);
            $info = curl_getinfo($curl);
            $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
            curl_close($curl);
            // dd($response);
            $header = substr($response, 0, $header_size);
            $body = substr($response, $header_size);

            if($info['http_code']=='200'){
                // return response()->json(['msg' => 'Proses pengiriman berhasil..!!'], 200);
                return response()->json(['msg' => $postfield], 200);
            }else{
                return response()->json(['msg' => 'Error : ' . $info['http_code'] . '; ' . $url.'|'.$postfield], 400);
            }
        } else {
            return response()->json(['msg' => 'Dokumen tidak ditemukan, silahkan hubungi Adminstrator..!! '], 400);
        }
    }

    // STORE
    public function store_surat(Request $request)
    {  
        $pdf_upload = false;

        $validator = $this->validator($request->all());
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $no_rawat = $request->no_rawat; 
        $f_no_rawat = str_replace('/', '', $no_rawat);
        $pdf_name = 'RM999_' . $f_no_rawat . '.pdf';

        try{
            $pegawai = Pegawai::where('nik', $request->nip)->first();
            if (!$pegawai) {
                return response()->json(['error' => 'Petugas tidak ditemukan'], 400);
            }

            if ($request->hasFile('path')) {
                $pdf_upload = $request->file('path')->storeAs('rekam-medis', $pdf_name);
            } else {
                $pdf_upload = true;
            }

            $tte = ManajemenSurat::create([
                'no_rawat' => $request->no_rawat,
                'tanggal_upload' => Carbon::now()->format('Y-m-d H:i:s'),
                'tanggal_signed' => '0000-00-00 00:00:00',
                'jenis_rm' => '999',
                'path' => $pdf_name,
                'signed_status' => 'BELUM',
            ]);

            $status_tte_ppa = StatusTTEPPA::create([
                'no_rawat' => $request->no_rawat,
                'tanggal_upload' => Carbon::now()->format('Y-m-d H:i:s'),
                'tanggal_signed' => '0000-00-00 00:00:00',
                'nip' => $request->nip,
                'jenis_rm' => '999',
                'status' => 'BELUM',
            ]);
        } catch (Exception $e){
            // return response()->json(['msg' => $e->getMessage()], 500);
            return response()->json(['error' => 'Data gagal ditambahkan'], 500);
        }
        return response()->json(['success' => 'Berhasil menambahkan data'], 200);
    }

     public function store_rm(Request $request)
     {  
         $pdf_upload = false;
 
         $validator = $this->validator($request->all());
         if ($validator->fails()) {
             return response()->json(['error' => $validator->errors()], 400);
         }
 
         $no_rawat = $request->no_rawat; 
         $f_no_rawat = str_replace('/', '', $no_rawat);
         $pdf_name = 'RM'.$request->jenis_rm.'_' . $f_no_rawat . '.pdf';
 
         try{
            $pegawai = Pegawai::where('nik', $request->nip)->first();
            if (!$pegawai) {
                return response()->json(['error' => 'Petugas tidak ditemukan'], 400);
            }

            if ($request->hasFile('path')) {
                 $pdf_upload = $request->file('path')->storeAs('rekam-medis', $pdf_name);
            } else {
                 $pdf_upload = true;
            }
 
            $tte = ManajemenTTE::create([
                 'no_rawat' => $request->no_rawat,
                 'tanggal_upload' => Carbon::now()->format('Y-m-d H:i:s'),
                 'tanggal_signed' => '0000-00-00 00:00:00',
                 'jenis_rm' => $request->jenis_rm,
                 'path' => $pdf_name,
                 'signed_status' => 'BELUM',
            ]);

            $status_tte_ppa = StatusTTEPPA::create([
                'no_rawat' => $request->no_rawat,
                'tanggal_upload' => Carbon::now()->format('Y-m-d H:i:s'),
                'tanggal_signed' => '0000-00-00 00:00:00',
                'nip' => $request->nip,
                'jenis_rm' => $request->jenis_rm,
                'status' => 'BELUM',
            ]);
         } catch (Exception $e){
            //  return response()->json(['error' => $e->getMessage()], 500);
             return response()->json(['error' => 'Data gagal ditambahkan'], 500);
         }
         return response()->json(['success' => 'Data berhasil ditambahkan'], 200);
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