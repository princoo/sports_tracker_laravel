<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TestSession extends Model
{
    use HasFactory, HasUuids;
    
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'test_sessions';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'date',
        'is_active',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'datetime',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Session status constants
     */
    const STATUS_SCHEDULED = 'SCHEDULED';
    const STATUS_ACTIVE = 'ACTIVE';
    const STATUS_COMPLETED = 'COMPLETED';

    public function sessionTests()
    {
        return $this->hasMany(SessionTest::class, 'session_id');
    }

    /**
     * Get the player test results for this session.
     */
    public function testResults()
    {
        return $this->hasMany(PlayerTest::class, 'session_id');
    }
    /**
     * Get the tests associated with this session.
     */
}
