<?php

namespace App\Http\Middleware\Test;

use App\Models\Test;
use App\Services\Test\TestService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUpdatedNameExists
{
    protected $testService;

    public function __construct(TestService $testService)
    {
        $this->testService = $testService;
    }
    public function handle(Request $request, Closure $next): Response
    {
        $name = $request->input('name');
        $testId = $request->route('test_id');
        if ($name) {
            $test = Test::where('name', $name)->first();
            // $test = $this->testService->findByName($name);
            if ($test && $test->id !== $testId) {
                return response()->json(['error' => "Test with name {$name} already exists."], 400);
            }
        }
        return $next($request);
    }
}
