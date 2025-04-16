<?php

namespace App\Http\Middleware\Test;

use App\Models\Test;
use App\Services\Test\TestService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTestIdExists
{
    protected $testService;

    public function __construct(TestService $testService)
    {
        $this->testService = $testService;
    }
    public function handle(Request $request, Closure $next): Response
    {
        $id = $request->route('test_id') ?? $request->input('test_id');
        if (!$id) {
            return response()->json(['error' => 'ID for test is required'], 400);
        }
        $test = Test::where('id', $id)->exists();
        if (!$test) {
            return response()->json(['error' => 'Test with this ID does not exist.'], 400);
        }

        return $next($request);
    }
}
