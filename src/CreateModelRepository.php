<?php

namespace Zhangx\Dzlle;


class CreateModelRepository
{


    public static function createRepository($models)
    {
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
    public static function cConfig($models)
    {
        $config = [];
        foreach ($models as $m) {
            $mel = str_replace('.php', '', '\App\Models\\' . $m);//处理后字符串
            // 根据model名称实例化，只有设置了table的才生成
            $model = new $mel();
            if (isset($model->table)) {
                // 生成Model中所需配置
                // 生成Model中所需配置
                $tableName = $model->table;
                $columns = \Illuminate\Support\Facades\Schema::getColumnListing($tableName);
                $arr['model'] = $mel;
                $arr['repository'] = '\App\\Repositories\\' . str_replace('.php', '', $m) . 'Repository';
                $config['repositories'][str_replace('.php', '', $m) . 'Repository'] = $arr;
                $config['columns'][$tableName] = $columns;
            }

        }
        $config = $configStr = "<?php\r\n\r\n return " . self::arrayeval($config, false) . ";";
        file_put_contents(config_path('repository.php'), $config);
        if(is_file(config_path('repository.php'))){
            echo PHP_EOL.'配置文件更新成功';
        }else{
            echo PHP_EOL.'配置文件新建成功';
        }
    }

    /**
     * 格式化数组
     * @param $array
     * @param bool $format
     * @param int $level
     * @return string
     */
    protected static function arrayeval($array, $format = false, $level = 0)
    {
        $space = $line = '';
        if (!$format) {
            for ($i = 0; $i <= $level; $i++) {
                $space .= "\t";
            }
            $line = "\n";
        }
        $evaluate = 'Array' . $line . $space . '(' . $line;
        $comma = $space;
        foreach ($array as $key => $val) {
            $key = is_string($key) ? '\'' . addcslashes($key, '\'\\') . '\'' : $key;
            $val = !is_array($val) && (!preg_match('/^\-?\d+$/', $val) || strlen($val) > 12) ? '\'' . addcslashes($val, '\'\\') . '\'' : $val;
            if (is_array($val)) {
                $evaluate .= $comma . $key . '=>' . self::arrayeval($val, $format, $level + 1);
            } else {
                $evaluate .= $comma . $key . '=>' . $val;
            }
            $comma = ',' . $line . $space;
        }
        $evaluate .= $line . $space . ')';
        return $evaluate;
    }


    /**
     * 创建门面
     * @param $models
     */
    protected static function cFacades($models){
        if(!is_dir(app_path('Facades'))){mkdir(app_path('Facades'));}
        foreach ($models as $m){
            $m=str_replace('.php','',$m).'Repository';

            if(is_file(app_path("Facades/$m.php"))){continue;}
            $str='<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;


/**
 * @method static mixed getById($id, $selects = \'*\')
 * @method static mixed getByIdWith($id, $with, $selects = \'*\')
 * @method static mixed getFirst($where, $selects = \'*\', $withTrashed=false, $with=[])
 * @method static mixed getWhere($where, $selects = \'*\', $orderby=[])
 * @method static mixed getWhereWith($where, $selects = \'*\', $with, $orderby=[])
 * @method static mixed getWhereIn($where, $whereIn, $selects = \'*\')
 * @method static mixed getWhereInWith($where, $whereIn, $with=[], $selects = \'*\')
 * @method static mixed getPageList($pageNum, $pageSize,array $orderby, $selects = \'*\')
 * @method static mixed getCount()
 * @method static mixed getPageListWhere($pageNum, $pageSize, array $where,array $orderby,$selects = \'*\')
 * @method static mixed getCountWhere($where)
 * @method static mixed upsert($inputs, $key=\'id\', $isValidate=false)
 * @method static mixed whereInUpdate($where, $whereIn=[], $data)
 * @method static mixed batchInsert(array $items)
 * @method static mixed pagetemp($res,$pageSize, $pageNum,$orderby,$selects=\'*\')
 * @method static mixed getModel()

 */

class '.$m.' extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \''.$m.'\';
    }
}';
            file_put_contents(app_path("Facades/$m.php"),$str);
            echo PHP_EOL."门面 $m 新建成功";

        }
    }


    /**
     * 创建仓库
     * @param $models
     */
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
            echo PHP_EOL."仓库 $m 新建成功";
        }
    }


    /**
     * 移动公共方法
     */
    protected static function mvRepository(){
        self::fileCopy(__DIR__.'/files/CommonRepository.php',app_path('Repositories/CommonRepository.php'));
        self::fileCopy(__DIR__.'/files/Traits/Model/BaseModelTrait.php',
            app_path('Traits/Model/BaseModelTrait.php'));
        self::fileCopy(__DIR__.'/files/Traits/Repository/BaseRepositoryTrait.php',
            app_path('Traits/Repository/BaseRepositoryTrait.php'));
    }


    /**
     * 文件移动
     * Created by ZX.
     * @param $file
     * @param $to
     */
    public static function fileCopy($file,$to){
        if(!is_dir(dirname($to))){mkdir(dirname($to),777,true);}
        if(!is_file($to)){
            file_put_contents($to,file_get_contents($file));
            echo PHP_EOL.' 生成'.$to;
        }
    }



}
