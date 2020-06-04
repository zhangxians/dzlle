<?php

namespace Zhangx\Dzlle;


class CreateException{


    // 移动异常类
    public static function cpException(){
        //移动异常类
        CreateModelRepository::fileCopy(__DIR__.'/files/Exceptions/DataNotException.php',app_path('Exceptions/DataNotException.php'));
        CreateModelRepository::fileCopy(__DIR__.'/files/Exceptions/ForbiddenException.php',app_path('Exceptions/ForbiddenException.php'));
        CreateModelRepository::fileCopy(__DIR__.'/files/Exceptions/OpDataException.php',app_path('Exceptions/OpDataException.php'));
        CreateModelRepository::fileCopy(__DIR__.'/files/Exceptions/OptionFailException.php',app_path('Exceptions/OptionFailException.php'));
        CreateModelRepository::fileCopy(__DIR__.'/files/Exceptions/ProgramException.php',app_path('Exceptions/ProgramException.php'));
        CreateModelRepository::fileCopy(__DIR__.'/files/Exceptions/ValidateException.php',app_path('Exceptions/ValidateException.php'));
    }

}
