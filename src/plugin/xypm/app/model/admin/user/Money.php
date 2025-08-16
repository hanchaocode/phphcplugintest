<?php

namespace plugin\xypm\app\model\admin\user;

use plugin\saiadmin\basic\BaseNormalModel;



class Money extends BaseNormalModel
{

    

    

    // 表名
    protected $name = 'xypm_user_money';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'integer';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'type_text'
    ];
    

    
    public function getTypeList()
    {
        return ['sys' => trans('Sys',[],'user/money'), 'pay' => trans('Pay',[], 'user/money')];
    }


    public function getTypeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['type']) ? $data['type'] : '');
        $list = $this->getTypeList();
        return isset($list[$value]) ? $list[$value] : '';
    }




}
