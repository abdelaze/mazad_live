<?php

namespace App\Models;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Mazdat extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table      =  'mazdats';
    protected $guarded    =  ['id'];

    public function category() {
         return $this->belongsTo(Category::class)->select('id','name');
    }

    public function subcategory() {
        return $this->belongsTo(SubCategory::class)->select('id','name');
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
            return $this->belongsTo(User::class , 'user_id' , 'id')->select('id','full_name','email' ,'phone_number' , 'photo');   
        }else {
             return $this->belongsTo(User::class , 'user_id' , 'id')->select('id','full_name','email' ,'phone_number' , 'photo')->withCount('is_favorite');
        }
    }

    public function images() {
        return $this->hasMany(MazdatImage::class);
    }

    public function videos() {
        return $this->hasMany(MazdatVideo::class);
    }

    public function favorites()
    {
        return $this->hasMany(MazdatFavorite::class);
    }

    public function favorite()
    {
        return $this->hasMany(MazdatFavorite::class)->where('user_id' , Auth::guard('api')->user()->id);
    }

    public function mazad_selected_user()
    {
        return $this->hasOne(MazadSelectedUser::class , 'mazdat_id' , 'id')->with('user');
    }

     // this is a recommended way to declare event handlers
     public static function boot() {
        parent::boot();

        static::deleting(function($mazdat) { // before delete() method call this

           //  $mazdat->images()->delete();
           //  $mazdat->favorites()->delete();
           //  $mazdat->videos()->delete(); 
             // do the rest of the cleanup...
             
        });
    }

       
}
