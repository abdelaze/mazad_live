<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Onboaring extends Model
{
    use HasFactory, SoftDeletes, HasTranslations;
    
    protected $table      =  'onboarings';
    protected $guarded    =  ['id'];
    public $translatable =   ['title','content'];

    public function getImageAttribute($value)
    {
        return url('public/uploads/onboardings/'.$value);
    }
}
