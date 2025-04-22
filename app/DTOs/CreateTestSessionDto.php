<?php

namespace App\DTOs;

class CreateTestSessionDto
{
    /**
     * @param array $tests Array of test IDs
     * @param string $date Date string for the session
     */
    public function __construct(
        public array $tests,
        public string $date
    ) {}
    
    /**
     * Create DTO from request data
     *
     * @param array $data
     * @return static
     */
    public static function fromArray(array $data): self
    {
        return new self(
            tests: $data['tests'],
            date: $data['date']
        );
    }
}