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
        'id_user', 
        'id_area_conocimiento', 
        'tema', 
        'identificador',
        'created_at', 
        'updated_at',
    ];

    public function notaSubtemas() {
        return $this->hasMany(NotaSubtema::class);
    }

    public function subtitulos() {
        return $this->hasMany(Subtitulos::class);
    }
}
