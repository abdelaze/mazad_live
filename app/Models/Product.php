<?php

namespace App\Models;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table      =  'products';
    protected $guarded    =  ['id'];

    public function category() {
         return $this->belongsTo(Category::class)->select('id','name');
    }

    public function subcategory() {
        return $this->belongsTo(SubCategory::class)->select('id','name');
    }

    public function brand() {
        return $this->belongsTo(Brand::class)->select('id','name');
    }

    public function country() {
        return $this->belongsTo(Country::class)->select('id','name_'.App::getLocale().' as name');
    }

    public function state() {
        return $this->belongsTo(State::class)->select('id','name_'.App::getLocale().' as name');
    }

    public function city() {
        return $this->belongsTo(City::class)->select('id','name_'.App::getLocale().' as name');
    }

    public function user() {
        if(auth()->guard('admin')) {
            return $this->belongsTo(User::class)->select('id','full_name','email' ,'phone_number' , 'photo');
        }else {
            return $this->belongsTo(User::class)->select('id','full_name','email' ,'phone_number' , 'photo')->withCount('is_favorite');
        }
       
    }

    public function images() {
        return $this->hasMany(ProductImage::class);
    }

    public function favorites()
    {
        return $this->hasMany(ProductFavorite::class);
    }

    public function favorite()
    {
        return $this->hasMany(ProductFavorite::class)->where('user_id' , Auth::guard('api')->user()->id);
    }

    public function product_mazad_user()
    {
        return $this->hasMany(ProductMazadUser::class , 'product_id' , 'id')->with('user');
    }

    public function product_selected_user()
    {
        return $this->hasOne(ProductSelectedUser::class , 'product_id' , 'id')->with('user');
    }

     // this is a recommended way to declare event handlers
     public static function boot() {
        parent::boot();

        static::deleting(function($product) { // before delete() method call this
             $product->images()->delete();
             $product->options()->delete();
             $product->options_ar()->delete();
             $product->favorites()->delete();
             // do the rest of the cleanup...
        });
    }

    public function options()
    {
        return $this->hasMany(Option::class)->whereNull('key_ar')->select('id' ,'product_id', 'key' , 'value');
    }

    public function options_ar()
    {
        return $this->hasMany(Option::class)->whereNull('key')->select('id' , 'product_id', 'key_ar' , 'value_ar');
    }
}
