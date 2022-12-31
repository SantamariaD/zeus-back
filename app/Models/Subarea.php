<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subarea extends Model
{
    use HasFactory;

    protected $table = 'subareas';
    protected $fillable = [ 
        'id',
        'id_area_conocimiento', 
        'subarea',
    ];
}
