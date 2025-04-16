<?php

namespace App\Http\Middleware\Test;

use App\Models\Test;
use App\Services\Test\TestService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTestNameExists
{
    protected $testService;

    public function __construct(TestService $testService)
    {
        $this->testService = $testService;
    }
    public function handle(Request $request, Closure $next): Response
    {
        $name = $request->input('name');
        if ($name) {
            $test = Test::where('name', $name)->exists();
            if ($test) {
                return response()->json(['error' => "Test with name {$name} already exists."], 400);
            }
        }
        return $next($request);
    }
}
