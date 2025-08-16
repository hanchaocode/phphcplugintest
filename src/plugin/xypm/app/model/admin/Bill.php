<?php

namespace plugin\xypm\app\model\admin;

use plugin\saiadmin\basic\BaseNormalModel;



class Bill extends BaseNormalModel
{

    

    

    // 表名
    protected $name = 'xypm_bill';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'integer';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'buildtype_text',
        'status_text'
    ];


    public function searchCreateTimeAttr($query, $value)
    {
        $query->whereTime('createtime', 'between', $value);
    }

    public function searchMoneyAttr($query, $value)
    {
        // 确保 $value 是一个数组，并且有两个元素
        if (is_array($value) && count($value) === 2) {
            list($min, $max) = $value;

            // 如果 min 和 max 都是有效值，则执行查询
            if ($min !== '' || $max !== '') {
                // 将空字符串转换为 null，这样可以使用数据库的 NULL 处理
                $min = $min === '' ? null : $min;
                $max = $max === '' ? null : $max;

                // 构建查询条件
                if ($min !== null && $max !== null) {
                    $query->whereBetween('money', [$min, $max]);
                } elseif ($min !== null) {
                    $query->where('money', '>=', $min);
                } elseif ($max !== null) {
                    $query->where('money', '<=', $max);
                }
            }
        }

    }
    public function getBuildtypeList()
    {
        return ['house' => trans('Buildtype house',[],'bill'), 'shop' => trans('Buildtype shop',[],'bill'), 'parking' => trans('Buildtype parking',[],'bill'), 'garage' => trans('Buildtype garage',[],'bill')];
    }

    public function getStatusList()
    {
        return ['1' => trans('Status 1',[],'bill'), '0' => trans('Status 0',[],'bill')];
    }


    public function getBuildtypeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['buildtype']) ? $data['buildtype'] : '');
        $list = $this->getBuildtypeList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getStatusTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['status']) ? $data['status'] : '');
        $list = $this->getStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }




}
