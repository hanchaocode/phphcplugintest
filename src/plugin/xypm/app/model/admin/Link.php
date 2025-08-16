<?php

namespace plugin\xypm\app\model\admin;

use plugin\saiadmin\basic\BaseNormalModel;



class Link extends BaseNormalModel
{

    // 表名
    protected $name = 'xypm_link';
    


    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';

    // 追加属性
    protected $append = [
        'type_text',
    ];


    public static function onBeforeInsert($model)
    {
        $info = getCurrentInfo();
        $info && $model->setAttr('weigh', $info['id']);
    }


    public function searchUrlAttr($query, $value)
    {
        $query->where('url', 'like', '%'.$value.'%');
    }

    public function searchNameAttr($query, $value)
    {
        $query->where('name', 'like', '%'.$value.'%');
    }
    public function getTypeList()
    {
        return ['basic' => trans('Basic',[],'link'), 'user' => trans('User',[],'link'), 'notice' => trans('Notice',[],'link'), 'activity' => trans('Activity',[],'link'), 'goods' => trans('Goods',[],'link')];
    }

    public function getTypeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['type']) ? $data['type'] : '');
        $list = $this->getTypeList();
        return isset($list[$value]) ? $list[$value] : '';
    }


}
