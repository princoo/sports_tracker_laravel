<?php

namespace App\DTOs;

class UpdateTestSessionDto
{
    /**
     * @param array|null $tests Array of test IDs
     * @param string|null $date Date string for the session
     */
    public function __construct(
        public ?array $tests = null,
        public ?string $date = null
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
            tests: $data['tests'] ?? null,
            date: $data['date'] ?? null
        );
    }
}