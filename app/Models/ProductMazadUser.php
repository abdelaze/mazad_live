<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductMazadUser extends Model
{
    use HasFactory;
    protected $table      =  'product_mazad_users';
    protected $guarded    =  ['id'];

    public function user() {
        return $this->belongsTo(User::class)->select('id','full_name','email' ,'phone_number' , 'photo');
    }

    public function product() {
        return $this->belongsTo(Product::class);
    }
}
