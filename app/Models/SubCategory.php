<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubCategory extends Model
{
    use HasFactory, SoftDeletes, HasTranslations;
    
    protected $table      =  'sub_categories';
    protected $guarded    =  ['id'];
    public $translatable =   ['name'];

    public function category() {
        return  $this->belongsTo(Category::class);
    }

    public function brands() {
        return $this->hasMany(Brand::class, 'sub_category_id')->where('status' , 1)->select('id','sub_category_id','name');
    }
   
}
