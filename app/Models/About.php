<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class About extends Model
{
    use HasFactory, HasTranslations;
    protected $table      =  'abouts';
    protected $guarded    =  ['id'];
    public $translatable  =   ['detail'];
}
