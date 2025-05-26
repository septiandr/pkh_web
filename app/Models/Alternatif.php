<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alternatif extends Model
{
    use HasFactory;

    protected $table = 'alternatif';
    protected $primaryKey = 'id_alternatif';

    protected $fillable = [
        'nik',
        'nama_lengkap',
        'alamat',
        'dokumen',
        'eligible',
        'pendapatan',
        'luas_tanah',
    ];

    public function penilaians()
    {
        return $this->hasMany(Penilaian::class, 'id_alternatif');
    }

    public function rangking()
    {
        return $this->hasOne(Rangking::class, 'id_alternatif');
    }
}
