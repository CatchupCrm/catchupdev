<?php

namespace Modules\BalanceSheet\Providers;

use App\Providers\AuthServiceProvider;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;

class BalanceSheetServiceProvider extends AuthServiceProvider
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
        \Modules\BalanceSheet\Models\BalanceSheet::class => \Modules\BalanceSheet\Policies\BalanceSheetPolicy::class,
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
            __DIR__.'/../Config/config.php' => config_path('balancesheet.php'),
        ]);
        $this->mergeConfigFrom(
            __DIR__.'/../Config/config.php', 'balancesheet'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = base_path('resources/views/modules/balancesheet');

        $sourcePath = __DIR__.'/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ]);

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/balancesheet';
        }, \Config::get('view.paths')), [$sourcePath]), 'balancesheet');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = base_path('resources/lang/modules/balancesheet');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'balancesheet');
        } else {
            $this->loadTranslationsFrom(__DIR__ .'/../Resources/lang/en', 'balancesheet');
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
