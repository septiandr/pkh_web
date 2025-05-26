<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kriteria extends Model
{
    use HasFactory;

    protected $table = 'kriteria';
    protected $primaryKey = 'id_kriteria';
    protected $fillable = ['nama_kriteria', 'bobot'];

    public function subKriteria()
    {
        return $this->hasMany(SubKriteria::class, 'id_kriteria');
    }

    public function penilaian()
    {
        return $this->hasMany(Penilaian::class, 'id_kriteria');
    }

    public function rangking()
    {
        return $this->hasMany(Rangking::class, 'id_kriteria');
    }
}

