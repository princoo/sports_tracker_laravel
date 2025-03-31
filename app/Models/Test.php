<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Test extends Model
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
        'name',
        'description',
        'required_metrics',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'required_metrics' => 'array', // Cast JSON to PHP array
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the player test results for this test.
     */
    public function testResults()
    {
        return $this->hasMany(PlayerTest::class,'test_id');
    }

    /**
     * Get the sessions that include this test.
     */
    public function sessions()
    {
        return $this->hasMany(SessionTest::class,'test_id');
    }
}
