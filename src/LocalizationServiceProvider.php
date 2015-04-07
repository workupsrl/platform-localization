<?php

namespace Stevebauman\Localization;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\AliasLoader;
use Cartalyst\Extensions\Extension;
use Cartalyst\Support\ServiceProvider;

/**
 * Class LocalizationServiceProvider
 * @package Stevebauman\Localization
 */
class LocalizationServiceProvider extends ServiceProvider
{
    /**
     * Register the localization repositories
     */
    public function register()
    {
        /*
         * Register the Translation class alias
         */
        AliasLoader::getInstance()->alias(
            'Translation', 'Stevebauman\Translation\Facades\Translation'
        );

        /*
         * Publish the translation config into the localization config
         */
        $this->publishes([
            base_path('/vendor/stevebauman/translation/src/config') => __DIR__ . '/../config/'
        ], 'config');

        /*
         * Publish the translation config into the laravel config
         */
        $this->publishes([
            __DIR__ . '/../config/config.php' => config_path('translation.php'),
        ], 'config');

        /*
         * Publish the translation migrations into the localization migrations
         */
        $this->publishes([
            base_path('/vendor/stevebauman/translation/src/migrations') => __DIR__ . '/../database/migrations/',
        ], 'migrations');

        /*
         * Prepare the translataion migrations
         */
        $this->prepareMigrations();

        /*
         * Bind the required localization instances
         */
        $this->bindRequired();
    }

    /**
     * Publishes the migrations from the translation package
     * to be published into the localization migrations folder
     * to be ran when the extension is installing
     */
    private function prepareMigrations()
    {
        /*
         * If the extension is installing, we'll publish the localization
         * migrations to our localization extension directory
         */
        Extension::installing(function(Extension $extension)
        {
            $extension->getSlug('stevebauman/localization');

            if($extension->getNamespace() === 'Stevebauman\Localization')
            {
                Artisan::call('vendor:publish', array(
                    '--provider' => get_class($this),
                ));
            }
        });
    }

    /**
     * Binds the required localization instances
     */
    private function bindRequired()
    {
        // Locale
        $this->bindIf('localization.locale', 'Stevebauman\Localization\Repositories\Locale\LocaleRepository');

        $this->bindIf('localization.locale.handler.event', 'Stevebauman\Localization\Handlers\Locale\LocaleEventHandler');

        $this->bindIf('localization.locale.handler.data', 'Stevebauman\Localization\Handlers\Locale\LocaleDataHandler');

        $this->bindIf('localization.locale.validator', 'Stevebauman\Localization\Validator\Locale\LocaleValidator');

        // Translation
        $this->bindIf('localization.translation', 'Stevebauman\Localization\Repositories\Translation\TranslationRepository');

        $this->bindIf('localization.translation.handler.event', 'Stevebauman\Localization\Handlers\Translation\TranslationEventHandler');

        $this->bindIf('localization.translation.handler.data', 'Stevebauman\Localization\Handlers\Translation\TranslationDataHandler');

        $this->bindIf('localization.translation.validator', 'Stevebauman\Localization\Validator\Translation\TranslationValidator');
    }
}