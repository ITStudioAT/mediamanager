<?php

namespace Itstudioat\Mediamanager;

use Itstudioat\Mediamanager\Commands\MediamanagerCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class MediamanagerServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('mediamanager')
            ->hasConfigFile()
            ->hasRoutes(['api', 'web'])
            ->hasCommand(MediamanagerCommand::class);
    }

    public function bootingPackage()
    {
        // Optional: auto-publish JS routes into main app
        $this->publishes([
            __DIR__ . '/../resources/js' => resource_path('vendor/mediamanager/js'),
            __DIR__ . '/../resources/routes' => resource_path('vendor/mediamanager/routes'),
            __DIR__ . '/../resources/plugins' => resource_path('vendor/mediamanager/plugins'),
            __DIR__ . '/../resources/css' => resource_path('vendor/mediamanager/css'),
        ], 'mediamanager-all');
    }
}
