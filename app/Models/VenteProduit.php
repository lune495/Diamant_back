<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VenteProduit extends Model
{
    use HasFactory;

    public  function vente()
    {
        return $this->belongsTo(Vente::class);
    }

    public  function produit()
    {
        return $this->belongsTo(Produit::class);
    }

    public static function getTotal($id)
    {
        $item = VenteProduit::find($id);
        // $vente = Vente::find($item->id);
        $produit = Produit::find($item->produit_id);
        $total = $item->qte * $produit->pv;

        return $total;
    }        
}
