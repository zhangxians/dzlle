<?php

if ( ! function_exists('json_response')) {
    /**
     * 返回给页面json的信息，ajax操作时返回的提示信息
     * @param $code
     * @param $msg
     * @param null $data
     * @return string
     */
    function json_response($code,$msg,$data=null){
        return response()->json(array('code'=>$code,'msg'=>$msg,'data'=>$data));
    }
}
if ( ! function_exists('json_success')) {
    function json_success($msg='操作成功!',$data=null){
        return json_response(0,$msg,$data);
    }
}
if ( ! function_exists('json_fail')) {
    function json_fail($msg='操作失败!',$data=null){
        return json_response(1,$msg,$data);
    }
}
if ( ! function_exists('json_page')) {
    function json_page($data,$count,$msg='',$code=0){
        //TODO::file1 file2
//        if(!is_array($data)){
//            $data=$data->toArray();
//        }
//        xss_filter($data);
        return response()->json(array('data'=>$data,'count'=>$count,'code'=>$code,'msg'=>$msg));
    }
}



if ( ! function_exists('to_json_response')) {
    /**
     * 将对象转换成，页面json的信息，ajax操作时返回的提示信息
     * @param $obj Repository返回的自定义对象
     * @return string
     */
    function to_json_response($obj){
        $data=isset($obj->data)?$obj->data:null;
        if($obj->status){
            return json_success($obj->msg,$data);
        }else{
            return json_fail($obj->msg,$data);
        }
    }
}

if ( ! function_exists('json_success_fail')) {
    /**
     * 将对象转换成，页面json的信息，ajax操作时返回的提示信息
     * @param $model
     * @return string
     */
    function json_success_fail($model,$msg1=null,$msg2=null){
        if($model){
            return json_success($msg2===null?'操作成功！':$msg2,json_encode($model));
        }else{
            return json_fail($msg1===null?'操作失败！':$msg1);
        }
    }
}
