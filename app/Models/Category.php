<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Category extends Model
{
    use HasFactory, SoftDeletes, HasTranslations;
    
    protected $table      =  'categories';
    protected $guarded    =  ['id'];
    public $translatable =   ['name'];

    public function getImageAttribute($value)
    {
        return url('public/uploads/categories/'.$value);
    }

    public function subcategories() {
        return $this->hasMany(SubCategory::class, 'category_id')->where('status' , 1)->select('id','category_id','name');
    }

    public function brands() {
        return $this->hasMany(Brand::class, 'category_id')->where('status' , 1)->select('id','category_id','name');
    }

    public function attributes() {
        return $this->hasMany(Attribute::class, 'category_id');
    }

    public function mazdats() {
        return $this->hasMany(Mazdat::class, 'category_id');
    }

    // this is a recommended way to declare event handlers
    public static function boot() {
        parent::boot();

        static::deleting(function($category) { // before delete() method call this
             $category->subcategories()->delete();
             $category->mazdats()->delete();
             $category->brands()->delete();
             // do the rest of the cleanup...
        });
    }
}
