<?php

namespace Zhangx\Dzlle;

class CreateRepository{



    public static function createRepository($models){
        if(!is_dir(app_path('Repositories'))){mkdir(app_path('Repositories'));}
        self::storeCommonRepository();
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
            if(app_path("Repositories/$m.php")){continue;}
            file_put_contents(app_path("Repositories/$m.php"),$str);

        }
    }



    protected static function storeCommonRepository(){


        $commonRepository='<?php namespace App\Repositories;

/**
 * 数据库操作基类，所有表都可以用
 * 通过子类继承来调用
 * 重用
 */
use App\Exceptions\ValidateException;
use App\Traits\Repository\BaseRepositoryTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Factory;

abstract class CommonRepository
{
    use BaseRepositoryTrait;

    /**
     * 模型实例
     * @var Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * The validator factory instance.
     * @var \Illuminate\Validation\Factory
     */
    protected $validator;

    /**
     * Create a new instance.
     * @param Model $model
     * @param Factory $validator
     */
    public function __construct(Model $model, Factory $validator)
    {
        $this->model = $model;
        $this->validator = $validator;
    }

    /**
     *  $model->rules规则过滤 （对传入参数的过滤规则，定义在Models下的各类中）
     * @param null $query
     * @return array
     */
    public function rules($query = null)
    {
        $model = $this->model;
        // get rules from the model if set
        if (isset($model->rules)) {
            $rules = $model->rules;
        } else {
            $rules = [];
        }
        // if the there are no rules
        if (!is_array($rules) || !$rules) {
            // return an empty array
            return [];
        }
        // if the query is empty
        if (!$query) {
            // return all of the rules
            return array_filter($rules);
        }
        // return the relevant rules
        return array_filter(array_only($rules, $query));
    }

    /**
     * 验证数据  根据上面的验证规则，验证传入的参数是否合法
     * $model->messages如果不合法返回错误消息（错误消息定义再Models下面各类中）
     * @param array $data
     * @param null $rules
     * @param bool $custom
     * @param bool $exception 是否抛出异常
     * @return bool
     * @throws ValidateException
     */
    public function validate(array $data, $rules = null, $custom = false,$exception=true)
    {
        if (!$custom) {
            $rules = $this->rules($rules);
        }
        $model = $this->model;
        if (!isset($model->messages)) {
            $model->messages = [];
        }
        $val=$this->validator->make($data, $rules, $model->messages);
        if ($val->fails()) {
            if($exception)
                throw new ValidateException(join(\'<br/>\',$val->errors()->all()));
            else
                return join(\',\',$val->errors()->all());
        }
        if(!$exception){
            return true;
        }
    }

    /**
     * 删除数据，软删除
     * @param int $id
     * @return bool|null
     */
    public function destroy($id)
    {
        $result=false;
        if(is_array($id)){ //批量删除
            $items = $this->model->whereIn(\'id\',$id)->get();
            foreach($items as $item){
                $result = $item->delete();
            }
//            foreach($items as $item){
//                if(($user_id&&$item->creator==$user_id)||(!$user_id)){
//                    $result = $item->delete();
//                }
//            }
        }else{
            $item = $this->model->find($id);
            if(!$item){
                return obj_info(false,\'没有找到这条数据，可能已经删除了！\');
            }
            $result = $item->delete();
        }
        if (!$result) {
            return obj_info(false,  \'删除失败！\');
        }
        return obj_info(true,  \'删除成功！\');
    }

    /**
     * 恢复数据
     * @param $id
     * @return array
     */
    public function restore($id)
    {
        $result=false;
        if(is_array($id)){ //批量恢复
            $items = $this->model->onlyTrashed()->whereIn(\'id\',$id)->get();
            foreach($items as $item){
                $result = $item->restore();
            }
        }else{
            $item = $this->model->onlyTrashed()->find($id);
            if(!$item){
                return obj_info(false,  \'没有找到这条数据，可能已经恢复了！\');
            }
            $result = $item->restore();
        }
        if (!$result) {
            return obj_info(false,  \'恢复失败！\');
        }
        return obj_info(true,  \'恢复成功！\');
    }


}';
        file_put_contents(app_path('Repositories/CommonRepository.php'),$commonRepository);
    }


}
