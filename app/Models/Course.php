<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'title', 'program_id', 'units', 'instructor',
    ];

    public function program()
    {
        return $this->belongsTo(Program::class);
    }
}
