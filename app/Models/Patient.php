<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    public function suivis()
    {
        return $this->hasMany(Suivi::class);
    }

    public function services()
    {
        return $this->hasMany(Service::class);
    }
}