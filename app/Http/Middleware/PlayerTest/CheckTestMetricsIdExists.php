<?php

namespace App\Http\Middleware\PlayerTest;

use App\Services\PlayerTest\PlayerTestService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTestMetricsIdExists
{
    protected $playerTestService;
    public function __construct(PlayerTestService $playerTestService)
    {
        $this->playerTestService = $playerTestService;
    }
    public function handle(Request $request, Closure $next): Response
    {
        $testMetricId = $request->route('test_metric_id');
        $testMetric = $this->playerTestService->findOneTestMetricById($testMetricId);
        if (!$testMetric) {
            return response()->json([
                'status' => 'error',
                'message' => 'TestMetric does not exists',
            ], 400);
        }
        $requiredMetrics = $testMetric->playerTest->test->requiredMetrics;
        $request->merge(['requiredMetrics' => $requiredMetrics]);
        return $next($request);
    }
}
