<?php

namespace plugin\xypm\app\model\admin\recharge;

use think\Model;


class Recharge extends Model
{

    

    

    // 表名
    protected $name = 'xypm_recharge';
    


    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'status_text'
    ];
    

    
    public function getStatusList()
    {
        return ['normal' => trans('Status normal',[],'recharge'), 'hidden' => trans('Status hidden',[],'recharge')];
    }

    public function searchCreateTimeAttr($query, $value)
    {
        $query->whereTime('createtime', 'between', $value);
    }


    public function searchFaceValueAttr($query, $value)
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
                    $query->whereBetween('facevalue', [$min, $max]);
                } elseif ($min !== null) {
                    $query->where('facevalue', '>=', $min);
                } elseif ($max !== null) {
                    $query->where('facevalue', '<=', $max);
                }
            }
        }

    }

    public function searchBuyPriceAttr($query, $value)
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
                    $query->whereBetween('buyprice', [$min, $max]);
                } elseif ($min !== null) {
                    $query->where('buyprice', '>=', $min);
                } elseif ($max !== null) {
                    $query->where('buyprice', '<=', $max);
                }
            }
        }

    }
    public function getStatusTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['status']) ? $data['status'] : '');
        $list = $this->getStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }



}
