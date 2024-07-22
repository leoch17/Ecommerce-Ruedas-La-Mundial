<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tire extends Model
{
    use HasFactory;

    public function tire() {
        return $this->hasMany(Tire::class);
    }
}
