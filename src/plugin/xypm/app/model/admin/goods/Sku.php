<?php

namespace plugin\xypm\app\model\admin\goods;

use plugin\saiadmin\basic\BaseNormalModel;



class Sku extends BaseNormalModel
{

    

    

    // 表名
    protected $name = 'xypm_goods_sku';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    protected $deleteTime = false;

    // 追加属性
    protected $append = [

    ];


    public static function onBeforeInsert($model)
    {
        $info = getCurrentInfo();
        $info && $model->setAttr('weigh', $info['id']);
    }
    







}
