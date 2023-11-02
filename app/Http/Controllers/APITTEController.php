<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Config;
use Illuminate\Support\Facades\Storage;


class APITTEController extends Controller
{
    private $baseurl_api;
    private $storage_location;
    private $client;

    public function __construct()
    {
      $this->baseurl_api = config('app.baseurl_api');
      $this->storage_location = '../storage/app/rekam-medis/';
      $this->client = new Client();
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
        
        
    //     // $rules = [
    //     //     'passphrase' => 'required',
    //     // ];
    
    //     // $customMessages = [
    //     //     'required' => 'Passphrase is required'
    //     // ];
    
    //     // $this->validate($request, $rules, $customMessages);
    
        // $nik = '0803202100007062';
        $nik = Auth::user()->pegawai->no_ktp;
        $passphrase =$request->passphrase;
        // $passphrase ='Hantek1234.!';
        $location = '3';
        $nama_file = 'contoh-file.pdf';
        $target_file = Str::substr($nama_file , 0 , Str::of($nama_file)->length()-4) . '_TTE.pdf';

        $url = $this->baseurl_api . '/tte/api/sign/pdf';

        $headers = [
            'Authorization' => 'Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyX2lkIjoxLCJlbWFpbCI6ImFkbWluQHR0ZS5jb20iLCJuaWsiOiIwODAzMjAyMTAwMDA3MDYyIiwiZXhwIjoxNzI5OTgyMjczfQ.ZFCzzT4DP_d6OodzysZlUOt_VLX-ZOt2Y860yZBpJlw'
        ];
        $resource = fopen($this->storage_location . $target_file, 'w');

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
                    'contents' => 'is this a fantasy..??'
                ]
            ],
            'headers' => $headers,
            'verify'  => false,
            'http_errors' => false,
            'sink' => $resource,
        ]);

        // echo $response->getBody();
        if ($response->getStatusCode() == 200) {
            return response()->json(['success' => 'hehehe', ], 200);
                //perform your action with $response 
        }else{
            unlink(storage_path('app/rekam-medis/' . $target_file));
            $responseBody = json_decode($response->getBody());
            return response()->json(['error' => $responseBody->message], 400);
        }

    }
}

