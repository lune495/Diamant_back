<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ElementService extends Model
{
    use HasFactory;

    public  function service()
    {
        return $this->belongsTo(Service::class);
    }

    public  function type_service()
    {
        return $this->belongsTo(TypeService::class);
    }
}
