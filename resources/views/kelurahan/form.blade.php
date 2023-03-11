@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
        <h3>{{$title}}</h3>
        <div style="height: 5px"></div>
        <div class="col-md-6">

            <form id="form" method="post">
                <input type="hidden" name="id" value="{{$data->id??''}}">
                <div class="mb-3">
                    <label for="exampleFormControlInput1" class="form-label">Kelurahan</label>
                    <input value="{{$data->nama_kelurahan??''}}" type="text" class="form-control" name="nama_kelurahan" autofocus>
                </div>
                <div class="mb-3">
                    <label for="exampleFormControlInput1" class="form-label">Kecamatan</label>
                    <input value="{{$data->nama_kecamatan??''}}" type="text" class="form-control" name="nama_kecamatan">
                </div>
                <div class="mb-3">
                    <label for="exampleFormControlInput1" class="form-label">Kota</label>
                    <input value="{{$data->nama_kota??''}}" type="text" class="form-control" name="kota">
                </div>
                <div class="mb-3">
                    <a href="{{ route('kelurahan.index') }}" class="btn btn-secondary">Kembali</a>
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
            url:"{{ route('kelurahan.store') }}",
            data:datastring,
            success:function(data){
                if(data.status=="success") {
                    window.location.href = '{{route('kelurahan.index')}}';
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
