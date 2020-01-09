<?php

namespace zhangx\dzlle;

class CreateModelRepository{


    public static function createRepository($models){
        self::cConfig($models);
        self::cFacades($models);
        self::cRepository($models);
        self::mvRepository();
    }

    /**
     * 生成配置文件
     * Created by ZX.
     * @param $models
     */
    protected static function cConfig($models){
        $configs="<?php 
        
        return [ 
            'models' => [
         ";
        foreach ($models as $m){
            $m=str_replace('.php','',$m);
            $configs.="     '$m' => 'App\Models\\$m',
            ";
        }
        $configs.= "
            ],
         
         'page-limit' => 20,
         ]; ";
        file_put_contents(config_path('repository.php'),$configs);
        dump('配置文件新建成功');
    }

    protected static function cFacades($models){
        if(!is_dir(app_path('Facades'))){mkdir(app_path('Facades'));}
        foreach ($models as $m){
            $m=str_replace('.php','',$m).'Repository';

            if(is_file(app_path("Facades/$m.php"))){continue;}
            $str="<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class $m extends Facade
{
    protected static function getFacadeAccessor()
    {
        return '$m';
    }
}";
            file_put_contents(app_path("Facades/$m.php"),$str);
            dump("门面 $m 新建成功");

        }
    }


    protected static function cRepository($models){
        if(!is_dir(app_path('Repositories'))){mkdir(app_path('Repositories'));}
        foreach ($models as $m){
            $m=str_replace('.php','',$m).'Repository';

            $str="<?php namespace App\Repositories;

/**
 * 
 * Class $m
 * @package App\Repositories
 */
class $m extends CommonRepository {

  
}";
            if(is_file(app_path("Repositories/$m.php"))){continue;}
            file_put_contents(app_path("Repositories/$m.php"),$str);
            dump("仓库 $m 新建成功");
        }
    }


    protected static function mvRepository(){
        self::fileCopy(__DIR__.'/files/CommonRepository.php',app_path('Repositories/CommonRepository.php'));
        self::fileCopy(__DIR__.'/files/Traits/Repository/BaseRepositoryTrait.php',app_path('Traits/Repository/BaseRepositoryTrait.php'));
    }


    /**
     * 文件移动
     * Created by ZX.
     * @param $file
     * @param $to
     */
    public static function fileCopy($file,$to){
        if(!is_dir(dirname($to))){mkdir(dirname($to),777,true);}
        file_put_contents($to,file_get_contents($file));
        dump("新建 $to");
    }



}
