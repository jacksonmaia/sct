<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendedor extends Model
{
    protected $fillable = [
        'nome', 'tipo', 'taxa',
    ];
}
