<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSelectedUser extends Model
{
    use HasFactory;
    protected $table      =  'product_selected_users';
    protected $guarded    =  ['id'];

    public function user() {
        return $this->belongsTo(User::class)->select('id','full_name','email' ,'phone_number' , 'photo');
    }

    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function owner() {
        return $this->belongsTo(User::class , 'owner_id' , 'id')->select('id','full_name','email' ,'phone_number' , 'photo');
    }

    public function admin() {
        return $this->belongsTo(Admin::class , 'admin_id' , 'id');
    }
}
