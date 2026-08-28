<?php

declare(strict_types=1);

namespace AltenJohn\CrudGenerator\Support;

use RuntimeException;

//use RuntimeException;

class StubGenerator
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function generate(
        string $stub,
        string $destination,
        array $replacements = [],
        bool $force = false,
    ): GeneratorResult {


     try {

        $stubPath = $this->resolveStub($stub);
   

            if (! file_exists($stubPath)) {
                return new GeneratorResult(
                    status: 'failed',
                    path: $destination,
                    message: "Stub [{$stub}] not found.",
                );
            }


               if (
                file_exists($destination)
                && ! $force
            ) {
                return new GeneratorResult(
                    status: 'skipped',
                    path: $destination,
                    message: 'File already exists.',
                );
            }


        $content = file_get_contents($stubPath);

            if ($content === false) {
                return new GeneratorResult(
                    status: 'failed',
                    path: $destination,
                    message: "Unable to read stub [{$stub}].",
                );
        }

            $content = str_replace(
                array_keys($replacements),
                array_values($replacements),
                $content,
            );




        $directory = dirname($destination);



            if (! is_dir($directory)) {
                if (! mkdir($directory, 0755, true)) {
                    return new GeneratorResult(
                        status: 'failed',
                        path: $destination,
                        message: "Unable to create directory [{$directory}].",
                    );
                }
            }

            if (
                file_put_contents(
                    $destination,
                    $content
                ) === false
            ) {
                return new GeneratorResult(
                    status: 'failed',
                    path: $destination,
                    message: 'Unable to write file.',
                );
            }

            return new GeneratorResult(
                status: 'created',
                path: $destination,
                message: 'File created successfully.',
            );
        } catch (\Throwable $e) {
            return new GeneratorResult(
                status: 'failed',
                path: $destination,
                message: $e->getMessage(),
            );
        }

    }


private function resolveStub(string $stub): string
{
    // 1. Custom stub milik project
    $projectStub = base_path('stubs/' . $stub);

    if (is_file($projectStub)) {
        return $projectStub;
    }

    // 2. Default stub milik package
    $packageStub = dirname(__DIR__, 2)
        . '/stubs/'
        . $stub;

    if (is_file($packageStub)) {
        return $packageStub;
    }

    throw new RuntimeException(
        "[{$stub}] not found."
    );
}

}
