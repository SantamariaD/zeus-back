<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotaSubtema extends Model
{
    use HasFactory;

    protected $table = 'notas_subtemas';
    protected $fillable = [
        'id', 
        'idNota', 
        'subtema', 
        'base64',
        'html',
    ];
}
