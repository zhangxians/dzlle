<?php
namespace App\Traits\Repository;

trait BaseRepositoryTrait
{

    /**
     * 根据条件批量更新多条数据
     * $this->whereUpdate(array('id'=>1),array('status'=>2));
     * @param $where
     * @param $data
     * @return mixed
     */
    public function whereUpdate(array $where, $data)
    {
        return $this->model->where($where)->update($data);
    }

    /**
     * 通过id获得数据
     * @param $id
     * @param string|array $selects
     * @return mixed
     */
    public function getById($id,$selects = "*")
    {
        if ($selects == '*') {
            $model = $this->model;
            if (isset($model->fields_all)) {
                $selects = $model->fields_all;
            }
        }
        return $this->model->select($selects)->find($id);
    }
    /**
     * 通过id获得数据
     * @param $id
     * @param string|array $selects
     * @return mixed
     */
    public function getByIdWith($id,$with=[],$selects = "*")
    {
        $model=$this->model;
        if($with){
            foreach ($with as $w){
                $model=$model->with($w);
            }
        }
        return $model->select($selects)->find($id);
    }

    /**
     * 通过id获得数据
     * @param array $where
     * @param string $selects
     * @return Model|null|static
     */
    public function getFirst(array $where, $selects = "*")
    {
        if ($selects == '*') {
            $model = $this->model;
            if (isset($model->fields_all)) {
                $selects = $model->fields_all;
            }
        }
        $models=$this->model->where($where)
            ->select($selects)->first();
        if($models!=null){
            return $models;
        }
        return null;
    }

    /**
     * 通过id获得数据
     * @param array $where
     * @param string $selects
     * @return Model|null|static
     */
    public function getFirstWithTrashed(array $where, $selects = "*")
    {
        if ($selects == '*') {
            $model = $this->model;
            if (isset($model->fields_all)) {
                $selects = $model->fields_all;
            }
        }
        $models=$this->model->withTrashed()->where($where)
            ->select($selects)->first();
        if($models!=null){
            return $models;
        }
        return null;
    }

    /**
     * 根据获得数据
     * @param array $where 查询条件  例:array('id','1')
     * @param array $orderby 排序  例:array('id','asc')
     * @return mixed
     */
    public function getWhere(array $where, array $orderby=[],$selects = '*')
    {
        if ($selects == '*') {
            $model = $this->model;
            if (isset($model->fields_all)) {
                $selects = $model->fields_all;
            }
        }
        $res=$this->model->where($where);
        if(count($orderby)>0){
            $res=$res->orderBy($orderby[0], $orderby[1]);
        }
        return $res->select($selects)->get();
    }

    /**
     * 根据获得数据
     * @param array $whereIn 查询条件  例:['id'=>array('id','1')]
     * @return mixed
     */
    public function getWhereIn(array $whereIn, $selects = '*')
    {
        $model = $this->model;
        if ($selects == '*') {
            $selects = $model->get_fields();
        }
        $key=key($whereIn);
        return $model->whereIn($key,$whereIn[$key])->select($selects)->get();
    }


    /**
     * 根据获得数据
     * @param array $whereraw 查询条件  例:'id'='1'
     * @param array $orderby 排序  例:array('id','asc')
     * @return mixed
     */
    public function getWhereRaw($whereraw, array $orderby)
    {
        return $this->model->whereRaw($whereraw)
            ->orderBy($orderby[0], $orderby[1])->get();
    }

    /**
     * 获得分页数据
     * 例：$data["rows"]=UserRepository::getPageList(1,15,['id','desc'],1);
     * @param $pageNum 页码
     * @param $pageSize 每页数据量
     * @param string|array $selects 需要查询的字段
     * @param array $orderby 根据什么排序
     * @param string $selects
     * @return mixed
     */
    public function getPageList($pageNum, $pageSize,array $orderby, $selects = '*')
    {
        if ($selects == '*') {
            $model = $this->model;
            if (isset($model->fields_all)) {
                $selects = $model->fields_all;
            }
        }
        return $this->model->select($selects)
            ->orderBy($orderby[0], $orderby[1])->skip($pageSize * ($pageNum - 1))->take($pageSize)->get();
    }

    /**
     * 获得记录条数
     * @return mixed
     */
    public function getCount()
    {
        return $this->model->count();
    }

    /**
     * 根据条件查询总数量
     * @param $where
     * @return mixed
     */
    public function getCountWhere($where)
    {
        return $this->model->where($where)->count();
    }

    /**
     * 批量插入数据
     * @param array $items 例:array(
     * array('email' => 'taylor@example.com', 'votes' => 0),
     * array('email' => 'dayle@example.com', 'votes' => 0),)
     * @return mixed
     */
    public function batchInsert(array $items)
    {
        return $this->model->insert($items);
    }

    /**
     * 获取分的sql
     * @param $res
     * @param $pageSize
     * @param $pageNum
     * @param $orderby
     * @param string $selects
     * @return mixed
     */
    public function pagetemp($res,$pageSize, $pageNum,$orderby,$selects='*')
    {
        if ($selects == '*') {
            $model = $this->model;
            if (isset($model->fields_all)) {
                $selects = $model->fields_all;
            }
        }
        if($pageSize > 90){
            $pageSize = 90;
        }
        $res = $res->select($selects);

        if(is_array($orderby)&&count($orderby)==2){
            $res = $res->orderBy($orderby[0], $orderby[1]);
        }
        $res = $res->skip($pageSize * ($pageNum - 1))
            ->take($pageSize)->get();
        return $res;
    }

}