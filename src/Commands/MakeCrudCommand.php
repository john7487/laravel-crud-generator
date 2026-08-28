<?php

namespace AltenJohn\CrudGenerator\Commands;



use AltenJohn\CrudGenerator\Support\GeneratorDefinition;
use AltenJohn\CrudGenerator\Support\GeneratorResult;
use AltenJohn\CrudGenerator\Support\StubGenerator;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

#[Signature('make:crud 
{name : The model name} 
{--force : Overwrite existing files}
{--m|migration : Create migration}
')]
#[Description('Create a complete CRUD structure')]
class MakeCrudCommand extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(StubGenerator $generator)
    {
        $name = $this->argument('name');
        $force = (bool) $this->option('force');

        $replacements = $this->buildReplacements($name);

        $this->line($replacements);

        $results = [];

        foreach ($this->files() as $file) {

            if (! $this->shouldGenerate($file)) {
                continue;
            }

            $fileReplacements = [
                ...$replacements,
            '{{ crud }}' => $file->crud ?? '',
            ];

            $result =   $generator->generate(
                        stub: $file->stub,
                        destination: $file->resolvePath($fileReplacements),
                        replacements: $fileReplacements,
                        force: $force,
                        );

            $this->displayResult(
                definition: $file,
                result: $result,
            );
        }
        return self::SUCCESS;
    }

////////////////////////////////////////////////////////////////////////////////////////////////


private function shouldGenerate(
    GeneratorDefinition $file
): bool {
    if ($file->option === null) {
        return true;
    }

    return match ($file->option) {
        'migration' => $this->option('migration'),
        default => false,
    };
}



private function buildReplacements(
    string $name
): array {
    $name = trim($name, '/');
    $parts = explode('/', $name);
    $model = Str::studly(array_pop($parts));
    $path = implode('/', $parts);
    $controllerNamespace = $path !== ''
        ? '\\' . str_replace('/', '\\', $path)
        : '';

    return [
        '{{ model }}' => $model,
        '{{ variable }}' => Str::camel($model),
        '{{ table }}' => Str::snake(Str::pluralStudly($model)),
        '{{ path }}' => $path,
        '{{ route_path }}' => Str::camel($path),
        '{{ controller }}' => $path.'/'.$model,
        '{{ controller_namespace }}' => $controllerNamespace,
    ];
}

    private function files(): array
    {
        return [
            new GeneratorDefinition(
                name: 'Model',
                stub: 'crud/model.stub',
                directory: 'app/Models',
                filename: '{{ model }}.php',
                crud: '',
            ),

            new GeneratorDefinition(
                name: 'Migration',
                stub: 'crud/migration.stub',
                directory: 'database/migrations',
                filename: 'create_{{ table }}_table.php',
                timestamp: true,
                option: 'migration',
            ),


            new GeneratorDefinition(
                name: 'IndexController',
                stub: 'crud/controller.index.stub',
                directory: 'app/Http/Controllers',
                filename: 'Api/{{ controller }}/IndexController.php',
                crud: 'Index',
            ),

            new GeneratorDefinition(
                name: 'StoreController',
                stub: 'crud/controller.store.stub',
                directory: 'app/Http/Controllers',
                filename: 'Api/{{ controller }}/StoreController.php',
                crud: 'Store',
            ),

            new GeneratorDefinition(
                name: 'ImportController',
                stub: 'crud/controller.import.stub',
                directory: 'app/Http/Controllers',
                filename: 'Api/{{ controller }}/ImportController.php',
                crud: 'Import',
            ),

            new GeneratorDefinition(
                name: 'ShowController',
                stub: 'crud/controller.show.stub',
                directory: 'app/Http/Controllers',
                filename: 'Api/{{ controller }}/ShowController.php',
                crud: 'Show',
            ),


            new GeneratorDefinition(
                name: 'UpdateController',
                stub: 'crud/controller.update.stub',
                directory: 'app/Http/Controllers',
                filename: 'Api/{{ controller }}/UpdateController.php',
                crud: 'Update',
            ),

            new GeneratorDefinition(
                name: 'DeleteController',
                stub: 'crud/controller.delete.stub',
                directory: 'app/Http/Controllers',
                filename: 'Api/{{ controller }}/DeleteController.php',
                crud: 'Delete',
            ),

            new GeneratorDefinition(
                name: 'StoreRequest',
                stub: 'crud/request.stub',
                directory: 'app/Http/Requests',
                filename: 'Api/{{ controller }}/{{ crud }}Request.php',
                crud: 'Store',
            ),

            new GeneratorDefinition(
                name: 'ImportRequest',
                stub: 'crud/request.import.stub',
                directory: 'app/Http/Requests',
                filename: 'Api/{{ controller }}/{{ crud }}Request.php',
                crud: 'Import',
            ),

            new GeneratorDefinition(
                name: 'UpdateRequest',
                stub: 'crud/request.stub',
                directory: 'app/Http/Requests',
                filename: 'Api/{{ controller }}/{{ crud }}Request.php',
                crud: 'Update',
            ),

            new GeneratorDefinition(
                name: 'StoreDTOs',
                stub: 'crud/dto.stub',
                directory: 'app/DTOs/{{ model }}',
                filename: 'Store{{ model }}DTO.php',
                crud: 'Store',
            ),

            new GeneratorDefinition(
                name: 'ImportDTOs',
                stub: 'crud/dto.import.stub',
                directory: 'app/DTOs/{{ model }}',
                filename: 'Import{{ model }}DTO.php',
                crud: 'Import',
            ),

            new GeneratorDefinition(
                name: 'UpdateDTOs',
                stub: 'crud/dto.stub',
                directory: 'app/DTOs/{{ model }}',
                filename: 'Update{{ model }}DTO.php',
                crud: 'Update',
            ),

            new GeneratorDefinition(
                name: 'StoreAction',
                stub: 'crud/action.store.stub',
                directory: 'app/Actions/{{ model }}',
                filename: 'Store{{ model }}Action.php',
                crud: 'Store',
            ),

            new GeneratorDefinition(
                name: 'ImportAction',
                stub: 'crud/action.import.stub',
                directory: 'app/Actions/{{ model }}',
                filename: 'Import{{ model }}Action.php',
                crud: 'Import',
            ),

            new GeneratorDefinition(
                name: 'UpdateAction',
                stub: 'crud/action.update.stub',
                directory: 'app/Actions/{{ model }}',
                filename: 'Update{{ model }}Action.php',
                crud: 'Update',
            ),

            new GeneratorDefinition(
                name: 'DeleteAction',
                stub: 'crud/action.delete.stub',
                directory: 'app/Actions/{{ model }}',
                filename: 'Delete{{ model }}Action.php',
                crud: 'Delete',
            ),

            new GeneratorDefinition(
                name: 'Resource',
                stub: 'crud/resource.stub',
                directory: 'app/Http/Resources',
                filename: 'Api/{{ controller }}Resource.php',
                crud: '',
            ),

            new GeneratorDefinition(
                name: 'Route',
                stub: 'crud/route.stub',
                directory: 'routes/api',
                filename: '{{ route_path }}/{{ variable }}.php',
                crud: '',
            ),

        ];

    }


    private function displayResult(
            GeneratorDefinition $definition,
            GeneratorResult $result
        ): void {
            if ($result->isCreated()) {
                $this->info(
                    "✓ {$definition->name}"
                );
                $this->line(
                    "{$result->path} - {$result->message}"
                );
                return;
            }

            if ($result->isSkipped()) {
                $this->warn(
                    "⚠ {$definition->name}"
                );
                $this->line(
                    "{$result->path} - {$result->message}"
                );

                return;
            }

            $this->error(
                "✗ {$definition->name}"
            );

            $this->line(
                "{$result->path} - {$result->message}"
            );
    }
}
