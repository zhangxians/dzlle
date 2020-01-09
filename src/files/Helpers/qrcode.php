<?php

if (!function_exists('certificate_image')) {

    function certificate_image(
        $exam = '艺术素质测评', // 考试名称
        $type, // 1-美术证书 2-音乐证书
        $date = '2019年1月14日',
        $score_course_study = 0,
        $score_file1 = 0,
        $score_basic_skill = 0,
        $score_basic_know = 0,
        $score_file2 = 0,
        $score_art_expertise = 0,
        $userName = '', // 学生姓名
        $codeUrl // 二维码url
    )
    {
        if ($type == 1) {
            $img = \Intervention\Image\Facades\Image::make(config('app.cer_art'));
        } else {
            $img = \Intervention\Image\Facades\Image::make(config('app.cer_music'));
        }
        $cer_name = config('app.cer_name');
        $msg = config('app.cer_msg');
        $numberNum = $score_course_study + $score_art_expertise + $score_file1 + $score_basic_skill + $score_basic_know + $score_file2;
        $type = WithScoreGetType($numberNum);
        $url = $codeUrl ? $codeUrl : config('app.url');
        $bottom = "$msg \n\n$date";
        $row = "课程学习  $score_course_study       课外活动  $score_file1       基本技能  $score_basic_skill" . "\n\n"
            . "基础知识  $score_basic_know       校外学习  $score_file2       艺术特长  $score_art_expertise";
        //标题
        $img->text($cer_name, 960, 120, function ($font) {
            //字体文件
            $font->file('fonts/kaiti_gb2312.ttf');
            $font->color('#69441f');
            $font->align('center');
            $font->valign('top');
            $font->size(80);
        });
        //姓名
        $img->text($userName, 960, 280, function ($font) {
            $font->file('fonts/kaiti_gb2312.ttf');
            $font->color('#69441f');
            $font->align('center');
            $font->valign('top');
            $font->size(56);
        });
        //测评具体
        $img->text("参加“{$exam}”认定等级为", 960, 380, function ($font) {
            $font->file('fonts/kaiti_gb2312.ttf');
            $font->color('#69441f');
            $font->align('center');
            $font->valign('top');
            $font->size(39);
        });
        //层次
        $img->text($type, 960, 500, function ($font) {
            $font->file('fonts/kaiti_gb2312.ttf');
            $font->color('#ff0000');
            $font->align('center');
            $font->valign('top');
            $font->size(80);
        });
        //分数
        $img->text($row, 810, 610, function ($font) {
            $font->file('fonts/kaiti_gb2312.ttf');
            $font->color('#69441f');
            $font->align('center');
            $font->valign('top');
            $font->size(34);
        });
        //总成绩
        $img->text("总成绩  $numberNum", 1360, 700, function ($font) {
            $font->file('fonts/kaiti_gb2312.ttf');
            $font->color('#69441f');
            $font->align('center');
            $font->valign('top');
            $font->size(36);
        });
        $img->text($bottom, 1400, 900, function ($font) {
            $font->file('fonts/kaiti_gb2312.ttf');
            $font->color('#69441f');
            $font->align('left');
            $font->valign('top');
            $font->size(27);
        });

        //生成对应二维码
        $random = str_random(16);
        $temp = "images/qrcode/qrcode-{$random}.png";

        //判断该文件夹是否存在，不存在则创建
        $temp_dir = public_path('images/qrcode');
        if (!is_dir($temp_dir)) {
            mkdir($temp_dir, 0777);
        }
        getQrCode($url, $temp);
        $img->insert(public_path($temp), 'left-top', 1280, 930);
        unlink(public_path($temp));
        return $img;
    }
}

if (!function_exists('getQrCode')) {
    function getQrCode($url, $temp)
    {
        return SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')->encoding('UTF-8')->size(100)->margin(0)->generate($url, public_path($temp));
    }
}

if (!function_exists('WithScoreGetType')) {
    function WithScoreGetType($score)
    {
        if ($score < 60) {
            return "不 及 格";
        } elseif (75 > $score && $score >= 60) {
            return "及 格";
        } elseif (90 > $score && $score >= 75) {
            return '良 好';
        } elseif (110 >= $score && $score >= 90) {
            return "优 秀";
        } else {
            return "未 知";
        }
    }
}

