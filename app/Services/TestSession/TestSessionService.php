<?php

namespace App\Services\TestSession;

use App\Models\Test;
use App\Models\TestSession;
use App\Models\SessionTest;
use App\Enums\SessionStatus;
use App\DTOs\CreateTestSessionDto;
use App\DTOs\UpdateTestSessionDto;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TestSessionService
{
    /**
     * Create a new test session.
     *
     * @param CreateTestSessionDto $createTestSessionDto
     * @return TestSession
     * @throws ValidationException
     */
    public function create(CreateTestSessionDto $createTestSessionDto)
    {
        $date = $createTestSessionDto->date;
        $tests = $createTestSessionDto->tests;

        $currentDate = Carbon::now()->format('Y-m-d\TH:i:s\Z');
        $sessionDate = Carbon::parse($date)->utc()->format('Y-m-d\TH:i:s\Z');

        // Log dates for debugging
        logger()->info('Date provided:', ['date' => $date]);
        logger()->info('Session date formatted:', ['sessionDate' => $sessionDate]);

        // Check if the provided test IDs exist
        $existingTests = Test::whereIn('id', $tests)
            ->select('id')
            ->get();

        // Check if all test IDs are valid
        if ($existingTests->count() !== count($tests)) {
            throw ValidationException::withMessages([
                'tests' => ['Some of the test IDs provided are invalid.'],
            ]);
        }

        // Check if the date is in the past
        if (explode('T', $sessionDate)[0] < explode('T', $currentDate)[0]) {
            throw ValidationException::withMessages([
                'date' => ['The session date must be today or in the future.'],
            ]);
        }

        $isActive = false;
        $status = SessionStatus::SCHEDULED;

        if (explode('T', $sessionDate)[0] === explode('T', $currentDate)[0]) {
            $isActive = true;
            $status = SessionStatus::ACTIVE;
        }

        // Use transaction to ensure data integrity
        // Use transaction to ensure data integrity
        $result = DB::transaction(function () use ($sessionDate, $isActive, $status, $existingTests) {
            $testSession = TestSession::create([
                'date' => $sessionDate,
                'is_active' => $isActive,
                'status' => $status->value,
            ]);

            // Create session test relationships
            foreach ($existingTests as $test) {
                SessionTest::create([
                    'session_id' => $testSession->id,
                    'test_id' => $test->id,
                ]);
            }

            return $testSession;
        });

        return $result;
    }

    public function findAll()
    {
        return TestSession::with('sessionTests.test') // i will come back to this later when there is an issue about how this data id retrieved 
            ->orderByDesc('is_active')
            ->orderByDesc('date')
            ->get();
    }
    public function findOne(string $id)
    {
        return TestSession::with('sessionTests.test')
            ->find($id);
    }
    public function findActiveSessions()
    {
        return TestSession::where('is_active', true)
            ->where('status', SessionStatus::ACTIVE->value)
            ->with(['sessionTests.test'])
            // ->with(['tests.test'])
            ->first();
    }

    public function findActive(string $date)
    {
        $formattedDate = Carbon::parse($date)->utc()->format('Y-m-d\TH:i:s\Z');

        return TestSession::where('is_active', true)
            ->where('status', SessionStatus::ACTIVE->value)
            ->where('date', $formattedDate)
            ->get();
    }
    public function findOnSameDay(string $date)
    {
        $formattedDate = Carbon::parse($date)->utc()->format('Y-m-d\TH:i:s\Z');

        return TestSession::where('date', $formattedDate)
            ->get();
    }
    public function remove(string $id)
    {
        return TestSession::findOrFail($id)->delete();
    }
    public function update(string $id,UpdateTestSessionDto $updateTestSessionDto)
    {
        $tests = $updateTestSessionDto->tests;
        $date = $updateTestSessionDto->date;
        $existingTests = [];
        $isActive = false;
        $status = SessionStatus::SCHEDULED;

        // Check if tests are provided and validate them
        if ($tests && count($tests) > 0) {
            $existingTests = Test::whereIn('id', $tests)
                ->select('id')
                ->get();

            if ($existingTests->count() !== count($tests)) {
                throw ValidationException::withMessages([
                    'tests' => ['Some of the test IDs provided are invalid.'],
                ]);
            }
        }

        $currentDate = Carbon::now()->utc()->format('Y-m-d\TH:i:s\Z');

        return DB::transaction(function () use ($id, $date, $tests, $existingTests, $currentDate) {
            // Handle updates to the TestSession
            $updateData = [];

            if ($date) {
                $sessionDate = Carbon::parse($date)->utc()->format('Y-m-d\TH:i:s\Z');

                if (explode('T', $sessionDate)[0] < explode('T', $currentDate)[0]) {
                    throw ValidationException::withMessages([
                        'date' => ['The session date must be today or in the future.'],
                    ]);
                }

                // Check if the date already exists for another session
                $sessionDateExists = TestSession::where('date', $sessionDate)
                    ->where('id', '!=', $id)
                    ->first();

                if ($sessionDateExists) {
                    throw ValidationException::withMessages([
                        'date' => ['The session date already exists.'],
                    ]);
                }

                $isActive = false;
                $status = SessionStatus::SCHEDULED;

                if (explode('T', $sessionDate)[0] == explode('T', $currentDate)[0]) {
                    $isActive = true;
                    $status = SessionStatus::ACTIVE;
                }

                $updateData['is_active'] = $isActive;
                $updateData['status'] = $status->value;
                $updateData['date'] = $sessionDate;

                logger()->info('Update data:', $updateData);
            }

            $updatedSession = TestSession::findOrFail($id);
            $updatedSession->update($updateData);

            // Handle the update of tests
            if ($tests) {
                // Delete existing tests for this session
                SessionTest::where('session_id', $id)->delete();

                // Create new SessionTests records
                foreach ($existingTests as $test) {
                    SessionTest::create([
                        'session_id' => $id,
                        'test_id' => $test->id,
                    ]);
                }
            }

            // Return the updated session with tests
            return TestSession::with('sessionTests.test')->findOrFail($id);
        });
    }
}
