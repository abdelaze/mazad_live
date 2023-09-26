<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;
    protected $table      =  'countries';
    protected $guarded    =  ['id'];
  
    public function states() {
        return $this->hasMany(State::class, 'country_id')->select('id' , 'name_en' ,'name_ar' ,'country_id');
    }
}
