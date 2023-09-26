<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Message extends Model
{
    use HasFactory;
    protected $table      =  'messages';
    protected $guarded    =  ['id'];

    public function user_chat() {
        return $this->belongsTo(UserChat::class, 'user_chat_id', 'chat_id');
    }
}
