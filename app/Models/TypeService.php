<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeService extends Model
{
    use HasFactory;

    public  function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function element_services()
    {
        return $this->hasMany(ElementService::class);
    }

}
