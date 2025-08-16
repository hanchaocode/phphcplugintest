<?php

namespace plugin\xypm\app\model\api;


use plugin\saiadmin\basic\BaseNormalModel;

class Token extends BaseNormalModel
{

    // 表名
    protected $name = 'xypm_user_token';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'integer';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = false;


    public static function clear($user_id)
    {
        // 假设Token信息存储在tokens表中，并且有user_id字段
        (new self())->where('user_id', $user_id)->delete();
    }

    public static function get($token){
        return (new self())->where('token', $token)->find();
    }

}
