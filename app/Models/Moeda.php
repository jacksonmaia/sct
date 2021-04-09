<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Moeda extends Model
{
    protected $fillable = [
        'sigla', 'nome', 'margem'
    ];
}
