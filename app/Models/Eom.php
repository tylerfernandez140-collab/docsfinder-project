<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Eom extends Model
{
    use HasFactory;

    // Table name (optional if not default 'eoms')
    protected $table = 'eoms';

    // Fillable fields for mass assignment
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

    // Relationships
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
