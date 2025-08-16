<?php

namespace plugin\xypm\app\model\admin\activity;

use plugin\saiadmin\basic\BaseNormalModel;
use think\model\concern\SoftDelete;


class Activity extends BaseNormalModel
{

    use SoftDelete;

    

    // 表名
    protected $name = 'xypm_activity';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'integer';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = 'deletetime';

    // 追加属性
    protected $append = [
        'signup_start_time_text',
        'signup_end_time_text',
        'start_time_text',
        'end_time_text',
        'status_text'
    ];
    

    public function getStatusList()
    {
        return ['normal' => trans('Status normal',[],'activity'), 'hidden' => trans('Status hidden',[],'activity')];
    }


    public function getSignupStartTimeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['signup_start_time']) ? $data['signup_start_time'] : '');
        return is_numeric($value) ? date("Y-m-d H:i", $value) : $value;
    }


    public function getSignupEndTimeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['signup_end_time']) ? $data['signup_end_time'] : '');
        return is_numeric($value) ? date("Y-m-d H:i", $value) : $value;
    }


    public function getStartTimeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['start_time']) ? $data['start_time'] : '');
        return is_numeric($value) ? date("Y-m-d H:i", $value) : $value;
    }


    public function getEndTimeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['end_time']) ? $data['end_time'] : '');
        return is_numeric($value) ? date("Y-m-d H:i", $value) : $value;
    }


    public function getStatusTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['status']) ? $data['status'] : '');
        $list = $this->getStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }

    protected function setSignupStartTimeAttr($value)
    {
        return $value === '' ? null : ($value && !is_numeric($value) ? strtotime($value) : $value);
    }

    protected function setSignupEndTimeAttr($value)
    {
        return $value === '' ? null : ($value && !is_numeric($value) ? strtotime($value) : $value);
    }

    protected function setStartTimeAttr($value)
    {
        return $value === '' ? null : ($value && !is_numeric($value) ? strtotime($value) : $value);
    }

    protected function setEndTimeAttr($value)
    {
        return $value === '' ? null : ($value && !is_numeric($value) ? strtotime($value) : $value);
    }

    public function searchCreatetimeAttr($query, $value)
    {
        $query->whereTime('createtime', 'between', $value);
    }

}
