<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddFileRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_id',
        'name',
        'isFree'
    ];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function user()
    {
        return $this->hasOne(AddFileRequestToUser::class);
    }
}
