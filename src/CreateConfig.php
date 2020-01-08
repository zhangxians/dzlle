<?php

namespace zhangx\dzlle;

class CreateConfig{


    /**
     * 生成配置文件
     * Created by ZX.
     * @param $models
     */
    public static function storeConfig($models){
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
    }



}
