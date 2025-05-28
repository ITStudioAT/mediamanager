<?php

namespace Itstudioat\Mediamanager;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Itstudioat\Mediamanager\Commands\MediamanagerCommand;

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
            ->hasConfigFile();
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
