<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rangking extends Model
{
    use HasFactory;

    protected $table = 'rangking';
    protected $primaryKey = 'id_rangking';
    protected $fillable = ['keterangan', 'id_alternatif', 'total_nilai'];

    public function alternatif()
    {
        return $this->belongsTo(Alternatif::class, 'id_alternatif');
    }
}
