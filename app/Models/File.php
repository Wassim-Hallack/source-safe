<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'isFree',
        'group_id'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_file');
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}
