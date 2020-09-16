<?php
namespace App\Traits\Repository;

trait BaseRepositoryTrait
{

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
            $selects = $model->getColumns();
        }
        return $this->model->select($selects)->find($id);
    }

    /**
     * 通过id获得数据
     * @param $id
     * @param array $with
     * @param string|array $selects
     * @return mixed
     */
    public function getByIdWith($id,$with=[],$selects = "*")
    {
        $model=$this->model;
        if ($selects == '*') {
            $selects = $model->getColumns();
        }
        if($with){
            $model = $model->with($with);
        }
        return $model->select($selects)->find($id);
    }

    /**
     * 通过id获得数据
     * @param array $where
     * @param string $selects
     * @param bool $withTrashed
     * @param array $with
     * @return null|static
     */
    public function getFirst(array $where, $selects = "*",$withTrashed=false,$with=[])
    {
        $model = $this->model;

        if ($selects == '*') {
            $selects = $model->getColumns();
        }

        if($withTrashed){
            $model = $model->withTrashed();
        }
        if($with){
            $model = $model->with($with);
        }
        $models=$model->where($where)
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
     * @param string $selects
     * @return mixed
     */
    public function getWhere($where,$selects = '*',$orderby=[])
    {
        if ($selects == '*') {
            $model = $this->model;
            $selects = $model->getColumns();
        }
        $res=$this->model->where($where);
        if(count($orderby)>0){
            $res=$res->orderBy($orderby[0], $orderby[1]);
        }
        return $res->select($selects)->get();
    }


    /**
     * 根据获得数据 With
     * @param array $where 查询条件  例:array('id','1')
     * @param array $orderby 排序  例:array('id','asc')
     * @param array $with
     * @param string $selects
     * @return mixed
     */
    public function getWhereWith($where,$selects = '*',$with=[],$orderby=[])
    {
        $model = $this->model;
        if ($selects == '*') {
            $selects = $model->getColumns();
        }
        $model = $model->where($where);
        if(count($orderby)>0){
            $model = $model->orderBy($orderby[0], $orderby[1]);
        }
        if($with){
            $model = $model->with($with);
        }
        return $model->select($selects)->get();
    }

    /**
     * 根据获得数据
     * @param array $whereIn 查询条件  例:['id'=>array('id','1')]
     * @param array $where
     * @param string $selects
     * @return mixed
     */
    public function getWhereIn($where=[],$whereIn, $selects = '*')
    {
        $model = $this->model;
        if ($selects == '*') {
            $selects = $model->getColumns();
        }
        if($where){
            $model = $model->where($where);
        }
        $key=key($whereIn);
        return $model->whereIn($key,$whereIn[$key])->select($selects)->get();
    }


    /**
     * 根据获得数据
     * @param array $whereIn 查询条件  例:['id'=>array('id','1')]
     * @param array $where
     * @param array $with
     * @param string $selects
     * @return mixed
     */
    public function getWhereInWith($where=[],$whereIn,$with=[], $selects = '*')
    {
        $model = $this->model;
        if ($selects == '*') {
            $selects = $model->getColumns();
        }
        if($where){
            $model = $model->where($where);
        }
        if($with){
            $model = $model->with($with);
        }
        $key=key($whereIn);
        return $model->whereIn($key,$whereIn[$key])->select($selects)->get();
    }

    /**
     * 获得分页数据
     * 例：$data["rows"]=UserRepository::getPageList(1,15,['id','desc'],1);
     * @param $pageNum
     * @param $pageSize
     * @param $orderby
     * @param string $selects
     * @return mixed
     */
    public function getPageList($pageNum, $pageSize,$orderby,$selects = '*')
    {
        $model = $this->model;

        if ($selects == '*') {
            $selects = $model->getColumns();
        }
        $model = $model->select($selects)
            ->orderBy($orderby[0], $orderby[1])
            ->skip($pageSize * ($pageNum - 1))
            ->take($pageSize)
            ->get();
        return $model;
    }

    /**
     * 获得数据表记录条数
     * @return mixed
     */
    public function getCount()
    {
        return $this->model->count();
    }

    /**
     * 分页条件查询
     * @param $pageNum
     * @param $pageSize
     * @param $where
     * @param string $selects
     * @return mixed
     */
    public function getPageListWhere($pageNum, $pageSize, array $where, array $orderby, $selects = '*')
    {
        $model = $this->model;
        if ($selects == '*') {
            $selects = $model->getColumns();
        }
        if (count($orderby) > 0) {
            $model = $model->orderBy($orderby[0], $orderby[1]);
        }
        return $model->select($selects)
            ->where($where)
            ->skip($pageSize * ($pageNum - 1))->take($pageSize)->get();
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
     * 更新数据
     * @param $inputs //数据操作的主要
     * @param $key
     * @param bool $isValidate
     * @return bool
     * @throws \App\Exceptions\ValidateException
     */
    public function upsert($inputs,$key='id', $isValidate=false){
        $model = $this->model;
        if($isValidate){
            $this->validate($inputs);
        }
        if(isset($inputs[$key])){
            $model = $model->where($key,$inputs[$key])->first();
        }
        $model->fill($inputs);
        $res = $model->save();
        return $res?$model:false;
    }

    /**
     * 根据条件批量更新多条数据
     * $this->whereUpdate(array('id'=>1),array('status'=>2));
     * @param $where
     * @param $whereIn
     * @param $data
     * @return mixed
     */
    public function whereUpdate($where, $whereIn=[], $data)
    {
        $model = $this->model;
        $model = $model->where($where);
        if($whereIn){
            $key=key($whereIn);
            $model = $model->whereIn($key,$whereIn[$key]);
        }
        return $model->update($data);
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
                $selects = $model->getColumns();
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


    /**
     * 返回当前model
     * @return mixed
     */
    public function getModel(){
        return $this->model;
    }

}