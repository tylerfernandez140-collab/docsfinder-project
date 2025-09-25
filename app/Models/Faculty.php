<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faculty extends Model
{
    use HasFactory;

    protected $table = 'faculty'; // since table name is not plural

    protected $fillable = [
        'name', 'department_id', 'designation', 'specialization',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
