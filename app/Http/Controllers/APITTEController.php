<?php

namespace App\Http\Controllers;

use App\Models\ManajemenTTE;
use App\Models\ManajemenSurat;
use App\Models\StatusTTEPPA;
use App\Models\TTELog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Config;
use Exception;
use Illuminate\Support\Facades\Storage;
use \App\Models\KeteranganTTE;



class APITTEController extends Controller
{
    private $baseurl_api;
    private $storage_location;
    private $qr_location;
    private $client;
    protected $statusTTE;

    public function __construct()
    {
      $this->baseurl_api = config('app.baseurl_api');
      $this->storage_location = storage_path('app/rekam-medis/');//'../storage/app/rekam-medis/';
      $this->qr_location = storage_path('app/qr-code/');
      $this->client = new Client();
      $this->statusTTE = new StatusTTEPPA();
    }

    public function getStatusUser()
    {
        $headers = [
            'Authorization' => 'Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyX2lkIjoxLCJlbWFpbCI6ImFkbWluQHR0ZS5jb20iLCJuaWsiOiIwODAzMjAyMTAwMDA3MDYyIiwiZXhwIjoxNzI5OTgyMjczfQ.ZFCzzT4DP_d6OodzysZlUOt_VLX-ZOt2Y860yZBpJlw'
        ];

        // baru
        $url = $this->baseurl_api . '/account/api/user/status/0803202100007062';
        
        $params = [
            //If you have any Params Pass here
        ];

        $response = $this->client->request('GET', $url, [
            // 'json' => $params,
            'headers' => $headers,
            'verify'  => false,
        ]);

        $responseBody = json_decode($response->getBody());

        if($responseBody->status == 'sukses'){
            return response()->json(['success' => $responseBody->message, ], 200);
        } elseif($responseBody->status == 'gagal') {
            return response()->json(['error' => $responseBody->message], 400);
        }
    }

    public function signInvisible(Request $request)
    {
        // $nik = '0803202100007062';
        $nik = Auth::user()->pegawai->no_ktp;
        $passphrase = $request->passphrase;
        $no_rawat = $request->no_rawat;
        $jenis_rm = $request->jenis_rm;
        // $passphrase ='Hantek1234.!';

        // Validasi Passphrase
        if (!preg_match('/^(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*()-_+=])[A-Za-z0-9!@#$%^&*()-_+=]{8,}$/', $passphrase)) {
            return response()->json(['msg' => 'Passphrase harus memenuhi kriteria: minimal 8 karakter, 1 huruf kapital, 1 angka, dan 1 simbol.'], 400);
        }

        // (?=.*[A-Z]): Minimal satu huruf kapital.
        // (?=.*[0-9]): Minimal satu angka.
        // (?=.*[!@#$%^&*()-_+=]): Minimal satu simbol (dalam contoh ini, simbol yang diperbolehkan adalah !@#$%^&*()-_+=).
        // [A-Za-z0-9!@#$%^&*()-_+=]{8,}: Minimal 8 karakter dari kombinasi huruf besar/kecil, angka, dan simbol yang diperbolehkan.
        // if (strlen($passphrase) < 8) {
        //     return response()->json(['msg' => 'Passphrase harus memiliki minimal 8 karakter.'], 400);
        // }

        $location = '1';
        $nama_file = $request->nama_file;
        $target_file = Str::substr($nama_file , 0 , Str::of($nama_file)->length()-4) . '_.pdf';

        /*
        Check apakah file ada di dalam storage
        */ 
        if(!file_exists($this->storage_location . '/' . $request->jenis_rm . '/' . $nama_file)){
            return response()->json(['msg' => $request->no_rawat . ', ' . 'File tidak ditemukan..!!'], 400);
        }

        if($request->nama_file==""){
            return response()->json(['msg' => $request->no_rawat . ', ' . 'Nama File tidak ditemukan..!!'], 400);
        }

        $url = $this->baseurl_api . '/api/sign/pdf';

        // $headers = [
        //     'Authorization' => 'Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyX2lkIjoxLCJlbWFpbCI6ImFkbWluQHR0ZS5jb20iLCJuaWsiOiIwODAzMjAyMTAwMDA3MDYyIiwiZXhwIjoxNzI5OTgyMjczfQ.ZFCzzT4DP_d6OodzysZlUOt_VLX-ZOt2Y860yZBpJlw'
        // ];
        $resource = fopen($this->storage_location . '/' . $request->jenis_rm . '/' . $target_file, 'w');

        try{
            $response = $this->client->request('POST', $url, [
                'multipart' => [
                    [
                        'name' => 'file',
                        'contents' => Psr7\Utils::tryFopen($this->storage_location . '/' . $request->jenis_rm . '/' . $nama_file, 'r'),
                        'filename' => $nama_file,
                        'headers'  => [
                            'Content-Type' => 'application/pdf',
                        ]
                    ],
                    [
                        'name' => 'nik',
                        'contents' => $nik
                    ],
                    [
                        'name' => 'passphrase',
                        'contents' => $passphrase
                    ],
                    [
                        'name' => 'tampilan',
                        'contents' => 'invisible'
                    ],
                    [
                        'name' => 'location',
                        'contents' => $location
                    ],
                    [
                        'name' => 'text',
                        'contents' => 'Dokumen ini ditandatangani secara elektronik.'
                    ]
                ],
                'auth' => [
                    'esign', 
                    'qwerty'
                ],
                // 'headers' => $headers,
                'stream' => true,
                'verify'  => true,
                'http_errors' => true,
                // 'sink' => $resource,
            ]);
            
            $dateTime = Carbon::now()->format('Y/m/d H:i:s');
            $headers = $response->getHeaders();
            if($headers['Content-Type'][0] == 'application/json'){
                $response_ = json_decode($response->getBody(),true);
                // dd($response_);
                
                $tte_log = TTELog::create([
                    'user' => Auth::user()->pegawai->nik,
                    'created_at' => $dateTime,
                    'message' => $response_['error'],
                ]);
                return response()->json(['msg' => $request->no_rawat . ', ' . $response_['error']], 400);

            }else if($headers['Content-Type'][0] == 'application/pdf'){
                // Storing pdf contents to a file
                // Storage::disk('rekam-medis')->put('/' . $request->jenis_rm . '/' . $target_file, $response->getBody()->getContents());

                //hapus file lama
                // unlink(storage_path('app/rekam-medis/' . $request->jenis_rm . '/' . $nama_file));

                $tgl_upload = ManajemenTTE::where('no_rawat', '=', $no_rawat)->where('path', '=', $nama_file)->select('tanggal_upload')->get()->first()['tanggal_upload'];
                $dataStatusTTE = StatusTTEPPA::where('no_rawat', '=', $no_rawat)->where('jenis_rm', '=', $jenis_rm)->where('tgl_upload', '=', $tgl_upload)->get();
                var_dump($dataStatusTTE);
                $status_tte = StatusTTEPPA::where([
                    'no_rawat' => $no_rawat,
                    'jenis_rm' => $jenis_rm,
                    'tgl_upload' => $tgl_upload,
                    'nip' => Auth::user()->pegawai->nik,
                    ])->update([
                        'tgl_signed' => $dateTime,
                        'status' => 'SUDAH',
                    ]);
                
                $status_ket_tte = KeteranganTTE::where([
                    'no_rawat' => $no_rawat,
                    'jenis_rm' => $jenis_rm,
                    'nip' => Auth::user()->pegawai->nik,
                    ])->update([
                        'tgl_signed' => $dateTime,
                    ]);

                return response()->json(['msg' => $dataStatusTTE. ' | '.$no_rawat. ' | '.$jenis_rm.' | '.$tgl_upload.' | Update status_tte qeuery..!! '.$status_tte], 400);

                try{
                    
                    //cek apakah semua PPA sudah melakukan tanda tangan 
                    $signed_status = 'BELUM';
                    if($this->statusTTE->countStatusBelum($no_rawat,$jenis_rm,$tgl_upload) == 0){
                        $signed_status = 'SUDAH';
                    }
                    $jumlahFileRM = ManajemenTTE::where('no_rawat', '=', $no_rawat)->where('jenis_rm', '=', $jenis_rm)->get();
                    if($jumlahFileRM->count()>0){
                        $tte = ManajemenTTE::where([
                            'no_rawat' => $no_rawat,
                            'path' => $nama_file,
                            ])->update([
                                'tanggal_signed' => $dateTime,
                                'path' => $target_file,
                                'signed_status' => $signed_status,
                            ]);
                    } else {
                        $tte = ManajemenSurat::where([
                            'no_rawat' => $no_rawat,
                            'path' => $nama_file,
                            ])->update([
                                'tanggal_signed' => $dateTime,
                                'path' => $target_file,
                                'signed_status' => $signed_status,
                            ]);
                    }

                    return response()->json(['msg' => 'Proses Berhasil..!!! TglUppload '.$tgl_upload, ], 200);
                }catch(Exception $e){
                    $status_tte = StatusTTEPPA::where([
                        'no_rawat' => $no_rawat,
                        'jenis_rm' => $jenis_rm,
                        'tgl_upload' => $tgl_upload,
                        'nip' => Auth::user()->username,
                        ])->update([
                            'status' => 'BELUM',
                        ]);
                    $tte_log = TTELog::create([
                                        'user' => Auth::user()->pegawai->nik,
                                        'created_at' => $dateTime,
                                        'message' => 'Update Database Gagal..!!',
                                    ]);
                    return response()->json(['msg' => $no_rawat . ', ' . 'Update Database Gagal..!! '.$e], 400);
                }

                
                /* remove file RM yang sebelumnya untuk menghemat storage */
                // unlink(storage_path('app/rekam-medis/' . $request->jenis_rm . '/' . $nama_file));
            }
        }catch(RequestException $err){
            $errMsg="Error: " . $err->getMessage();
            if ($err->hasResponse()) {
                dd( $err->getResponse()->getStatusCode());
                echo "\nHTTP Status Code: " . $err->getResponse()->getStatusCode();
            }
            $tte_log = TTELog::create([
                'user' => Auth::user()->pegawai->nik,
                'created_at' => Carbon::now()->format('Y/m/d H:i:s'),
                'message' => 'Pengiriman data gagal..!!' . $err,
            ]);
            unlink(storage_path('app/rekam-medis/' . $request->jenis_rm . '/' .  $target_file));
            return response()->json(['msg' => $request->no_rawat . ', ' . 'Pengiriman data gagal..!!'], 400);
        }
    }

    // public function signCoordinate(Request $request){
        
    //     $nik = Auth::user()->pegawai->no_ktp;
    //     $passphrase = $request->passphrase;

    //     // Validasi Passphrase
    //     if (!preg_match('/^(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*()-_+=])[A-Za-z0-9!@#$%^&*()-_+=]{8,}$/', $passphrase)) {
    //         return response()->json(['msg' => 'Passphrase harus memenuhi kriteria: minimal 8 karakter, 1 huruf kapital, 1 angka, dan 1 simbol.'], 400);
    //     }
    //     $location = '1';
    //     $nama_file = $request->nama_file;
    //     $target_file = Str::substr($nama_file , 0 , Str::of($nama_file)->length()-4) . '_.pdf';
        
    //     $nama_qr = KeteranganTTE::where('no_rawat', $request->no_rawat)->where('jenis_rm', $request->jenis_rm)->where('nip', Auth::user()->pegawai->nik)->first()->id . '.png';
    //     $tag = KeteranganTTE::where('no_rawat', $request->no_rawat)->where('jenis_rm', $request->jenis_rm)->where('nip', Auth::user()->pegawai->nik)->first()->tag;

    //     /*
    //     Check apakah file ada di dalam storage
    //     */ 
    //     if(!file_exists($this->storage_location . $nama_file)){
    //         return response()->json(['msg' => 'File tidak ditemukan..!!'], 400);
    //     }

    //     if(!file_exists($this->qr_location . $nama_qr)){
    //         return response()->json(['msg' => 'QR tidak ditemukan..!!'], 400);
    //     }

    //     if($request->nama_file==""){
    //         return response()->json(['msg' => 'Nama File tidak ditemukan..!!'], 400);
    //     }

    //     $url = $this->baseurl_api . '/api/sign/pdf';

    //     // $headers = [
    //     //     'Authorization' => 'Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyX2lkIjoxLCJlbWFpbCI6ImFkbWluQHR0ZS5jb20iLCJuaWsiOiIwODAzMjAyMTAwMDA3MDYyIiwiZXhwIjoxNzI5OTgyMjczfQ.ZFCzzT4DP_d6OodzysZlUOt_VLX-ZOt2Y860yZBpJlw'
    //     // ];
    //     $resource = fopen($this->storage_location . $target_file, 'w');

    //     try{
    //         $response = $this->client->request('POST', $url, [
    //             'multipart' => [
    //                 [
    //                     'name' => 'file',
    //                     'contents' => Psr7\Utils::tryFopen($this->storage_location . $nama_file, 'r'),
    //                     'filename' => $nama_file,
    //                     'headers'  => [
    //                         'Content-Type' => 'application/pdf',
    //                     ]
    //                 ],
    //                 [
    //                     'name' => 'imageTTD',
    //                     'contents' => Psr7\Utils::tryFopen($this->qr_location . $nama_qr, 'r'),
    //                     'filename' => $nama_qr,
    //                     'headers'  => [
    //                         'Content-Type' => 'application/png',
    //                     ]
    //                 ],
    //                 [
    //                     'name' => 'nik',
    //                     'contents' => $nik
    //                 ],
    //                 [
    //                     'name' => 'passphrase',
    //                     'contents' => $passphrase
    //                 ],
    //                 [
    //                     'name' => 'tampilan',
    //                     'contents' => 'visible'
    //                 ],
    //                 [
    //                     'name' => 'image',
    //                     'contents' => 'true'
    //                 ],
    //                 [
    //                     'name' => 'width',
    //                     'contents' => '75'
    //                 ],
    //                 [
    //                     'name' => 'height',
    //                     'contents' => '75'
    //                 ],
    //                 [
    //                     'name' => 'tag_koordinat',
    //                     'contents' => $tag
    //                 ]
    //             ],
    //             'auth' => [
    //                 'esign', 
    //                 'qwerty'
    //             ],
    //             // 'headers' => $headers,
    //             'stream' => true,
    //             'verify'  => true,
    //             'http_errors' => true,
    //             // 'sink' => $resource,
    //         ]);
            
    //         $headers = $response->getHeaders();
    //         if($headers['Content-Type'][0] == 'application/json'){
    //             $response_ = json_decode($response->getBody(),true);
    //             // dd($response_);
                
    //             $tte_log = TTELog::create([
    //                 'user' => Auth::user()->pegawai->nik,
    //                 'created_at' => Carbon::now()->format('Y/m/d H:i:s'),
    //                 'message' => $response_['error'],
    //             ]);
    //             return response()->json(['msg' => $response_['error']], 400);

    //         }else if($headers['Content-Type'][0] == 'application/pdf'){
    //             // Storing pdf contents to a file
    //             Storage::disk('rekam-medis')->put($target_file, $response->getBody()->getContents());

    //             $status_tte = StatusTTEPPA::where([
    //                 'no_rawat' => $request->no_rawat,
    //                 'jenis_rm' => $request->jenis_rm,
    //                 'nip' => Auth::user()->pegawai->nik,
    //                 ])->update([
    //                     'status' => 'SUDAH',
    //                 ]);

    //             $status_ket_tte = KeteranganTTE::where([
    //                 'no_rawat' => $request->no_rawat,
    //                 'jenis_rm' => $request->jenis_rm,
    //                 'nip' => Auth::user()->pegawai->nik,
    //                 ])->update([
    //                     'tgl_signed' => Carbon::now()->format('Y/m/d H:i:s'),
    //                 ]);
    //             //hapus QR
    //             unlink(storage_path('app/qr-code/' . $nama_qr));

    //             try{
    //                 //cek apakah semua PPA sudah melakukan tanda tangan 
    //                 $signed_status = 'BELUM';
    //                 if($this->statusTTE->countStatusBelum($request->no_rawat,$request->jenis_rm) == 0){
    //                     $signed_status = 'SUDAH';
    //                 }
    //                 $jumlahFileRM = ManajemenTTE::where('no_rawat', '=', $request->no_rawat)->where('jenis_rm', '=', $request->jenis_rm)->get();
    //                 if($jumlahFileRM->count()>0){
    //                     $tte = ManajemenTTE::where([
    //                         'no_rawat' => $request->no_rawat,
    //                         'path' => $nama_file,
    //                         ])->update([
    //                             'tanggal_signed' => Carbon::now()->format('Y/m/d H:i:s'),
    //                             'path' => $target_file,
    //                             'signed_status' => $signed_status,
    //                         ]);
    //                 } else {
    //                     $tte = ManajemenSurat::where([
    //                         'no_rawat' => $request->no_rawat,
    //                         'path' => $nama_file,
    //                         ])->update([
    //                             'tanggal_signed' => Carbon::now()->format('Y/m/d H:i:s'),
    //                             'path' => $target_file,
    //                             'signed_status' => $signed_status,
    //                         ]);
    //                 }
    //             }catch(Exception $e){
    //                 $status_tte = StatusTTEPPA::where([
    //                     'no_rawat' => $request->no_rawat,
    //                     'jenis_rm' => $request->jenis_rm,
    //                     'nip' => Auth::user()->username,
    //                     ])->update([
    //                         'status' => 'BELUM',
    //                     ]);
    //                 $tte_log = TTELog::create([
    //                                     'user' => Auth::user()->pegawai->nik,
    //                                     'created_at' => Carbon::now()->format('Y/m/d H:i:s'),
    //                                     'message' => 'Update Database Gagal..!!',
    //                                 ]);
    //                 return response()->json(['msg' => 'Update Database Gagal..!! '.$e], 400);
    //             }

                
    //             /* remove file RM yang sebelumnya untuk menghemat storage */
    //             unlink(storage_path('app/rekam-medis/' . $nama_file));
    //             return response()->json(['msg' => 'Proses Berhasil..!!!', ], 200);
    //         }
                
    //     }catch(RequestException $err){
    //         $errMsg="Error: " . $err->getMessage();
    //         if ($err->hasResponse()) {
    //             dd( $err->getResponse()->getStatusCode());
    //             echo "\nHTTP Status Code: " . $err->getResponse()->getStatusCode();
    //         }
    //         $tte_log = TTELog::create([
    //             'user' => Auth::user()->pegawai->nik,
    //             'created_at' => Carbon::now()->format('Y/m/d H:i:s'),
    //             'message' => 'Pengiriman data gagal..!!' . $err,
    //         ]);
    //         unlink(storage_path('app/rekam-medis/' . $target_file));
    //         return response()->json(['msg' => 'Pengiriman data gagal..!!'], 400);
    //     }
    // }

    public function manageBerkas(Request $request){

        if($request->type == "hapus"){
            $passphrase = $request->passphrase;
            $no_rawat = $request->no_rawat;
            $nama_file = $request->nama_file;
            $jenis_rm = $request->jenis_rm;
    
            if(!file_exists($this->storage_location . '/' . $request->jenis_rm . '/' . $nama_file)){
                return response()->json(['msg' => 'File tidak ditemukan..!!'], 400);
            }
    
            try{
                if(unlink(storage_path('app/rekam-medis/' . $nama_file))){
                    $tte = ManajemenTTE::where([
                        'no_rawat' => $request->no_rawat,
                        'path' => $nama_file,
                        ])->delete();
            
                    $tte = StatusTTEPPA::where([
                        'no_rawat' => $request->no_rawat,
                        'jenis_rm' => $jenis_rm,
                        ])->delete();
            
                    $dataKetTTE = KeteranganTTE::where('no_rawat', '=', $request->no_rawat)->where('jenis_rm', '=', $request->jenis_rm)->where('tgl_signed', '<>', '0000-00-00 00:00:00')->get();
                    if($dataKetTTE->count()>0){
                        foreach($dataKetTTE as $data){
                            unlink(storage_path('app/qr-code/' . $data->id . '.png'));
                        }
                    }
                    $tte = KeteranganTTE::where([
                        'no_rawat' => $request->no_rawat,
                        'jenis_rm' => $jenis_rm,
                        ])->delete();
        
                    return response()->json(['msg' => 'Proses Berhasil..!!!', ], 200);
                }else{
                    return response()->json(['msg' => 'Proses hapus gagal..!!'], 400);
                }
            }catch(RequestException $err){   
                $errMsg="Error: " . $err->getMessage();
                return response()->json(['msg' => $errMsg], 400);
            }
        }
    }
}

