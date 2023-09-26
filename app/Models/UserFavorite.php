<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserFavorite extends Model
{
    use HasFactory;
    protected $table    = 'user_favorites';
    protected $guarded  = ['id']; 
   
    public function favorite_user() {
        return $this->belongsTo('App\Models\User', 'favorite_user_id', 'id');
    }
   
   public function user() {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
   }
}
