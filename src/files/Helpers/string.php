<?php

if ( ! function_exists('empty2zero')) {
    function empty2zero($str){
        if(empty($str)){
            return 0;
        }else{
            return $str;
        }
    }
}

if ( ! function_exists('is_idcard')) {
    function is_idcard( $id )
    {
        $id = strtoupper($id);
        $regx = "/(^\d{15}$)|(^\d{17}([0-9]|X)$)/";
        $arr_split = array();
        if(!preg_match($regx, $id))
        {
            return FALSE;
        }
        if(15==strlen($id)) //检查15位
        {
            $regx = "/^(\d{6})+(\d{2})+(\d{2})+(\d{2})+(\d{3})$/";

            @preg_match($regx, $id, $arr_split);
            //检查生日日期是否正确
            $dtm_birth = "19".$arr_split[2] . '/' . $arr_split[3]. '/' .$arr_split[4];
            if(!strtotime($dtm_birth))
            {
                return FALSE;
            } else {
                return TRUE;
            }
        }
        else      //检查18位
        {
            $regx = "/^(\d{6})+(\d{4})+(\d{2})+(\d{2})+(\d{3})([0-9]|X)$/";
            @preg_match($regx, $id, $arr_split);
            $dtm_birth = $arr_split[2] . '/' . $arr_split[3]. '/' .$arr_split[4];
            if(!strtotime($dtm_birth)) //检查生日日期是否正确
            {
                return FALSE;
            }
            else
            {
                //检验18位身份证的校验码是否正确。
                //校验位按照ISO 7064:1983.MOD 11-2的规定生成，X可以认为是数字10。
                $arr_int = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
                $arr_ch = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
                $sign = 0;
                for ( $i = 0; $i < 17; $i++ )
                {
                    $b = (int) $id{$i};
                    $w = $arr_int[$i];
                    $sign += $b * $w;
                }
                $n = $sign % 11;
                $val_num = $arr_ch[$n];
                if ($val_num != substr($id,17, 1))
                {
                    return FALSE;
                } //phpfensi.com
                else
                {
                    return TRUE;
                }
            }
        }

    }
}

if ( ! function_exists('xss_filter')) {
    //php防注入和XSS攻击过滤.
    function xss_filter(&$arr,$strict=false) {
        $ra = Array('/([\x00-\x08,\x0b-\x0c,\x0e-\x19])/', '/script/', '/javascript/', '/vbscript/', '/expression/', '/applet/', '/meta/', '/xml/', '/blink/', '/link/', '/embed/', '/object/',
            '/frame/', '/layer/', '/title/', '/bgsound/', '/base/', '/onload/', '/onunload/', '/onchange/', '/onsubmit/', '/onreset/', '/onselect/', '/onblur/', '/onfocus/', '/onabort/',
            '/onkeydown/', '/onkeypress/', '/onkeyup/', '/onclick/', '/ondblclick/', '/onmousedown/', '/onmousemove/', '/onmouseout/', '/onmouseover/', '/onmouseup/', '/onunload/');
        if (is_array($arr)) {
            foreach ($arr as $key => $value) {
                if (!is_array($value)) {
                    if (!get_magic_quotes_gpc()) { //不对magic_quotes_gpc转义过的字符使用addslashes(),避免双重转义。
                        $value = addslashes($value); //给单引号（'）、双引号（"）、反斜线（\）与 NUL（NULL 字符）加上反斜线转义
                    }
                    if($strict){ //严格模式过滤所有的标签
                        $value = preg_replace($ra, '', $value); //删除非打印字符，粗暴式过滤xss可疑字符串
                        $value=strip_tags($value);//函数剥去字符串中的 HTML、XML 以及 PHP 的标签。
                    }
                    $arr[$key] = e($value); //去除 HTML 和 PHP 标记并转换为 HTML 实体
                } else {
                    xss_filter($arr[$key]);
                }
            }
        }elseif (is_string($arr)){
            $arr= preg_replace($ra, '', $arr);
        }
    }
}

if ( ! function_exists('d_sql_injection')) {
    //防止sql注入
    function d_sql_injection($keyword){
        $keyword=addslashes($keyword);
        return $keyword = preg_replace('/&((#(\d{3,5}|x[a-fA-F0-9]{4}));)/', '&\\1', $keyword);
    }
}

if ( ! function_exists('ismobile')) {
    function ismobile()
    {
        // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
        if (isset ($_SERVER['HTTP_X_WAP_PROFILE'])) {
            return TRUE;
        }
        // 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
        if (isset ($_SERVER['HTTP_VIA'])) {
            return stristr($_SERVER['HTTP_VIA'], "wap") ? TRUE : FALSE;// 找不到为flase,否则为TRUE
        }
        // 判断手机发送的客户端标志,兼容性有待提高
        if (isset ($_SERVER['HTTP_USER_AGENT'])) {
            $clientkeywords = array(
                'mobile',
                'nokia',
                'sony',
                'ericsson',
                'mot',
                'samsung',
                'htc',
                'sgh',
                'lg',
                'sharp',
                'sie-',
                'philips',
                'panasonic',
                'alcatel',
                'lenovo',
                'iphone',
                'ipod',
                'blackberry',
                'meizu',
                'android',
                'netfront',
                'symbian',
                'ucweb',
                'windowsce',
                'palm',
                'operamini',
                'operamobi',
                'openwave',
                'nexusone',
                'cldc',
                'midp',
                'wap'
            );
            // 从HTTP_USER_AGENT中查找手机浏览器的关键字
            if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
                return TRUE;
            }
        }
        if (isset ($_SERVER['HTTP_ACCEPT'])) { // 协议法，因为有可能不准确，放到最后判断
            // 如果只支持wml并且不支持html那一定是移动设备
            // 如果支持wml和html但是wml在html之前则是移动设备
            if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== FALSE) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === FALSE || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
                return TRUE;
            }
        }
        return FALSE;
    }
}

if ( ! function_exists('hash_encode')) {
    function hash_encode($id){
        $hashids = new \Hashids\Hashids('art#123#edu',20);
        return $hashids->encode($id);
    }
}

if ( ! function_exists('hash_decode')) {
    function hash_decode($openid){
        try
        {
            $hashids = new \Hashids\Hashids('art#123#edu',20);
            $id=$hashids->decode($openid);
            if(is_array($id)&&count($id)>0){
                $id=$id[0];
            }
            return $id;
        }
        catch (\Exception $e){
            throw new \App\Exceptions\ValidateException('参数有误！');
        }
    }
}

if ( ! function_exists('sys_setting')) {
    function sys_setting($key){
        return \App\Models\SysSetting::getByKey($key);
    }
}