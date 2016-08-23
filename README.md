# Simple Artisan Command to Develop Package

## Installation

	composer require cleaniquecoders/packager

## Configuration

Open up `app/Console/Commands/Kernel.php` and add the following:

```php
protected $commands = [
    \CleaniqueCoders\Packager\Console\Commands\Packager\Skeleton::class,
    \CleaniqueCoders\Packager\Console\Commands\Packager\Scaffold::class
];
```

## Terminal

Open your terminal and run `php artisan` to verify the command `packager` available.

## Usage

Type in `php artisan packager:skeleton` in your terminal, you will get the following result:

	Creating package...

	Updating Composer...
	Updating Service Provider...
	Updating README.md...

	Creating package for Vendor/Package is done.

The package is generated `/path/to/project/packages` directory.

You may run `php artisan speed:scaffold vendor package model` for scaffold generator for your packages.