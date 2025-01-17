<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'image'
    ];

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
        'password' => 'hashed',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    // Return groups created by the user
    public function groups()
    {
        return $this->hasMany(Group::class);
    }

    public function groups_user_in()
    {
        return $this->belongsToMany(Group::class, 'user_groups');
    }

    public function groups_to_join()
    {
        return $this->belongsToMany(Group::class, 'group_invitations');
    }

    public function files()
    {
        return $this->belongsToMany(File::class, 'user_files');
    }

    public function files_checked()
    {
        return $this->belongsToMany(File::class, 'file_operations');
    }

    public function admins()
    {
        return $this->hasMany(Admin::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}
