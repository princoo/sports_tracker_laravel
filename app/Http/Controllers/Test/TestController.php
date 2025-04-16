<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use App\Services\Test\TestService;
use Illuminate\Http\Request;

class TestController extends Controller
{
    protected $testService;
    public function __construct(TestService $testService)
    {
        $this->testService = $testService;
    }
    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'required_metrics' => 'required|array',
        ]);
        $data = $this->testService->create($request->all());
        return response()->json(['message' => 'Test created successfully', 'data' => $data]);
    }
    public function findAll()
    {
        $data = $this->testService->findAll();
        return response()->json(['message' => 'Tests retrieved successfully', 'data' => $data]);
    }

    public function findOne($test_id)
    {
        $data = $this->testService->findOne($test_id);
        return response()->json(['message' => 'Test retrieved successfully', 'data' => $data]);
    }

    public function update(Request $request, $test_id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'required_metrics' => 'required|array',
        ]);
        $data = $this->testService->update($test_id, $request->all());
        return response()->json(['message' => 'Test updated successfully', 'data' => $data]);
    }

    public function remove($test_id)
    {
        $this->testService->remove($test_id);
        return response()->json(['message' => 'Test deleted successfully']);
    }
}
