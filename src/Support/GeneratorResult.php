<?php

namespace AltenJohn\CrudGenerator\Support;

final readonly class GeneratorResult
{
    public function __construct(
        public string $status,
        public string $path,
        public ?string $message = null,
    ) {}

    public function isCreated(): bool
    {
        return $this->status === 'created';
    }

    public function isSkipped(): bool
    {
        return $this->status === 'skipped';
    }

    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }
}
