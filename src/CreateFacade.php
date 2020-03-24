<?php

namespace Zhangx\Dzlle;

class CreateFacade{



    public static function createFacades($models){
        if(!is_dir(app_path('Facades'))){mkdir(app_path('Facades'));}
        foreach ($models as $m){
            $m=str_replace('.php','',$m).'Repository';

            if(app_path("Facades/$m.php")){continue;}
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

        }
    }

}
