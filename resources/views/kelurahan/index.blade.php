@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <h3>Kelurahan</h3>
        <div class="col-md-12 text-right">
            <a href="{{ route('kelurahan.create') }}" class="btn btn-primary">Tambah</a>
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
            <table id="dataTable" class="table table-striped table-bordered compact" style="width:100%">
                <thead>
                    <tr>
                        <th>Kelurahan</th>
                        <th>Kecamatan</th>
                        <th>Kota</th>
                        <th>aksi</th>
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
            serverSide: true,
            ajax: "{{ route('kelurahan.index') }}",
            columns: [
                {data: 'nama_kelurahan', name: 'nama_kelurahan'},
                {data: 'nama_kecamatan', name: 'nama_kecamatan'},
                {data: 'nama_kota', name: 'nama_kota'},
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
