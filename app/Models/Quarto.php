<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quarto extends Model
{
    protected $fillable = [
        'tipo', 'valor', 'hotel_id'
    ];
}
