<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestMetrics extends Model
{
    use HasFactory, HasUuids;

    // The primary key for the table
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'player_test_id',
        'accuracy',
        'body_position',
        'total_time',
        'attempts',
        'successes',
        'power',
        'cones_touched',
        'foot',
        'errors',
        'distance',
        'ball_control',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'accuracy' => 'float',
        'body_position' => 'float',
        'total_time' => 'float',
        'attempts' => 'integer',
        'successes' => 'integer',
        'power' => 'float',
        'cones_touched' => 'integer',
        'foot' => 'string', // Cast to string for enum type
        'errors' => 'integer',
        'distance' => 'float',
        'ball_control' => 'float',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    /**
     * Get the player test that owns the metrics.
     */
    public function playerTest()
    {
        return $this->belongsTo(PlayerTest::class, 'player_test_id');
    }
}
