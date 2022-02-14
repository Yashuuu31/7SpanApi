<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_url'
    ];
    protected $fillable = [
        'role',
        'first_name',
        'last_name',
        'email',
        'mobile_no',
        'profile',
        'is_active',
        'password',
        'password_view',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'password_view'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function hobbie(){
        return $this->hasMany(UserHobbie::class, 'user_id');
    }

    // Add Accessor For Image
    public function getProfileUrlAttribute(){
        $file = $this->attributes['profile'];
        $url = url("storage/app/media/user/$file");
        return $url;
    }
}
