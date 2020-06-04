<?php

namespace Zhangx\Dzlle;

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
    public static function cConfig($models){
        $config=[];
        foreach ($models as $m){
            // 根据model名称实例化，只有设置了table的才生成
            $model=new $m();
            if(isset($model->table)){
                // 生成Model中所需配置
                // 生成Model中所需配置
                $tableName=$model->table;
                $columns = \Illuminate\Support\Facades\Schema::getColumnListing($tableName);
                $config['columns'][$tableName]=$columns;
            }

        }

        $config =  $configStr="<?php\r\n\r\n return ".arrayeval($config,false).";";
        file_put_contents(config_path('model-service.php'),$config);
        dump('配置文件新建成功');
    }

    // 创建门面
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


    // 创建仓库
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


    // 移动公共方法
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
