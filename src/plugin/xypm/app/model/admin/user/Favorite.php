<?php

namespace plugin\xypm\app\model\admin\user;

use think\Model;
use think\model\concern\SoftDelete;


class Favorite extends Model
{

    use SoftDelete;

    

    // 表名
    protected $name = 'xypm_user_favorite';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'integer';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = 'deletetime';

    // 追加属性
    protected $append = [
        'type_text'
    ];
    

    
    public function getTypeList()
    {
        return ['coach' => trans('Coach'), 'store' => trans('Store'), 'course' => trans('Course'), 'goods' => trans('Goods')];
    }


    public function getTypeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['type']) ? $data['type'] : '');
        $list = $this->getTypeList();
        return isset($list[$value]) ? $list[$value] : '';
    }




}
