<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcessManual extends Model
{
    use HasFactory;

    protected $table = 'process_manuals';

    protected $fillable = [
        'title',
        'control_number',
        'type',
        'status',
        'version',
        'revisions',
        'owner_id',
        'numdl',
        'filename',
        'path'
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
