<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Taxe extends Model
{
    use HasFactory;
    
    public  function ventes()
    {
        return $this->hasMany(Vente::class);
    }
}
