<?php

namespace App\Http\Controllers;

use App\Models\ManajemenTTE;
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



class APITTEController extends Controller
{
    private $baseurl_api;
    private $storage_location;
    private $client;
    protected $statusTTE;

    public function __construct()
    {
      $this->baseurl_api = config('app.baseurl_api');
      $this->storage_location = storage_path('app/rekam-medis/');//'../storage/app/rekam-medis/';
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
        $passphrase =$request->passphrase;
        // $passphrase ='Hantek1234.!';
        $location = '1';
        $nama_file = $request->nama_file;
        $target_file = Str::substr($nama_file , 0 , Str::of($nama_file)->length()-4) . '_.pdf';

        /*
        Check apakah file ada di dalam storage
        */ 
        if(!file_exists($this->storage_location . $nama_file)){
            return response()->json(['msg' => 'File tidak ditemukan..!!'], 400);
        }

        $url = $this->baseurl_api . '/api/sign/pdf';

        // $headers = [
        //     'Authorization' => 'Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyX2lkIjoxLCJlbWFpbCI6ImFkbWluQHR0ZS5jb20iLCJuaWsiOiIwODAzMjAyMTAwMDA3MDYyIiwiZXhwIjoxNzI5OTgyMjczfQ.ZFCzzT4DP_d6OodzysZlUOt_VLX-ZOt2Y860yZBpJlw'
        // ];
        $resource = fopen($this->storage_location . $target_file, 'w');

        try{
            $response = $this->client->request('POST', $url, [
                'multipart' => [
                    [
                        'name' => 'file',
                        'contents' => Psr7\Utils::tryFopen($this->storage_location . $nama_file, 'r'),
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
                'verify'  => false,
                'http_errors' => false,
                'sink' => $resource,
            ]);

            // dd($response);
            // echo $response->getBody();
            if ($response->getStatusCode() == 200) {
            // if ($passphrase != 'Hantek1234.!') {
            //     $tte_log = TTELog::create([
            //         'user' => Auth::user()->pegawai->nik,
            //         'created_at' => Carbon::now()->format('Y/m/d H:i:s'),
            //         'message' => 'Proses TTE gagal..!!',
            //     ]);
            //     // unlink(storage_path('app/rekam-medis/' . $target_file));
            //     return response()->json(['msg' => 'Passphrase anda salah'], 400);

            // } else if ($response->getStatusCode() == 200) {

                //update status untuk PPA yang melakukan tanda tangan
                $status_tte = StatusTTEPPA::where([
                    'no_rawat' => $request->no_rawat,
                    'nip' => Auth::user()->pegawai->nik,
                    ])->update([
                        'status' => 'SUDAH',
                    ]);

                try{
                    //cek apakah semua PPA sudah melakukan tanda tangan 
                    $signed_status = 'BELUM';
                    if($this->statusTTE->countStatusBelum($request->no_rawat) == 0){
                        $signed_status = 'SUDAH';
                    }
                    $tte = ManajemenTTE::where([
                        'no_rawat' => $request->no_rawat,
                        'path' => $nama_file,
                        ])->update([
                            'tanggal_signed' => Carbon::now()->format('Y/m/d H:i:s'),
                            'path' => $target_file,
                            'signed_status' => $signed_status,
                        ]);
                }catch(Exception $e){
                    $status_tte = StatusTTEPPA::where([
                        'no_rawat' => $request->no_rawat,
                        'nip' => Auth::user()->username,
                        ])->update([
                            'status' => 'BELUM',
                        ]);
                    $tte_log = TTELog::create([
                                        'user' => Auth::user()->pegawai->nik,
                                        'created_at' => Carbon::now()->format('Y/m/d H:i:s'),
                                        'message' => 'Update Database Gagal..!!',
                                    ]);
                    return response()->json(['msg' => 'Update Database Gagal..!! '.$e], 400);
                }

                /* remove file RM yang sebelumnya untuk menghemat storage */
                unlink(storage_path('app/rekam-medis/' . $nama_file));

                return response()->json(['msg' => 'Proses Berhasil..!!!', ], 200);
            }else{
                $tte_log = TTELog::create([
                    'user' => Auth::user()->pegawai->nik,
                    'created_at' => Carbon::now()->format('Y/m/d H:i:s'),
                    'message' => 'Proses TTE gagal..!!',
                ]);
                unlink(storage_path('app/rekam-medis/' . $target_file));
                return response()->json(['msg' => 'Proses TTE gagal..!!'], 400);
            }
        
        }catch(Exception $err){
            $tte_log = TTELog::create([
                'user' => Auth::user()->pegawai->nik,
                'created_at' => Carbon::now()->format('Y/m/d H:i:s'),
                'message' => 'Pengiriman data gagal..!!' . $err,
            ]);
            unlink(storage_path('app/rekam-medis/' . $target_file));
            return response()->json(['msg' => 'Proses TTE gagal..!!'], 400);
        }
        

    }
}

