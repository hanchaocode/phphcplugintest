<?php

namespace plugin\xypm\utils;

class Tools
{

    static function xypmFilterSql($str)
    {
        $str = strtolower($str);        // 转小写
        $str = str_replace("and", "", $str);
        $str = str_replace("execute", "", $str);
        $str = str_replace("update", "", $str);
        $str = str_replace("count", "", $str);
        $str = str_replace("chr", "", $str);
        $str = str_replace("mid", "", $str);
        $str = str_replace("master", "", $str);
        $str = str_replace("truncate", "", $str);
        $str = str_replace("char", "", $str);
        $str = str_replace("declare", "", $str);
        $str = str_replace("select", "", $str);
        $str = str_replace("create", "", $str);
        $str = str_replace("delete", "", $str);
        $str = str_replace("insert", "", $str);
        $str = str_replace("union", "", $str);
        $str = str_replace("alter", "", $str);
        $str = str_replace("into", "", $str);
        $str = str_replace("'", "", $str);
        $str = str_replace("or", "", $str);
        $str = str_replace("=", "", $str);

        return $str;
    }

    /**
     * 获取最近七天所有日期
     */

    static function xypmGetWeeks($time = '', $format = 'm-d')
    {
        $time = ($time != '' ? $time : time());
        //组合数据
        $date = [];
        for ($i = 1; $i <= 7; $i++) {
            $date[] = date($format, strtotime('+' . ($i - 7) . ' days', $time));
        }
        return $date;
    }
    /**
     * 生成订单号
     * @return string
     */
    static function xypmCreateOrderNo()
    {
        return date('Ymd') . substr(implode('', array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);

    }
    /**
     * 检测系统必要环境
     */

    static function xypmCheckEnv($need = [], $is_throw = true)
    {
        $need = is_string($need) ? [$need] : $need;
        //检测是否安装了支付插件
        if (in_array('yansongda', $need)) {
            if (!class_exists(\Yansongda\Pay\Pay::class)) {
                if ($is_throw) {
                    throw new  \Exception('请安装微信支付宝整合插件');
                } else {
                    return false;
                }
            }
        }
        return true;
    }
}






?>