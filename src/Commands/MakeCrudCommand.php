<?php

namespace AltenJohn\CrudGenerator\Commands;



use AltenJohn\CrudGenerator\Definitions\CrudDefinition;
//use AltenJohn\CrudGenerator\Definitions\CrudFileDefinitions;
//use AltenJohn\CrudGenerator\Enums\CrudAction;
use AltenJohn\CrudGenerator\Support\CrudName;
use AltenJohn\CrudGenerator\Support\GeneratorDefinition;
use AltenJohn\CrudGenerator\Support\GeneratorResult;
use AltenJohn\CrudGenerator\Support\StubGenerator;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

//use Illuminate\Support\Str;

#[Signature('make:crud 
{name : The model name} 
{--force : Overwrite existing files}
{--m|migration : Create migration}
')]
#[Description('Create a complete CRUD structure')]
class MakeCrudCommand extends Command
{

    public function __construct(
      //  private readonly CrudFileDefinitions $definitions,
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(StubGenerator $generator)
    {

        $crudName = CrudName::from($this->argument('name'));
        $force = (bool) $this->option('force');
        $replacements = $this->buildReplacements($crudName);

        $results = [];

    //    foreach ($this->definitions->forGeneration($this->option('migration'),) as $definition) {
             foreach (CrudDefinition::definitions() as $definition) {
   
                if (! $this->shouldGenerate($definition)) {
                    continue;
                }


                    $fileReplacements = [
                        ...$replacements,
                    '{{ crud }}' => $definition->crud ?? '',
                    ];

                    $result =   $generator->generate(
                                stub: $definition->stub,
                                destination: $definition->resolvePath($fileReplacements),
                                replacements: $fileReplacements,
                                force: $force,
                                );

                    $this->displayResult(
                        definition: $definition,
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
    CrudName $crud  // V1/Product
): array {

    return [
        '{{ model }}' => $crud->model,  // => Product
        '{{ variable }}' =>  $crud->variable(),  // => product
        '{{ table }}' => $crud->table(),  // => products
        '{{ path }}' =>  $crud->version,  // => V1
        '{{ route_path }}' => $crud->routePath(),  // => v1
        '{{ controller }}' => $crud->path(),  // => V1/Product
        '{{ controller_namespace }}' => $crud->controllerNamespace(), // => \V1
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
