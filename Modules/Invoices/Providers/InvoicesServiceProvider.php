<?php

namespace Modules\Invoices\Providers;

use App\Providers\AuthServiceProvider;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;

class InvoicesServiceProvider extends AuthServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        \Modules\Invoices\Models\Invoices::class => \Modules\Invoices\Policies\InvoicesPolicy::class,
    ];

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot(GateContract $gate)
    {
        parent::boot($gate);
        
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            __DIR__.'/../Config/config.php' => config_path('invoices.php'),
        ]);
        $this->mergeConfigFrom(
            __DIR__.'/../Config/config.php', 'invoices'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = base_path('resources/views/modules/invoices');

        $sourcePath = __DIR__.'/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ]);

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/invoices';
        }, \Config::get('view.paths')), [$sourcePath]), 'invoices');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = base_path('resources/lang/modules/invoices');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'invoices');
        } else {
            $this->loadTranslationsFrom(__DIR__ .'/../Resources/lang/en', 'invoices');
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
