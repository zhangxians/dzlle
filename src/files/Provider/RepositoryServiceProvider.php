<?php

namespace App\Providers;

use App\Repositories\CategoryRepository;
use App\Repositories\Rbac\PermissionRepository;
use App\Repositories\Rbac\RoleRepository;
use App\Repositories\Rbac\UserRepository;
use App\Repositories\WebSiteRepository;
use App\Repositories\WeiXin\WxArticleRepository;
use App\Repositories\WeiXin\WxCommentRepository;
use App\Repositories\WeiXin\WxGzhRepository;
use App\Repositories\WeiXin\WxResourceRepository;
use App\Repositories\WeiXin\WxUserRepository;
use App\Repositories\WeiXin\WxAutoReplyRepository;
use Illuminate\Support\ServiceProvider;


class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerRbacRepository();
    }



    public function registerRbacRepository()
    {
        $repositories=config('model-service.repositories');

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
