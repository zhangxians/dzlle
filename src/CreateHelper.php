<?php

namespace Zhangx\Dzlle;


class CreateHelper{


    public static function mvHelpers(){
        //图片处理扩展
        CreateModelRepository::fileCopy(__DIR__.'/files/Traits/Controller/ImageTrait.php',app_path('Traits/Controller/ImageTrait.php'));

        //移动助手函数
        CreateModelRepository::fileCopy(__DIR__.'/files/Helpers/array.php',app_path('Helpers/array.php'));
        CreateModelRepository::fileCopy(__DIR__.'/files/Helpers/functions.php',app_path('Helpers/functions.php'));
        CreateModelRepository::fileCopy(__DIR__.'/files/Helpers/json.php',app_path('Helpers/json.php'));
        CreateModelRepository::fileCopy(__DIR__.'/files/Helpers/logger.php',app_path('Helpers/logger.php'));
        CreateModelRepository::fileCopy(__DIR__.'/files/Helpers/object.php',app_path('Helpers/object.php'));
        CreateModelRepository::fileCopy(__DIR__.'/files/Helpers/qrcode.php',app_path('Helpers/qrcode.php'));
        CreateModelRepository::fileCopy(__DIR__.'/files/Helpers/string.php',app_path('Helpers/string.php'));
    }




}
