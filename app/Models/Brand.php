<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Brand extends Model
{
    use HasFactory, SoftDeletes, HasTranslations;
    
    protected $table      =  'brands';
    protected $guarded    =  ['id'];
    public $translatable =   ['name'];

    public function getImageAttribute($value)
    {
        return !empty($value) ? url('public/uploads/brands/'.$value) : NULL;
    }

    public function category() {
        return  $this->belongsTo(Category::class);
    }

    public function sub_category() {
        return  $this->belongsTo(SubCategory::class);
    }
    
}
