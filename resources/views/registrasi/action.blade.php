<a target="_blank" href="{{ route('registrasi.show', $data->oid) }}" class="btn btn-info btn-sm">cetak kartu</a>
<a href="{{ route('registrasi.edit', $data->oid) }}" class="btn btn-warning btn-sm">edit</a>
<a href="{{ route('registrasi.hapus', $data->oid) }}" class="btn btn-danger btn-sm" onclick="return confirm('hapus data ini?')">hapus</a>
