<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable = ['name', 'created_by_role', 'type'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'group_user');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function addUserByRole(string $role)
    {
        $users = \App\Models\User::where('role', $role)->get();
        $this->users()->syncWithoutDetaching($users->pluck('id')->toArray());
    }
}
