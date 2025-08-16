<?php

namespace plugin\xypm\app\model\admin;

use plugin\saiadmin\basic\BaseNormalModel;




class Member extends BaseNormalModel
{

    

    

    // 表名
    protected $name = 'xypm_member';


    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'buildtype_text',
        'identity_text',
        'gender_text',
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

    public function getBuildTypeList()
    {
        return ['house' => trans('buildtype house', [], 'member', ), 'shop' =>trans('buildtype shop', [], 'member', ), 'parking' => trans('buildtype parking', [], 'member', ), 'garage' =>trans('buildtype garage', [], 'member', )];
    }

    public function getIdentityList()
    {
        return ['1' => trans('Identity 1',[],'member'), '2' => trans('Identity 2',[],'member'), '3' => trans('Identity 3',[],'member')];
    }

    public function getIdentityTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['identity']) ? $data['identity'] : '');
        $list = $this->getIdentityList();
        return isset($list[$value]) ? $list[$value] : '';
    }

    public function getGenderList()
    {
        return ['male' => trans('Gender male',[],'member'), 'female' => trans('Gender female',[],'member')];
    }

    public function getStatusList()
    {
        return ['1' => trans('Status 1',[],'member'), '0' => trans('Status 0',[],'member')];
    }


    public function getBuildTypeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['buildtype']) ? $data['buildtype'] : '');
        $list = $this->getBuildTypeList();
        return isset($list[$value]) ? $list[$value] : '';
    }





    public function getGenderTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['gender']) ? $data['gender'] : '');
        $list = $this->getGenderList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getStatusTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['status']) ? $data['status'] : '');
        $list = $this->getStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }




}
