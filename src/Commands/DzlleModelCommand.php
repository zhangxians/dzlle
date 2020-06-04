<?php

namespace zhangx\dzlle\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Zhangx\Dzlle\CreateModelRepository;

/*
   php artisan dzlle:init-model
 */
class DzlleModelCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dzlle:init-model';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

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


    }

}
