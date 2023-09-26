<?php

namespace App\Models;

use Carbon\Carbon;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function mazdats()
    {
        return $this->hasMany(Mazdat::class);
    }

    public function rates()
    {
        return $this->hasMany(Rate::class, 'barber_id');
    }

    public function is_favorite()
    {
        return $this->hasOne(UserFavorite::class)->where('user_id' , Auth::guard('api')->user()->id);
    }

    
    public function mazdat_favorites() {
        return $this->hasMany(MazdatFavorite::class, 'user_id');
    }

    public function rates_sum()
    {
        return $this->hasMany(Rate::class,'owner_id') ->selectRaw('round(avg(rate),2) as avg, owner_id')
        ->groupBy('owner_id');
    }


    public function notifications()
    {
        return $this->hasMany(Notification::class, 'user_id')->with(['mazad' , 'product']);
    }

    public function unreadnotifications()
    {
        return $this->hasMany(Notification::class, 'user_id')->where('is_read' , 0);
    }
    

   

    public function getPhotoAttribute($value)
    {
        return url('public/storage/uploads/users/'.$value);
    }

     // this is a recommended way to declare event handlers
     public static function boot() {
        parent::boot();

        static::deleting(function($mazdat) { // before delete() method call this
             $mazdat->mazdats()->delete();
             $mazdat->mazdat_favorites()->delete();
             // do the rest of the cleanup...
        });
    }


    public function selectedMazdats()
    {
        return $this->hasMany(MazadSelectedUser::class, 'user_id' , 'id' )->with(['mazad' , 'owner'])->where('payment_status' , 'paid');
    }

    public function selectedProducts()
    {
        return $this->hasMany(ProductSelectedUser::class, 'user_id' , 'id' )->with(['product' , 'owner'])->where('payment_status' , 'paid');
    }


}
