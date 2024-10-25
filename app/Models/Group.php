<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'user_id'
    ];

    public function files()
    {
        return $this->hasMany(File::class);
    }

    // Return the admin of the group
    public function user()
    {
        return $this->belongsTo(User::class, 'user_groups');
    }

    public function users_group_in()
    {
        return $this->belongsToMany(User::class, 'user_groups');
    }

    public function users_to_join()
    {
        return $this->belongsToMany(User::class, 'group_invitations');
    }
}
