<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddFileRequestToUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'add_file_request_id',
        'user_id'
    ];

    public function addFileRequest()
    {
        return $this->belongsTo(AddFileRequest::class);
    }
}
