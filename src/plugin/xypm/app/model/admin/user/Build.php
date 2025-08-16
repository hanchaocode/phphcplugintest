<?php

namespace plugin\xypm\app\model\admin\user;

use plugin\saiadmin\basic\BaseNormalModel;




class Build extends BaseNormalModel
{

    

    

    // 表名
    protected $name = 'xypm_user_build';
    


    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'buildtype_text'
    ];



    public function getBuildTypeList()
    {
        return ['house' => trans('buildtype house',[],'build'), 'shop' => trans('buildtype shop',[],'build'), 'parking' => trans('buildtype parking',[],'build'), 'garage' => trans('buildtype garage',[],'build')];
    }


    public function getBuildTypeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['buildtype']) ? $data['buildtype'] : '');
        $list = $this->getBuildTypeList();
        return isset($list[$value]) ? $list[$value] : '';
    }




}
