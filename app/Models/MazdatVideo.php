<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use File;

class MazdatVideo extends Model
{
    use HasFactory;
    protected $table      =  'mazdat_videos';
    protected $guarded    =  ['id'];

    public function getVideoAttribute($value)
    {
        return url('public/uploads/mazdats/videos/'.$value);
    }

  

}
