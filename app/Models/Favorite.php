<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;
    protected $table    = 'favorites';
    protected $fillable = ['salon_id','user_id']; 
   
    public function salon() {
        return  $this->belongsTo('App\Models\Salon', 'salon_id', 'id');
    }
   
   public function user() {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
   }
}
