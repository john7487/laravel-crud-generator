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