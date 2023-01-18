<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Nota;
use App\Models\NotaSubtema;

class Subtitulos extends Model
{
    use HasFactory;

    protected $table = 'subtitulos';
    protected $fillable = [
        'id',
        'nota_id', 
        'id_subtema',
        'subtitulo',
        'html',
        'numeroSubtitulo',
        'created_at', 
        'updated_at',
    ];

    public function nota() {
        return $this->belongsTo(Nota::class);
    }

    public function notaSubtema() {
        return $this->belongsTo(NotaSubtema::class);
    }
}
