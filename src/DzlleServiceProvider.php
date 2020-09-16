<?php

namespace Zhangx\Dzlle;

use Illuminate\Support\ServiceProvider;
use zhangx\dzlle\Commands\DzlleCommand;

class DzlleServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Actual provider
     *
     * @var \Illuminate\Support\ServiceProvider
     */
    protected $provider;

    /**
     * Create a new service provider instance.
     *
     * @
     * */
    public function __construct($app)
    {
        parent::__construct($app);
    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {

            $this->commands([
                DzlleCommand::class,
            ]);
        }
    }

    /**
     * Register the service provider.
     * @return void
     */
    public function register()
    {
        $this->registerRbacRepository();
    }


    /**
     * 注册 repository
     */
    public function registerRbacRepository()
    {
        if(is_file(config_path('repository.php'))){
            $repositories=config('repository.repositories');
            foreach ($repositories as $repositoryName=>$repository) {
                $model = $repository['model'];
                $repository = $repository['repository'];
                $this->app->singleton($repositoryName, function ($app) use ($model, $repository) {
                    $m = new $model();
                    $validator = $app['validator'];
                    return new $repository($m, $validator);
                });
            } 
        }

    }


}
