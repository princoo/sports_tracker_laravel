<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;


class Role extends Model
{
    use HasUuids;

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    // The attributes that are mass assignable
    protected $fillable = [
        'role_name',
    ];

    // The attributes that should be cast to native types
    protected $casts = [
        'id' => 'string',  // Cast UUID to string
    ];

    // Relationship with User model
    public function user()
    {
        return $this->hasMany(User::class, 'role_id');
    }
}
