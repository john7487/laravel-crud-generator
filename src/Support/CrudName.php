<?php

declare(strict_types=1);

namespace AltenJohn\CrudGenerator\Support;

use Illuminate\Support\Str;
use InvalidArgumentException;

final readonly class CrudName
{
    public function __construct(
        public string $version,
        public string $model,
    ) {}

    public static function from(string $name): self
    {
        $name = trim($name, '/');

        if (! str_contains($name, '/')) {
            return new self(
                version: 'V1',
                model: Str::studly($name),
            );
        }

        if (! preg_match('/^(V\d+)\/([^\/]+)$/', $name, $matches)) {
            throw new InvalidArgumentException(
                'CRUD name must use the format Product or V1/Product.'
            );
        }

        return new self(
            version: $matches[1],
            model: Str::studly($matches[2]),
        );
    }

    public function path(): string
    {
        return $this->version.'/'.$this->model;
    }

    public function routePath(): string
    {
        return Str::lower($this->version);
    }

    public function table(): string
    {
        return Str::snake(
            Str::pluralStudly($this->model),
        );
    }

    public function variable(): string
    {
        return Str::camel($this->model);
    }

    public function controllerNamespace(): string
    {
        return '\\'.$this->version;
    }
}