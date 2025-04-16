<?php

namespace App\Services\Test;

use App\Models\Test;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Models\User;

class TestService
{
    public function create(array $data): Test
    {
        return Test::create($data);
    }

    public function findAll()
    {
        return Test::all();
    }

    public function findOne($id)
    {
        return Test::findOrFail($id);
    }

    public function findByName($testName)
    {
        return Test::where('name', $testName)->firstOrFail();
    }

    public function update($id, array $data)
    {
        $test = $this->findOne($id);
        $test->update($data);
        return $test;
    }

    public function remove($id)
    {
        $test = $this->findOne($id);
        $test->delete();
        return $test;
    }
}
