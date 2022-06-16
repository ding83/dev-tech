<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Companies extends Model
{
    use HasFactory;

    public function jobs()
    {
        return $this->hasMany(Job::class, 'company_id');
    }
}
