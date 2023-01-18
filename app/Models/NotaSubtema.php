<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Nota;
use App\Models\Subtitulos;

class NotaSubtema extends Model
{
    use HasFactory;

    protected $table = 'subtemas';
    protected $fillable = [
        'id', 
        'nota_id', 
        'uuid',
        'subtema', 
        'numeroSubtema',
    ];

    public function nota() {
        return $this->belongsTo(Nota::class);
    }

    public function subtitulos()
    {
        return $this->hasMany(Subtitulos::class);
    }
}
