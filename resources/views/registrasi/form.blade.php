@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
        <h3>{{$title}}</h3>
        <div style="height: 5px"></div>
        <div class="col-md-12">
            <form id="form" method="post">
                <input type="hidden" name="id" value="{{$data->oid??''}}">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="exampleFormControlInput1" class="form-label">Nama Lengkap</label>
                            <input value="{{$data->nama_pasien??''}}" type="text" class="form-control" name="nama_pasien" autofocus>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="exampleFormControlInput1" class="form-label">Tanggal Lahir</label>
                            <input value="{{$data->tgl_lahir??''}}" type="date" class="form-control" name="tgl_lahir">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="exampleFormControlInput1" class="form-label">Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="form-control">
                                <option @if ('L' == ($data->jenis_kelamin??''))
                                    selected
                                @endif value="L">Laki-laki</option>
                                <option @if ('P' == ($data->jenis_kelamin??''))
                                    selected
                                @endif value="P">Perempuan</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="exampleFormControlInput1" class="form-label">No Telp</label>
                            <input value="{{$data->no_telp??''}}" type="text" class="form-control" name="no_telp" autofocus>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="exampleFormControlInput1" class="form-label">Alamat</label>
                            <input value="{{$data->alamat??''}}" type="text" class="form-control" name="alamat" autofocus>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="exampleFormControlInput1" class="form-label">Kelurahan</label>
                            <select name="kelurahan" class="form-control">
                                @foreach ($kelurahan as $item)
                                <option @if ($item->id == ($data->id_kelurahan??''))
                                    selected
                                @endif value="{{$item->id}}">{{$item->nama_kelurahan}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="exampleFormControlInput1" class="form-label">RT</label>
                            <input value="{{$data->rt??''}}" type="text" class="form-control" name="rt" autofocus>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="exampleFormControlInput1" class="form-label">RW</label>
                            <input value="{{$data->rw??''}}" type="text" class="form-control" name="rw" autofocus>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <a href="{{ route('home') }}" class="btn btn-secondary">Kembali</a>
                    <button type="submit" class="btn btn-primary btn-submit">Simpan</button>
                </div>
            </form>

        </div>
    </div>
</div>
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $(".btn-submit").click(function(e){
        e.preventDefault();
        var datastring = $("#form").serialize();
        console.log(datastring);
        $.ajax({
            type:'POST',
            dataType: 'json',
            url:"{{ route('registrasi.store') }}",
            data:datastring,
            success:function(data){
                if(data.status=="success") {
                    window.location.href = '{{route('home')}}';
                } else {
                    console.log(data.msg);
                    alert(data.msg);
                }
            },
            error: function(xhr, status, error) {
                alert(xhr.responseText);
            }
        });

    });
</script>
@endsection
