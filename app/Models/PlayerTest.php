<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PlayerTest extends Model
{
    use HasFactory, HasUuids;

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * The attributes that should be mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'player_id',
        'test_id',
        'session_id',
        'metrics_id',
        'recorder_by',
        'recorded_at',
        'results',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'recorded_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the player associated with this test record.
     */
    public function player()
    {
        return $this->belongsTo(Player::class,'player_id');
    }

    /**
     * Get the test associated with this record.
     */
    public function test()
    {
        return $this->belongsTo(Test::class,'test_id');
    }

    /**
     * Get the user (recorder) associated with this test record.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'recorder_by');
    }

    /**
     * Get the test session associated with this record.
     */
    public function testSession()
    {
        return $this->belongsTo(TestSession::class, 'session_id');
    }

    /**
     * Get the metrics associated with this test record.
     */
    public function metrics()
    {
        return $this->hasMany(TestMetrics::class, 'player_test_id');
    }
}
