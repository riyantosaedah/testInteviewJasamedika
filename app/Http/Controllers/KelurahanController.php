<?php

namespace App\Http\Controllers;

use App\Models\Kelurahan;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class KelurahanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sources = Kelurahan::query();
        if (request()->ajax()) {
            return datatables($sources)
            ->addColumn('action', function($data){
                return view('kelurahan.action', compact('data'));
            })->make();
        }
        return view('kelurahan.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = "Tambah Kelurahan";
        return view('kelurahan.form',compact('title'));
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
                'nama_kelurahan' => 'bail|required|string',
                'nama_kecamatan' => 'bail|required|string',
                'kota' => 'bail|required|string',
            ]);

            if ($validator->fails()) {
                throw new Exception($validator->errors()->first(), 1);
            }

            if ($id) {
                $fields = Kelurahan::find($id);
            } else {
                $fields = new Kelurahan();
            }

            DB::beginTransaction();

            $fields->created_by = Auth::user()->email;
            $fields->created_at = date('Y-m-d H:i:s');
            $fields->updated_by = Auth::user()->email;
            $fields->updated_at = date('Y-m-d H:i:s');
            $fields->nama_kelurahan = $request->nama_kelurahan;
            $fields->nama_kecamatan = $request->nama_kecamatan;
            $fields->nama_kota = $request->kota;
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $title = "Ubah Kelurahan";
        $data = Kelurahan::find($id);
        return view('kelurahan.form',compact('title','data'));
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

            $source = Kelurahan::find($id);
            $source->delete();
            return back()->with(['status'=>'success','msg'=>"data berhasil dihapus"]);
        } catch (\Throwable $th) {
            report($th);
            // return back()->with(['status'=>'danger','msg'=>$th->getMessage()]);
            return back()->with(['status'=>'danger','msg'=>'hapus gagal, coba lagi.']);
        }
    }
}
