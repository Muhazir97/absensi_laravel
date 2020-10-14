<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Absen;
use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id = Auth::user()->id;
        date_default_timezone_set('Asia/Jakarta');
        $date    = date("Y-m-d");
        $time    = date("H:i:s");
        
        //jika waktu jam 10:00 maka button masuk dan pulang non aktif
        if($time >= '10:00:00'){
            $info = array(
                    "btnIn"  => "disabled",
                    "btnOut" => "disabled");

            //jika waktu jam 17:00 maka button masuk non aktif dan button pulang aktif sampai jam 24:00
            if ($time >= '17:00:00') {
                $info = array(
                    "btnIn"  => "disabled",
                    "btnOut" => "");
            }

        //jika waktu jam 01:00 maka button masuk aktif dan button pulang non aktif sampai jam 00:00
        }else{
            $info = array(
                    "btnIn"  => "",
                    "btnOut" => "disabled");
        }

        // $cek_absen = Absen::where(['user_id' => $user_id, 'date' => $date])
        //                    ->get()
        //                    ->first();
        // if(is_null($cek_absen)){
        //     $info = array(
        //             "btnIn" => "",
        //             "btnOut" => "disabled");
        // }elseif($cek_absen->time_out == null){
        //     $info = array(
        //             "btnIn" => "disabled",
        //             "btnOut" => "");
        // }else{
        //     $info = array(
        //             "btnIn" => "disabled",
        //             "btnOut" => "disabled");
        // }
        if (!empty(request()->search)) {
            $data_absen = Absen::where('user_id', $user_id)
                          ->where('nama', 'LIKE', "%".request()->search."%")
                          ->orderBy('date', 'desc')
                          ->paginate();
            $hitung_gaji = Absen::where('nama', request()->search )
                          ->sum('gaji');
        }else{
            $data_absen = Absen::where('user_id', $user_id)
                          ->orderBy('date', 'desc')
                          ->paginate();
            $hitung_gaji = Absen::where('user_id', $user_id)
                          ->sum('gaji');
        }

        return view('home', compact('data_absen', 'hitung_gaji', 'info'));
    }

    public function absen(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $user_id = Auth::user()->id;
        $nama = Auth::user()->nama;
        $date    = date("Y-m-d");
        $time    = date("H:i:s");
        $nama    = $request->nama;
        $note    = $request->note;
        $gaji    = $request->gaji;

        // //button absen masuk
           $absen = new Absen;
        if(isset($request["btnIn"])) {

            //cek masuk dua kali
            // $cek_double = $absen->where(['date' => $date, 'nama' => $nama])
            //                     ->count();
            $cek_double = Absen::where(['nama' => $nama, 'date' => $date])
                           ->get()
                           ->first();
            if($cek_double == null){
                $absen->create([
                 'user_id' => $user_id,
                 'date'    => $date,
                 'nama'    => $nama,
                 'time_in' => $time,
                 'note'    => $note,
                 'gaji'    => $gaji
                 ]);

              return redirect()->back()->with('status', 'Absen Masuk Anda Berhasil &nbsp; '.'<b>'.$request->nama.'</b>');
            }
              return redirect()->back()->with('sudah', 'Anda Sudah Absen Masuk &nbsp; '.'<b>'.$cek_double->nama.'</b>');
           

        }elseif(isset($request["btnOut"])) {
             
             //cek pulang dua kali
             $cek_double = Absen::where(['nama' => $nama, 'date' => $date])
                           ->get()
                           ->first();
            if($cek_double->time_out == null){
                $absen->where(['date' => $date, 'nama' => $nama])
                  ->update([
                             'time_out' => $time,
                             // 'note'     => $note,
                             // 'gaji'     => $gaji
                             ]);
             return redirect()->back()->with('status', 'Absen Pulang anda berhasil &nbsp; '.'<b>'.$request->nama.'</b>');
            }
             return redirect()->back()->with('sudah', 'Anda Sudah Absen Pulang &nbsp; '.'<b>'.$cek_double->nama.'</b>');
            
        }

        return $request->all();
    }
}
