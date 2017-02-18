<?php

namespace Modules\Banking\Providers;

use App\Providers\AuthServiceProvider;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;

class BankingServiceProvider extends AuthServiceProvider
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
        \Modules\Banking\Models\Banking::class => \Modules\Banking\Policies\BankingPolicy::class,
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
            __DIR__.'/../Config/config.php' => config_path('banking.php'),
        ]);
        $this->mergeConfigFrom(
            __DIR__.'/../Config/config.php', 'banking'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = base_path('resources/views/modules/banking');

        $sourcePath = __DIR__.'/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ]);

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/banking';
        }, \Config::get('view.paths')), [$sourcePath]), 'banking');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = base_path('resources/lang/modules/banking');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'banking');
        } else {
            $this->loadTranslationsFrom(__DIR__ .'/../Resources/lang/en', 'banking');
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
