<?php

namespace plugin\xypm\app\model\api\bill;

use plugin\saiadmin\basic\BaseNormalModel;



class OrderItem extends BaseNormalModel
{

    // 表名
    protected $name = 'xypm_bill_order_item';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'integer';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = false;

    // 追加属性
    protected $append = [

    ];


}
