<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'file_id'
    ];

    public function file()
    {
        return $this->belongsTo(File::class);
    }
}
