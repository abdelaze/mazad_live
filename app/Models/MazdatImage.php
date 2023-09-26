<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MazdatImage extends Model
{
    use HasFactory;
    protected $table      =  'mazdat_images';
    protected $guarded    =  ['id'];

    public function getImageAttribute($value)
    {
        return url('public/uploads/mazdats/'.$value);
    }
}
