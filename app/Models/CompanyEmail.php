<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyEmail extends Model
{
    use HasFactory;

    public function getFromAttribute($value)
    {
        return json_decode($value);
    }

    public function getToAttribute($value)
    {
        return json_decode($value);
    }
}
