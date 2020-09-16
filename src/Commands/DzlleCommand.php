<?php

namespace zhangx\dzlle\Commands;

use Illuminate\Console\Command;
use zhangx\dzlle\CreateException;
use zhangx\dzlle\CreateHelper;
use zhangx\dzlle\CreateModelRepository;

class DzlleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dzlle-init {param1=true}';

    /**
     * The console command description.
     * 项目初始化 
     * 
     * 1、根据 Models 文件夹中 model 生成 Facades 对应门面及 Repositories 中对应数据库操作类。
     * 2、复制数据库公共操作方法到 Traits 中。
     * 3、复制自定义辅助方法到 Helpers 中 TODO:需自己在 composer.json 中 进行注册。
     * 4、辅助自定义异常类到 Exceptions 中。
     * 
     * @var string
     */
    protected $description = '项目初始化';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if(!is_dir(app_path('/Models'))){mkdir(app_path('/Models'));}
        $models=scandir(app_path('/Models'));
        foreach ($models as $k=>$model){
            if(!strpos($model,'php')){
                unset($models[$k]);
            }
        }
        $pazam = $this->argument('param1')??false;
        if($pazam==='model'){
            CreateModelRepository::createRepository($models); // 新建repository
          //  echo PHP_EOL.'更新表字段成功';
        }else{
            CreateModelRepository::createRepository($models); // 新建repository
            CreateException::cpException(); // 移动错误方法
            CreateHelper::cpHelpers();  // 移动helper函数
            $this->echoSuccess();
        }

    }
    
    public function echoSuccess(){
        $string = PHP_EOL.'项目初始化成功，请依次做以下操作：
        1、在项目根目录 composer.json 文件中 autoload 下添加 
            "files": [
                "app/Helpers/functions.php"
            ]
        2、执行 composer dump-autoload
        3、在 model class 中添加  use \App\Traits\Model\BaseModelTrait;
        ';
        echo $string;
    }
}
