<?php

namespace App\Http\Controllers;

use App\Models\Kelurahan;
use App\Models\Pasien;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PDF;

class RegistrasiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sources = Pasien::join('kelurahan','kelurahan.id','=','pasien.id_kelurahan')
            ->select('pasien.*','kelurahan.nama_kelurahan','kelurahan.nama_kecamatan','kelurahan.nama_kota');
        if (request()->ajax()) {
            return datatables($sources)
            ->addColumn('action', function($data){
                return view('registrasi.action',compact('data'));
            })->make();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = "Tambah Registrasi";
        $kelurahan = Kelurahan::orderBy('nama_kelurahan')->get();
        return view('registrasi.form',compact('title','kelurahan'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $id = $request->id??'';

        try {
            $validator = Validator::make($request->all(),[
                'nama_pasien' => 'bail|required|string',
                'tgl_lahir' => 'bail|required|date',
                'jenis_kelamin' => 'bail|required|in:L,P',
                'no_telp' => 'bail|required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
                'alamat' => 'bail|required|string',
                'kelurahan' => 'bail|required|integer|exists:kelurahan,id',
                'rt' => 'bail|required|regex:/^([0-9\s\-\+\(\)]*)$/|max:4',
                'rw' => 'bail|required|regex:/^([0-9\s\-\+\(\)]*)$/|max:4',
            ]);

            if ($validator->fails()) {
                throw new Exception($validator->errors()->first(), 1);
            }

            DB::beginTransaction();
            $tanggal = date('Y-m-d');

            if ($id) {
                $fields = Pasien::find($id);
            } else {
                $fields = new Pasien();
                $fields->oid = $this->get_oid();
                $fields->kode = $this->GenerateKode($tanggal);
            }

            $fields->created_by = Auth::user()->email;
            $fields->created_at = date('Y-m-d H:i:s');
            $fields->updated_by = Auth::user()->email;
            $fields->updated_at = date('Y-m-d H:i:s');
            $fields->nama_pasien = $request->nama_pasien;
            $fields->alamat = $request->alamat;
            $fields->no_telp = $request->no_telp;
            $fields->rw = $request->rw;
            $fields->rt = $request->rt;
            $fields->id_kelurahan = $request->kelurahan;
            $fields->jenis_kelamin = $request->jenis_kelamin;
            $fields->tgl_lahir = $request->tgl_lahir;

            $fields->save();

            DB::commit();
            return response()->json(['status'=>'success','msg'=>"data berhasil disimpan"]);

        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status'=>'error','msg'=>$th->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Pasien::where('oid',$id)
            ->join('kelurahan','kelurahan.id','=','pasien.id_kelurahan')
            ->select('pasien.*','kelurahan.nama_kelurahan','kelurahan.nama_kecamatan','kelurahan.nama_kota')
            ->first();
        $result = [
            'id' => $data->kode,
            'nama_pasien' => $data->nama_pasien,
            'tanggal_lahir' => date('d-m-Y', strtotime($data->tgl_lahir)),
            'jenis_kelamin' => ($data->jenis_kelamin=='L')?'Laki-laki':'Perempuan',
            'alamat' => $data->alamat . ' Kel. ' . $data->nama_kelurahan . ' Kec. ' . $data->nama_kecamatan . ', ' . $data->nama_kota. ' .'
        ];
        $pdf = PDF::loadView('kartu_pasien_pdf', $result)->setPaper('A6','landscape');
        return $pdf->stream();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $title = "Ubah Registrasi";
        $data = Pasien::find($id);
        $kelurahan = Kelurahan::orderBy('nama_kelurahan')->get();
        return view('registrasi.form',compact('title','kelurahan','data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $source = Pasien::find($id);
            $source->delete();
            return back()->with(['status'=>'success','msg'=>"data berhasil dihapus"]);
        } catch (\Throwable $th) {
            report($th);
            // return back()->with(['status'=>'danger','msg'=>$th->getMessage()]);
            return back()->with(['status'=>'danger','msg'=>'hapus gagal, coba lagi.']);
        }
    }

    public function get_oid($include_braces=false)
    {
        if (function_exists('com_create_guid')) {
            if ($include_braces === true) {
                return com_create_guid();
            } else {
                return substr(com_create_guid(), 1, 36);
            }
        } else {
            mt_srand((double) microtime() * 10000);
            $charid = strtoupper(md5(uniqid(rand(), true)));

            $guid = substr($charid,  0, 8) . '-' .
                substr($charid,  8, 4) . '-' .
                substr($charid, 12, 4) . '-' .
                substr($charid, 16, 4) . '-' .
                substr($charid, 20, 12);

            if ($include_braces) {
                $guid = '{' . $guid . '}';
            }

            return $guid;
        }
    }

    function buatkode($id,$jumlah_karakter = 0)
    {
        $id_nol = str_pad($id, $jumlah_karakter, "0", STR_PAD_LEFT);
        return $id_nol;
    }

    function GenerateKode($par_tanggal)
    {
        $tanggal = date('dmY', strtotime($par_tanggal));
        $tahun = substr($tanggal,6,2);
        $bulan = substr($tanggal,2,2);
        $no_urut_format="";

        $data = DB::select(
            DB::raw("SELECT coalesce(max(cast(substring(kode,5,5) as INTEGER)),0) + 1 as no_urut
                            from pasien
                            where substring(kode,1,2) = '$tahun'
                            and substring(kode,3,2) = '$bulan'
                            and length(kode)=9 limit 1"));
        foreach ($data as $key => $value) {
            $_no_urut = $value->no_urut;
            $no_urut_format = $this->buatkode($_no_urut,5);
        }
        return "$tahun$bulan$no_urut_format";
    }

}
