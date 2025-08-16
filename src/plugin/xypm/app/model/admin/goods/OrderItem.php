<?php

namespace plugin\xypm\app\model\admin\goods;

use think\Model;

class OrderItem extends Model
{

    // 表名
    protected $name = 'xypm_goods_order_item';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'integer';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = 'deletetime';

    // 追加属性
    protected $append = [

    ];




}
