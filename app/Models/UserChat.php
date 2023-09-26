<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserChat extends Model
{
    use HasFactory,SoftDeletes;
    protected $table      =  'user_chats';
    protected $guarded    =  ['id'];
    public function member1() {
        return $this->belongsTo(User::class, 'member1', 'id')->select('id' , 'full_name' , 'photo');
    }

    public function member2() {
        return $this->belongsTo(User::class, 'member2', 'id')->select('id' , 'full_name' , 'photo');
    }

    public function last_message() {
        return $this->hasOne(Message::class, 'user_chat_id' , 'chat_id')->orderBy('id' , 'DESC');
    }
}
