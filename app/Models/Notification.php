<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Translatable\HasTranslations;

class Notification extends Model
{
    use HasFactory , SoftDeletes , HasTranslations;
    protected $table        = 'notifications';
    protected $guarded      = ['id'];
    public $translatable    = ['title' , 'details'];


    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function mazad() {
        return $this->belongsTo(Mazdat::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
