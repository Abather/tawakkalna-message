<?php

namespace Abather\TawakkalnaMessage;

use Abather\TawakkalnaMessage\Commands\TawakkalnaMessageCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

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
