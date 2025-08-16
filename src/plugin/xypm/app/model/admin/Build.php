<?php

namespace plugin\xypm\app\model\admin;


use plugin\saiadmin\basic\BaseNormalModel;


class Build extends  BaseNormalModel
{

    

    // 表名
    protected $name = 'xypm_build';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'integer';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';


    protected $readonly = ['created_by', 'create_time'];

    // 追加属性


    /**
     * 称 搜索
     */
    public function searchNameAttr($query, $value)
    {
        $query->where('name', 'like', '%'.$value.'%');
    }


    public function searchCreatetimeAttr($query, $value)
    {
        $query->whereTime('createtime', 'between', $value);
    }

    public static function onBeforeInsert($model)
    {
        $info = getCurrentInfo();
        $info && $model->setAttr('weigh', $info['id']);
    }

    public function getStatusList()
    {
        return ['normal' => trans('Normal',[],'build'), 'hidden' => trans('Hidden',[], 'build')];
    }


    public function getStatusTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['status']) ? $data['status'] : '');
        $list = $this->getStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }




}
