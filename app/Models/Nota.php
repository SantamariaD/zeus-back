<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nota extends Model
{
    use HasFactory;

    protected $table = 'notas';
    protected $fillable = [
        'id_user', 
        'id_area_conocimiento', 
        'tema', 
        'created_at', 
        'updated_at',
    ];
}
