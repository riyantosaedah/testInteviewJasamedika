<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pasien extends Model
{
    use HasFactory;

    protected $table = "pasien";
    protected $primaryKey = "oid";
    protected $keyType = "string";
    public $timestamps = false;
    public $incrementing = false;
    protected $fillable = [
        'oid',
        'id',
        'kode',
        'created_by',
        'created_at',
        'updated_by',
        'updated_at',
        'nama_pasien',
        'alamat',
        'no_telp',
        'rt',
        'rw',
        'id_kelurahan',
        'tgl_lahir',
        'jenis_kelamin',
    ];

    public function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
