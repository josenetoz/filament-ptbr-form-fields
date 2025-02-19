<?php

namespace Jozenetoz\FilamentPtbrFormFields\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Jozenetoz\FilamentPtbrFormFields\FilamentPtbrFormFieldsServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Jozenetoz\\FilamentPtbrFormFields\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            FilamentPtbrFormFieldsServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        /*
        $migration = include __DIR__.'/../database/migrations/create_filament-ptbr-form-fields_table.php.stub';
        $migration->up();
        */
    }
}
