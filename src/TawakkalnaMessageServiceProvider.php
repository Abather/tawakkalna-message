<?php

namespace Abather\TawakkalnaMessage;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Abather\TawakkalnaMessage\Commands\TawakkalnaMessageCommand;

class TawakkalnaMessageServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('tawakkalna-message')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_tawakkalna_message_table')
            ->hasCommand(TawakkalnaMessageCommand::class);
    }
}
