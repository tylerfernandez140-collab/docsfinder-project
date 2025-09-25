<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Requesting extends Model
{
    protected $table = 'requesting';

    protected $primaryKey = 'request_id';

    protected $fillable = [
        'upload_id',
        'user_id',
        'request_remarks',
        'request_status',
        'status_remarks'
    ];

    // App\Models\Requesting.php
public function upload()
{
    return $this->belongsTo(Upload::class, 'upload_id', 'upload_id');
}

public function owner()
{
    return $this->belongsTo(User::class, 'user_id');
}

}
