# Laravel CRUD Generator

Installation
```php
composer require altenjohn/laravel-crud-generator
```
Usage
```php
php artisan make:crud Project
```
With migration
```php
php artisan make:crud Project -m
```
Force overwrite
```php
php artisan make:crud Project --force
```
API V1

```php
php artisan make:crud V1/Project
```

Migration + API V2
```php
php artisan make:crud V2/Project -m
```

initial setup app dari orebarranco/laravel-api-starter-kit
tambahkan pada akhir file routes/api/v1.php

```php
$routeFiles = glob(__DIR__.'/v1/*.php') ?: [];

sort($routeFiles);

foreach ($routeFiles as $routeFile) {
    require $routeFile;
}
```

pada file app/Traits/ApiResponse.php
pada bagian 

```php
    protected function noData(int $status = Response::HTTP_OK): JsonResponse
    {
        return response()->json(
            ['meta' => $this->baseMeta()],
            $status,
            ['Content-Type' => 'application/vnd.api+json'],
        );
    }
```

ubah menjadi

```php
    /**
     * @param  array<string, mixed>  $meta
     */
    protected function noData(int $status = Response::HTTP_OK, array $meta = []): JsonResponse
    {
        return response()->json(
            $meta
                ? ['meta' => array_merge($this->baseMeta(), $meta)]
                : ['meta' => $this->baseMeta()],
            $status,
            ['Content-Type' => 'application/vnd.api+json'],
        );
    }
```