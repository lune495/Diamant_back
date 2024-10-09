<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    use HasFactory;
    public function vente_produits()
    {
        return $this->hasMany(VenteProduit::class);
    }
}
