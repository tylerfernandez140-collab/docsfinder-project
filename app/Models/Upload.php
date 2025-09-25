<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Upload extends Model
{
    use HasFactory;

    protected $primaryKey = 'upload_id';

    protected $fillable = [
        'filename',
        'title',
        'control_number',
        'type',
        'version',
        'size',
        'file_type',
        'path',
        'user_id',
        'status_upload',
        'numdl',
        'previous_version_id',
        'is_archived',
        'status_distribution',
        'distributed_to_designation',
        'distributed_to_process_owner',
        'distributed_by_user_id',
        'distributed_at',
    ];

    protected $casts = [
        'distributed_to_process_owner' => 'array',
        'distributed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the latest request for this upload.
     */
    public function latestRequest()
    {
        return $this->hasOne(Requesting::class, 'upload_id', 'upload_id')->latestOfMany('created_at');
    }

    public function requesting()
    {
        return $this->hasMany(Requesting::class, 'upload_id', 'upload_id');
    }

    public function getRevisionsCountAttribute()
    {
        return $this->requesting()->count();
    }

    public function nextVersion()
    {
        return $this->hasOne(Upload::class, 'previous_version_id', 'upload_id');
    }

    public function distributedBy()
    {
        return $this->belongsTo(User::class, 'distributed_by_user_id');
    }
}
