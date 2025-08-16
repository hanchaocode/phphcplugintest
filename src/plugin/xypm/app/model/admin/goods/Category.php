<?php

namespace plugin\xypm\app\model\admin\goods;

use think\Model;



class Category extends Model
{

    // 表名
    protected $name = 'xypm_category';
    
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
        return ['goods' => trans('Goods',[],'goods/category'), 'course' => trans('Course',[],'goods/category')];
    }

    public function getStatusList()
    {
        return ['normal' => trans('Normal',[],'goods/category'),'hidden' =>trans('Hidden',[],'goods/category')];
    }

    public function searchNameAttr($query, $value)
    {
        $query->where('name', 'like', '%'.$value.'%');
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
