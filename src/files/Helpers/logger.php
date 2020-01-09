<?php


if ( ! function_exists('mylogger')) {
    function mylogger($type,$class,$method,$msg, $data=''){
        $class_method=$class.'/'.$method;
        if(strlen($msg)>1000){
            $msg=substr($msg,0,1000);
        }
        if(strlen($data)>1000){
            $data=substr($data,0,1000);
        }
        \App\Jobs\LogsJob::dispatch(compact('type','class_method','class_method','msg','data'));
    }
}

if ( ! function_exists('log_debug')) {
    function log_debug($class,$method,$msg, $data=''){
        $type=1;
        mylogger($type,$class,$method,$msg, $data);
    }
}
if ( ! function_exists('log_info')) {
    function log_info($class,$method,$msg, $data=''){
        $type=2;
        mylogger($type,$class,$method,$msg, $data);
    }
}
if ( ! function_exists('log_warn')) {
    function log_warn($class,$method,$msg, $data=''){
        $type=3;
        mylogger($type,$class,$method,$msg, $data);
    }
}
if ( ! function_exists('log_error')) {
    function log_error($class,$method,$msg, $data=''){
        $type=4;
        mylogger($type,$class,$method,$msg, $data);
    }
}
if ( ! function_exists('log_exception')) {
    function log_exception(\Exception $e, $data=''){
        $type=4;
        $file='';
        $line='';
        $url='';
        try{
            $file=$e->getFile();
            $line=$e->getLine();
            $url=request()->fullUrl();
        }catch (\Exception $e){}
        \Illuminate\Support\Facades\Log::error("【url={$url}】   【{$file}={$line}】 【{$e->getMessage()}】 【data={$data}】/r/n{$e->getTraceAsString()}");
        mylogger($type,'File:'.$file,'Line:'.$line,$e->getMessage(), $data);

    }
}
