<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MazadSelectedUser extends Model
{
    use HasFactory;
    protected $table      =  'mazad_selected_users';
    protected $guarded    =  ['id'];

    public function user() {
        return $this->belongsTo(User::class)->select('id','full_name','email' ,'phone_number' , 'photo');
    }

    public function mazad() {
        return $this->belongsTo(Mazdat::class , 'mazdat_id' , 'id');
    }

    public function owner() {
        return $this->belongsTo(User::class , 'owner_id' , 'id')->select('id','full_name','email' ,'phone_number' , 'photo');
    }

    public function admin() {
        return $this->belongsTo(Admin::class , 'admin_id' , 'id');
    }

}
