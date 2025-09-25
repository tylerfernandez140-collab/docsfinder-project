<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class College extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'programs', 'accreditation', 'qa', 'coordinator',
    ];

    public function departments()
    {
        return $this->hasMany(Department::class);
    }
}
