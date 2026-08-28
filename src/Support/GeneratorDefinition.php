<?php

declare(strict_types=1);

namespace AltenJohn\CrudGenerator\Support;

final readonly class GeneratorDefinition
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public string $name,
        public string $stub,
        public string $directory,
        public string $filename,
        public ?string $crud = null,
        public bool $timestamp = false,
        public ?string $option = null,
    )
    {}

    public function resolvePath(array $replacements): string 
    {
        $directory = str_replace(
            array_keys($replacements),
            array_values($replacements),
            $this->directory,
        );

        $filename = str_replace(
            array_keys($replacements),
            array_values($replacements),
            $this->filename,
        );

        if ($this->timestamp) {
        $timestamp = now()->format('Y_m_d_His');

        $filename = "{$timestamp}_{$filename}";
    }



        return base_path(
            "{$directory}/{$filename}"
        );
    }

}
