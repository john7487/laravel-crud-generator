<?php

declare(strict_types=1);

namespace AltenJohn\CrudGenerator;

use AltenJohn\CrudGenerator\Commands\DeleteCrudCommand;
use AltenJohn\CrudGenerator\Commands\MakeCrudCommand;
use Illuminate\Support\ServiceProvider;

final class CrudGeneratorServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeCrudCommand::class,
                DeleteCrudCommand::class,
            ]);
        }
    }
}