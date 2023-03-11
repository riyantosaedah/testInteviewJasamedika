<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelurahan extends Model
{
    use HasFactory;

    protected $table = "kelurahan";
    public $timestamps = false;
    protected $fillable = [
        'id',
        'created_by',
        'created_at',
        'updated_by',
        'updated_at',
        'nama_kelurahan',
        'nama_kecamatan',
        'nama_kota',
    ];

    public function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
