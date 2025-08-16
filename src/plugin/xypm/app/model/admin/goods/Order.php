<?php

namespace plugin\xypm\app\model\admin\goods;

use think\Model;
use think\model\concern\SoftDelete;


class Order extends Model
{

    use SoftDelete;

    

    // 表名
    protected $name = 'xypm_goods_order';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'integer';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = 'deletetime';

    // 追加属性
    protected $append = [
        'pay_type_text',
        'paytime_text',
        'platform_text',
        'status_text'
    ];
    
    public function getPayTypeList()
    {
        return ['wechat' => trans('Wechat Pay',[],'goods_order'),'balance'=>trans('Balance Pay',[],'goods_order')];
    }

    public function getPlatformList()
    {
        return ['wxMiniProgram' => trans('wxMiniProgram',[],'goods_order')];
    }

    public function getStatusList()
    {
        return ['0' => trans('Status 2',[],'goods_order'), '1' => trans('Status 1',[],'goods_order'), '2' => trans('Status 2',[],'goods_order'),'3' => trans('Status 3',[],'goods_order'),'4' => trans('Status 4',[],'goods_order'),'5' => trans('Status 5',[],'goods_order'),'-1' => trans('Status -1',[],'goods_order')];
    }

    public function searchCreatetimeAttr($query, $value)
    {
        $query->whereTime('createtime', 'between', $value);
    }

    public function searchLinePriceAttr($query, $value)
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
                    $query->whereBetween('line_price', [$min, $max]);
                } elseif ($min !== null) {
                    $query->where('line_price', '>=', $min);
                } elseif ($max !== null) {
                    $query->where('line_price', '<=', $max);
                }
            }
        }

    }

    public function searchPaytimeAttr($query, $value)
    {
        $query->whereTime('paytime', 'between', $value);
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

    public function item()
    {
        return $this->hasMany('plugin\xypm\app\model\admin\goods\OrderItem', 'goods_order_id');
    }

    public function user()
    {
        return $this->belongsTo('plugin\xypm\app\model\admin\User', 'user_id', 'id', [], 'LEFT');
    }



}
