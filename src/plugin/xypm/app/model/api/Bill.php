<?php

namespace plugin\xypm\app\model\api;
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

    /**
     * 待缴费列表
     */
    public static function getPayList($build_id,$buildtype)
    {
        $where = [
            'status' => 0,
            'build_id' => $build_id,
            'buildtype' => $buildtype,
        ];

        $order = 'id desc';

        $bill = self::where($where)->order($order);

        if (isset($page)) {
            $bill = $bill->paginate();
        } else {
            if(isset($limit)){
                $bill = $bill->limit($limit)->select();
            }else{
                $bill = $bill->select();
            }
        }

        return $bill;
    }

    /**
     * 待缴费用
     */
    public static function getPayMoney($build_id,$buildType)
    {
        $where = [
            'status' => 0,
            'build_id' => $build_id,
            'buildtype' => $buildType
        ];

        $money = self::where($where)->sum('money');

        return $money;
    }
    

    
    public function getBuildtypeList()
    {
        return ['house' => trans('Buildtype house',[],'bill'), 'shop' => trans('Buildtype shop',[],'bill'), 'parking' => trans('Buildtype parking',[],'bill'), 'garage' => trans('Buildtype garage',[],'bill')];
        return ['1' => trans('Status 1',[],'bill'), '0' => trans('Status 0',[],'bill')];
    }

    public function getStatusList()
    {
        return ['1' => trans('Status 1'), '0' => trans('Status 0')];
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
