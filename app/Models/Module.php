<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;

    public function type_services()
    {
        return $this->hasMany(TypeService::class);
    }
    public function medecins()
    {
        return $this->hasMany(Medecin::class);
    }

    public function services()
    {
        return $this->hasMany(Service::class);
    }
}
