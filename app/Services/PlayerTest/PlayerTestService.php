<?php

namespace App\Services\PlayerTest;

use App\Models\PlayerTest;
use App\Models\Site;
use App\Models\TestMetrics;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class PlayerTestService
{
    /**
     * Create a new player test with metrics.
     *
     * @param string $testId
     * @param string $playerId
     * @param string $sessionId
     * @param string $recorderBy
     * @param array $requiredMetrics
     * @param array $createPlayerTestDto
     * @return array
     * @throws ValidationException
     */
    public function create(
        string $testId,
        string $playerId,
        string $sessionId,
        string $recorderBy,
        array $requiredMetrics,
        array $createPlayerTestDto
    ) {
        // Validate the required metrics are present
        // $this->validateMetrics($createPlayerTestDto, $requiredMetrics); <-------- this validate need a check through

        return DB::transaction(function () use ($testId, $playerId, $sessionId, $recorderBy, $createPlayerTestDto) {
            // Create the player test
            $playerTest = PlayerTest::create([
                'player_id' => $playerId,
                'test_id' => $testId,
                'session_id' => $sessionId,
                'recorder_by' => $recorderBy,
                'recorded_at' => Carbon::now()->utc()->format('Y-m-d\TH:i:s\Z'),
            ]);

            // Create the test metrics
            $metricData = array_merge(
                ['player_test_id' => $playerTest->id],
                $createPlayerTestDto
            );

            $testMetric = TestMetrics::create($metricData);

            return ['playerTest' => $playerTest];
        });
    }
    public function findUnique(string $sessionId, string $testId, string $playerId)
    {
        return PlayerTest::where('session_id', $sessionId)
            ->where('test_id', $testId)
            ->where('player_id', $playerId)
            ->first();
    }
    public function findOne(string $id)
    {
        return PlayerTest::find($id);
    }
    public function findOneTestMetricById(string $id)
    {
        return TestMetrics::with(['playerTest.test'])
            ->find($id);
    }

    public function findAll()
    {
        return PlayerTest::with([
            'testSession.sessionTests.test:id,name',
            'metrics',
            'test:id,name',
            'player:id,first_name,last_name'
        ])
            ->orderByDesc('recorded_at')
            ->get();  
    }
    public function findAllByPlayerId(string $playerId)
    {
        return PlayerTest::where('player_id', $playerId)->get();
    }

    public function findBySessionId(string $sessionId)
    {
        // Get all player tests for the given session
        $playerTests = PlayerTest::where('session_id', $sessionId)
            ->with([
                'testSession.tests.test:id,name',
                'metrics',
                'test:id,name',
                'player:id,first_name,last_name'
            ])
            ->orderByDesc('recorded_at')
            ->get();

        // Group the data by playerId
        $groupedByPlayer = [];

        foreach ($playerTests as $playerTest) {
            $playerId = $playerTest->player_id;

            if (!isset($groupedByPlayer[$playerId])) {
                $groupedByPlayer[$playerId] = [
                    'playerId' => $playerId,
                    'player' => [
                        'firstName' => $playerTest->player->first_name,
                        'lastName' => $playerTest->player->last_name,
                    ],
                    'sessionTests' => $playerTest->testSession->tests->map(function ($sessionTest) {
                        return [
                            'test' => [
                                'id' => $sessionTest->test->id,
                                'name' => $sessionTest->test->name,
                            ]
                        ];
                    })->toArray(),
                    'tests' => [],
                ];
            }

            // Filter out metrics where the value is null
            $filteredMetrics = [];
            if ($playerTest->metrics) {
                foreach ($playerTest->metrics->getAttributes() as $key => $value) {
                    if (
                        $value !== null && $key !== 'id' && $key !== 'player_test_id' &&
                        $key !== 'created_at' && $key !== 'updated_at'
                    ) {
                        $filteredMetrics[$key] = $value;
                    }
                }
            }

            $groupedByPlayer[$playerId]['tests'][] = [
                'id' => $playerTest->id,
                'testId' => $playerTest->test_id,
                'testName' => $playerTest->test->name,
                'sessionId' => $playerTest->session_id,
                'metrics' => $filteredMetrics,
                'results' => $playerTest->results,
                'recordedAt' => $playerTest->recorded_at,
            ];
        }

        return array_values($groupedByPlayer);
    }
    public function update(
        string $playerTestId,
        array $requiredMetrics,
        array $updatePlayerTestDto
    ) {
        // Validate that required metrics are present
        $this->validateMetricsOnUpdate($updatePlayerTestDto, $requiredMetrics);

        $testMetric = TestMetrics::findOrFail($playerTestId);
        $testMetric->update($updatePlayerTestDto);

        return $testMetric;
    }

    public function remove(string $playerTestId)
    {
        return PlayerTest::findOrFail($playerTestId)->delete();
    }
    private function validateMetricsOnUpdate(array $data, array $requiredMetrics)
    {
        $errors = [];

        // Check for unexpected metrics
        $unexpectedMetrics = array_diff(array_keys($data), $requiredMetrics);

        if (!empty($unexpectedMetrics)) {
            $errors[] = 'Unexpected metrics: ' . implode(', ', $unexpectedMetrics);
        }

        // Check if required metrics that are provided are not empty
        $missingOrEmptyMetrics = [];
        foreach ($data as $metric => $value) {
            if (in_array($metric, $requiredMetrics) && $value === '') {
                $missingOrEmptyMetrics[] = $metric;
            }
        }

        if (!empty($missingOrEmptyMetrics)) {
            $errors[] = 'Missing or empty required metrics: ' . implode(', ', $missingOrEmptyMetrics);
        }

        // If there are any errors, throw a ValidationException
        if (!empty($errors)) {
            throw ValidationException::withMessages([
                'metrics' => implode('; ', $errors)
            ]);
        }
    }

    private function validateMetrics(array $data, array $requiredMetrics)
    {
        $errors = [];

        // Check for missing metrics
        $missingMetrics = array_filter($requiredMetrics, function ($metric) use ($data) {
            return !array_key_exists($metric, $data);
        });

        if (!empty($missingMetrics)) {
            $errors[] = 'Missing required metrics: ' . implode(', ', $missingMetrics);
        }

        // Check for unexpected metrics
        $unexpectedMetrics = array_diff(array_keys($data), $requiredMetrics);

        if (!empty($unexpectedMetrics)) {
            $errors[] = 'Unexpected metrics: ' . implode(', ', $unexpectedMetrics);
        }

        // Check if required metrics are provided and not empty
        $missingOrEmptyMetrics = array_filter($requiredMetrics, function ($metric) use ($data) {
            return array_key_exists($metric, $data) &&
                ($data[$metric] === '' || $data[$metric] === null);
        });

        if (!empty($missingOrEmptyMetrics)) {
            $errors[] = 'Missing or empty required metrics: ' . implode(', ', $missingOrEmptyMetrics);
        }

        // If there are any errors, throw a ValidationException
        if (!empty($errors)) {
            throw ValidationException::withMessages([
                'metrics' => implode('; ', $errors)
            ]);
        }
    }
}
