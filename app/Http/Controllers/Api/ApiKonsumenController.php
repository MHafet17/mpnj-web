<?php

namespace App\Http\Controllers\api;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Request;
use App\Models\Konsumen;
use App\Models\Alamat;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Hash;
use DB;

class ApiKonsumenController extends Controller
{
    protected $client, $token;

    public function __construct()
    {
        $this->client = new Client();
        $this->token = 'c506cdfc35a33e3d47fb068b799c0630';
    }


    public function profile($id_konsumen)
    {
        $alamat_lain = Alamat::with(['user', 'user.daftar_alamat' ,'user.alamat_fix'])->where('user_id', $id_konsumen)->get();
         $konsumen = Konsumen::where('id_konsumen',$id_konsumen)->first();
        if($konsumen){
             $res ['pesan'] = "Sukses!";
             $hasil['id_konsumen'] = $id_konsumen;
             $hasil['nama_lengkap'] = $konsumen->nama_lengkap;
             $request = $this->client->get('https://api.rajaongkir.com/starter/city?id='.$konsumen->city_id.'',[
                'headers' => [
                    'key' => $this->token
                ]
              ])->getBody()->getContents();
            $kota = json_decode($request,false);
            
            $alamat = array();
            $alamat['alamat'] = $konsumen->alamat;
            $alamat['kota'] = $kota->rajaongkir->results->type.' '.$kota->rajaongkir->results->city_name;
            $alamat['provinsi'] = $kota->rajaongkir->results->province;
            $alamat['kode_pos'] = $konsumen->kode_pos;

            $hasil['alamat_utama']  = $alamat;
            
            $hasil['nomer'] = $konsumen->nomor_hp;
            $hasil['email'] = $konsumen->email;

            
            $result = array();
            foreach ($alamat_lain as $key => $val) {
                // foreach ($val as $val) {
                    foreach ($val->user->daftar_alamat as $row) {
                        // if ($val->user->alamat_utama != $row->id_konsumen) {
                            $alamat_cadangan = array();
                            $alamat_cadangan['id_alamat'] = $row->id_alamat;
                            $alamat_cadangan['alamat'] = $row->alamat_lengkap;
                            $alamat_cadangan['kota'] = $kota->rajaongkir->results->type.' '.$kota->rajaongkir->results->city_name;
                            $alamat_cadangan['provinsi'] = $kota->rajaongkir->results->province;
                            $alamat_cadangan['kode_pos'] = $row->kode_pos;
                            array_push($result,$alamat_cadangan);
                            // }
                        // }
                    }
                }
            
            $hasil['alamat_lain'] = $alamat_cadangan;

            $res['data'] = $hasil;
            return response()->json($res);

        }else{
            return response()->json(['pesan' => 'Login Salah Bro, Santuyy'], 401);
        }
    }

    public function cek_email($email)
    {
      $konsumen = Konsumen::where('email',$email)->first();
        if($konsumen){    
            $res ['pesan'] = "Sukses!";
            $hasil['id_konsumen'] = $konsumen->id_konsumen;
            $res['data'] = $hasil;
            return response()->json($res);

        }else{
            return response()->json(['pesan' => 'Login Salah Bro, Santuyy'], 401);
        }
    }

     public function lupa_password(Request $request, $kosumenId)
    {
        
        $request = Validator::make(Request::all(),[ 
        'password' => 'required',
    ]);

        $konsumen = Konsumen::find($kosumenId);
        $konsumen->password = Hash::make(Request::get('password'));

        if($request->fails()){
            $res ['pesan'] = "gagal";
            $res ['response'] = $request->messages();

            return response()->json($res);
        }else{
            $konsumen->save();
            $res ['data'] = [$konsumen];
            $res2 ['pesan'] = "Sukses!";
            $res2 ['data'] = [$konsumen];
            
            return response()->json($res2);
        }
    }

}
