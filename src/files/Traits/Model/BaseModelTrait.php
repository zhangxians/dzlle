<?php
namespace App\Traits\Model;


use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

trait BaseModelTrait
{
    /**
     * 获取表字段
     * @return array|\Illuminate\Config\Repository|mixed
     */
    public function getColumns(){
        if($this->fields_all??false){
            return $this->fields_all;
        }
        $newColumns=[];
        $tableName=$this->table;
        $columns = config('repository.columns.'.$tableName) ?? [];
        if(method_exists($this,'exceptColumns'))
        {
            if(isset($columns)){
                foreach ($columns as $item){
                    if(!in_array($item,$this->exceptColumns())){
                        array_push($newColumns,$item);
                    }
                }
            }
        }else {
            return $columns;
        }
        return $newColumns;
    }

    /**
     * 重载getFillable
     * @return array
     */
    public function getFillable()
    {
        if($this->fill_able??false){
            return $this->fill_able;
        }
        $newColumns=[];
        $tableName=$this->table;
        $columns = config('repository.columns.'.$tableName);
        if(isset($columns)){
            foreach ($columns as $item){
                if(!in_array($item,['id','deleted_at','created_at','updated_at'])){
                    array_push($newColumns,$item);
                }
            }
        }
        return $newColumns;
    }
}
