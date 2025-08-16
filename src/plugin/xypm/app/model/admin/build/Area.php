<?php

namespace plugin\xypm\app\model\admin\build;

use think\Model;



class Area extends Model
{

    

    

    // 表名
    protected $name = 'xypm_build_area';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'integer';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'type_text',
        'status_text'
    ];
    


    public static function onBeforeInsert($model)
    {
        $info = getCurrentInfo();
        $info && $model->setAttr('weigh', $info['id']);
    }

    public function getTypeList()
    {
        return ['shop' => trans('Type shop',[],'build_area'), 'parking' => trans('Type parking',[],'build_area')];
    }

    public function getStatusList()
    {
        return ['normal' => trans('Status normal',[],'build_area'), 'hidden' => trans('Status hidden',[],'build_area')];
    }


    public function getTypeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['type']) ? $data['type'] : '');
        $list = $this->getTypeList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getStatusTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['status']) ? $data['status'] : '');
        $list = $this->getStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }




}
