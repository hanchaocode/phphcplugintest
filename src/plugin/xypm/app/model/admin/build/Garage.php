<?php

namespace plugin\xypm\app\model\admin\build;

use plugin\saiadmin\basic\BaseNormalModel;

class Garage extends BaseNormalModel
{

    

    

    // 表名
    protected $name = 'xypm_build_garage';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'integer';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'status_text'
    ];



    public static function onBeforeInsert($model)
    {
        $info = getCurrentInfo();
        $info && $model->setAttr('weigh', $info['id']);
    }

    public function searchCreatetimeAttr($query, $value)
    {
        $query->whereTime('createtime', 'between', $value);
    }

    public function getStatusList()
    {
        return ['1' => trans('Status 1',[],'build_garage'), '0' => trans('Status 0',[],'build_garage')];
    }


    public function getStatusTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['status']) ? $data['status'] : '');
        $list = $this->getStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }



}
