<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'user_id'
    ];

    protected $appends = ['is_owner'];

    public function files()
    {
        return $this->hasMany(File::class);
    }

    // Return the admin of the group
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function users_group_in()
    {
        return $this->belongsToMany(User::class, 'user_groups');
    }

    public function users_to_join()
    {
        return $this->belongsToMany(User::class, 'group_invitations');
    }

    public function add_file_requests()
    {
        return $this->hasMany(AddFileRequest::class);
    }

    // Accessor to check if the authenticated user is the owner
    public function getIsOwnerAttribute()
    {
        return Auth::check() && Auth::id() === $this->user_id;
    }
}
