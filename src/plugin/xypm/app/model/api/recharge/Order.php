<?php

namespace plugin\xypm\app\model\api\recharge;


use plugin\xypm\app\model\api\recharge\Recharge as RechargeModel;
use plugin\xypm\app\model\api\User;


use plugin\saiadmin\basic\BaseNormalModel;
use plugin\xypm\exception\ApiException;
use plugin\xypm\utils\Tools;

class Order extends BaseNormalModel
{

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

    /**
     * 添加订单
     */
    public static function addOrder($params){

        extract($params);

        $recharge = RechargeModel::where(['id'=>$recharge_id,'status'=>'normal'])->find();
        if(empty($recharge)){
            throw new ApiException('充值套餐已下架！');
        }
        $money = $recharge['facevalue'];
        $total_fee = $recharge['buyprice'];

        $user = User::info();

        $orderData = [];
        $orderData['order_sn'] = Tools::xypmCreateOrderNo();
        $orderData['user_id'] = $user->id;
        $orderData['money'] = $money;
        $orderData['total_fee'] = $total_fee;

        $order = self::create($orderData);

        return $order;
    }

    /**
     * 订单支付成功
     *
     * @param [type] $order
     * @param [type] $notify
     * @return void
     */
    public function paySuccess($order, $notify)
    {

        $order->status = 1;
        $order->paytime = time();
        $order->transaction_id = $notify['transaction_id'];
        $order->payment_json = $notify['payment_json'];
        $order->pay_type = $notify['pay_type'];
        $order->pay_fee = $notify['pay_fee'];
        $order->save();

        //更新用户余额
        User::money($order->money, $order->user_id, 'recharge', '',$order->id);
        
        return $order;
    }

    // 订单详情
    public static function getDetail($params)
    {
        $user = User::info();
        extract($params);

        $order = self::where('user_id', $user->id);

        if (isset($order_sn)) {
            $order = $order->where('order_sn', $order_sn);
        }
        if (isset($id)) {
            $order = $order->where('id', $id);
        }

        $order = $order->find();

        if (!$order) {
            throw new ApiException('订单不存在');
        }

        return $order;
    }

    
    public function getPayTypeList()
    {
        return ['wechat' => trans('Wechat',[],'recharge/order')];
        return ['wxMiniProgram' => trans('Platform wxminiprogram',[],'recharge/order'), 'wxOfficialAccount' => trans('Platform wxofficialaccount',[],'recharge/order')];
        return ['-2' => trans('Status -2',[],'recharge/order'), '-1' => trans('Status -1',[],'recharge/order'), '0' => trans('Status 0',[],'recharge/order'), '1' => trans('Status 1',[],'recharge/order')];
    }

    public function getPlatformList()
    {
        return ['wxMiniProgram' => trans('Platform wxminiprogram'), 'wxOfficialAccount' => trans('Platform wxofficialaccount')];
    }

    public function getStatusList()
    {
        return ['-2' => trans('Status -2'), '-1' => trans('Status -1'), '0' => trans('Status 0'), '1' => trans('Status 1')];
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


}
