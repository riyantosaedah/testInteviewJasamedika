@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <h3>Registrasi Pasien</h3>
        <div class="col-md-12 text-right">
            <a href="{{ route('registrasi.create') }}" class="btn btn-primary">Tambah</a>
        </div>
        <div style="height: 5px"></div>
        <div class="col-md-12">
            @if(session()->has('status'))
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-{{session()->get('status')}}" id='notifikasi_proses'>
                        <div class="close pull-right" onclick="$('#notifikasi_proses').hide()">x</div>
                        {!! session()->get('msg') !!}
                    </div>
                </div>
            </div>
            @endif
            <table id="dataTable" class="table table-striped table-bordered nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Lengkap</th>
                        <th>Tanggal Lahir</th>
                        <th>Jenis Kelamin</th>
                        <th>Alamat Lengkap</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#dataTable').DataTable({
            processing: true,
            responsive: true,
            serverSide: true,
            ajax: "{{ route('registrasi.index') }}",
            columns: [
                {data: 'kode', name: 'kode'},
                {data: 'nama_pasien', name: 'nama_pasien'},
                {data: 'tgl_lahir', name: 'tgl_lahir'},
                {data: 'jenis_kelamin', name: 'jenis_kelamin',
                    render: function ( data, type, row ) {
                        return (data == 'L')?'Laki-laki':'Perempuan';
                    }},
                {data: 'alamat', name: 'alamat',
                    render: function ( data, type, row ) {
                        return data + ' Kel. ' + row.nama_kelurahan + ' Kec. ' + row.nama_kecamatan + ', ' +
                                row.nama_kota + '.';
                    }},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ],
            order:[
                // [5, 'asc'],
                [0,'asc'],
            ],
        });

    });
</script>
@endsection
