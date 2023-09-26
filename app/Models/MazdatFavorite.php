<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MazdatFavorite extends Model
{
    use HasFactory;
    protected $table    = 'mazdat_favorites';
    protected $guarded  = ['id']; 
   
    public function mazad() {
        return  $this->belongsTo('App\Models\Mazdat', 'mazdat_id', 'id');
    }
   
   public function user() {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
   }
}
