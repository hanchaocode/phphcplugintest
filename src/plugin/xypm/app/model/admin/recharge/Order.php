<?php

namespace plugin\xypm\app\model\admin\recharge;

use plugin\saiadmin\basic\BaseNormalModel;
use think\Model;
use think\model\concern\SoftDelete;


class Order extends BaseNormalModel
{


    use SoftDelete;
    

    // 表名
    protected $name = 'xypm_recharge_order';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'integer';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'pay_type_text',
        'paytime_text',
        'platform_text',
        'status_text'
    ];
    

    
    public function getPayTypeList()
    {
        return ['wechat' => trans('Wechat',[],'recharge_order')];
    }

    public function getPlatformList()
    {
        return ['wxMiniProgram' => trans('Platform wxminiprogram',[],'recharge_order'), 'wxOfficialAccount' => trans('Platform wxofficialaccount',[],'recharge_order')];
    }

    public function getStatusList()
    {
        return [ '1' => trans('Status 1',[],'recharge_order'),'0' => trans('Status 0',[],'recharge_order')];
    }


    public function getPayTypeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['pay_type']) ? $data['pay_type'] : '');
        $list = $this->getPayTypeList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getPaytimeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['paytime']) ? $data['paytime'] : '');
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }


    public function getPlatformTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['platform']) ? $data['platform'] : '');
        $list = $this->getPlatformList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getStatusTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['status']) ? $data['status'] : '');
        $list = $this->getStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }

    protected function setPaytimeAttr($value)
    {
        return $value === '' ? null : ($value && !is_numeric($value) ? strtotime($value) : $value);
    }



    public function searchCreateTimeAttr($query, $value)
    {
        $query->whereTime('createtime', 'between', $value);
    }


    public function searchPayFeeAttr($query, $value)
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
                    $query->whereBetween('pay_fee', [$min, $max]);
                } elseif ($min !== null) {
                    $query->where('pay_fee', '>=', $min);
                } elseif ($max !== null) {
                    $query->where('pay_fee', '<=', $max);
                }
            }
        }


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
    public function user()
    {
        return $this->belongsTo('\plugin\xypm\app\model\admin\User', 'user_id', 'id', [], 'LEFT');
    }

}
