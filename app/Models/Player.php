<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Player extends Model
{
    use HasFactory, HasUuids;

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'age',
        'height',
        'weight',
        'foot',
        'nationality',
        'acad_status',
        'dob',
        'gender',
        'positions',
        'site_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'age' => 'float',
        'height' => 'float',
        'weight' => 'float',
        'dob' => 'datetime',
        'positions' => 'array', // Cast JSON to PHP array
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the site that the player belongs to.
     */
    public function site()
    {
        return $this->belongsTo(Site::class,'site_id');
    }

    /**
     * Get the test records for the player.
     */
    public function testRecords()
    {
        return $this->hasMany(PlayerTest::class, 'player_id');
    }
}
