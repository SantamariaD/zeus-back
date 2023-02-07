<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\NotaSubtema;
use App\Models\Subtitulos;

class Nota extends Model
{
    use HasFactory;

    protected $table = 'notas';
    protected $fillable = [
        'user_id', 
        'area_id', 
        'subarea_id',
        'tema', 
        'uuid',
        'imagen', 
    ];

    public function notaSubtemas() {
        return $this->hasMany(NotaSubtema::class);
    }

    public function subtitulos() {
        return $this->hasMany(Subtitulos::class);
    }
}
