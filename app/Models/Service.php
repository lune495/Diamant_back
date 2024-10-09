<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    public function element_services()
    {
        return $this->hasMany(ElementService::class);
    }

    public  function user()
    {
        return $this->belongsTo(User::class);
    }

    public  function medecin()
    {
        return $this->belongsTo(Medecin::class);
    }

    public  function module()
    {
        return $this->belongsTo(Module::class);
    }
}