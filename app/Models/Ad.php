<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ad extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = ['id'];
    public function getImageAttribute($value)
    {
        return url('public/uploads/ads/'.$value);
    }
}
