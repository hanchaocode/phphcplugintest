<?php

namespace plugin\xypm\app\model\api;

use plugin\saiadmin\basic\BaseNormalModel;

/**
 * 第三方登录模型
 */
class Third extends BaseNormalModel
{

    // 表名
    protected $name = 'xypm_third';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
}
