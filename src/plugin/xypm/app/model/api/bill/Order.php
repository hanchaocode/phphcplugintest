<?php

namespace plugin\xypm\app\model\api\bill;


use plugin\xypm\app\model\api\Bill as BillModel;
use plugin\xypm\app\model\api\User;
use plugin\xypm\app\model\api\user\Build as UserBildModel;
use think\facade\Db;
use plugin\saiadmin\basic\BaseNormalModel;
use think\model\concern\SoftDelete;




class Order extends BaseNormalModel
{

    use SoftDelete;

    // 表名
    protected $name = 'xypm_bill_order';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'integer';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = 'deletetime';

    // 追加属性
    protected $append = [
        'buildtype_text',
        'pay_type_text',
        'paytime_text',
        'platform_text',
        'status_text'
    ];

    public static function getList($params)
    {
        extract($params);
        $user = User::info();
        $where = ['user_id'=>$user->id,'status'=>1,'user_build_id'=>$user_build_id];
        $list = self::with(['item'])->where($where)->order('id desc')->paginate();

        return $list;
    }

    // 加载订单数据
    public static function getInitData($params)
    {
        extract($params);

        $userBuild = UserBildModel::getDetail($user_build_id);

        if(empty($userBuild)){
            throw new ApiException('未查询到您的房产信息');
        }

        //待交费账单
        $billList = BillModel::getPayList($userBuild['build_id'],$userBuild['type']);

        //待交金额
        $billAmount = BillModel::getPayMoney($userBuild['build_id'],$userBuild['type']);
       
        return [
            'bill_list' => $billList, //商品列表
            'bill_amount' => $billAmount, //账单总金额
            'total_amount' => $billAmount, //订单总金额
            'user_build'   => $userBuild,
        ];
    }

    /**
     * 创建订单
     */
    public static function addOrder($params)
    {
        extract(self::getInitData($params));

        $order = Db::transaction(function () use (
            $bill_list,
            $total_amount,
            $user_build
        ) {
            
            $orderData = [];
            $orderData['order_sn'] = xypmCreateOrderNo();
            $orderData['user_id'] = $user_build->user_id;
            $orderData['member_id'] = $user_build->member_id;
            $orderData['user_build_id'] = $user_build->id;
            $orderData['buildname'] = $user_build->member->buildname;
            $orderData['buildtype'] = $user_build->member->buildtype;
            $orderData['realname'] = $user_build->member->realname;
            $orderData['mobile'] = $user_build->member->mobile;
            $orderData['total_amount'] = $total_amount;
            $orderData['total_fee'] = $total_amount;
            $orderData['status'] = 0;
            $orderData['platform'] = request()->header('platform');

            
            $order = new Order();
            $order->save($orderData);

            // 添加订单选项
            foreach ($bill_list as $info) {
                $orderItem = new OrderItem();
                $orderItem->bill_order_id = $order->id;
                $orderItem->bill_id = $info['id'];
                $orderItem->cycle = $info['cycle'];
                $orderItem->billdate = $info['billdate'];
                $orderItem->money = $info['money'];
                $orderItem->save();
            }

            return $order;
        });

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

        //更新账单
        $orderItem = OrderItem::where(['bill_order_id'=>$order['id']])->select();
        foreach($orderItem as $oi){
            $bill = (new BillModel)->where(['id'=>$oi['bill_id']])->find();
            $bill->user_id = $order->user_id;
            $bill->member_id = $order->member_id;
            $bill->bill_order_id = $order->id;
            $bill->pay_time = time();
            $bill->status = 1;
            $bill->save();
        }
        
        return $order;
    }

    
    public function getBuildtypeList()
    {
        return ['house' => trans('Buildtype house',[],'bill/order'), 'shop' => trans('Buildtype shop',[],'bill/order'), 'parking' => trans('Buildtype parking',[],'bill/order'), 'garage' => trans('Buildtype garage',[],'bill/order')];
        return ['wechat' => trans('Pay_type wechat',[],'bill/order'), ' balance' => trans('Pay_type  balance',[],'bill/order')];
        return ['wxMiniProgram' => trans('Wxminiprogram',[],'bill/order')];
        return ['-1' => trans('已取消',[],'bill/order'), '0' => trans('待支付',[],'bill/order'), '1' => trans('已支付',[],'bill/order')];
    }

    public function getPlatformList()
    {
        return ['wxMiniProgram' => trans('Wxminiprogram')];
    }

    public function getStatusList()
    {
        return ['-1' => trans('已取消'), '0' => trans('待支付'), '1' => trans('已支付')];
    }


    public function getBuildtypeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['buildtype']) ? $data['buildtype'] : '');
        $list = $this->getBuildtypeList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getPayTypeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['pay_type']) ? $data['pay_type'] : '');
        $list = $this->getPayTypeList();
        return isset($list[$value]) ? $list[$value] : '';
    }

    public function getPayTypeList()
    {
        return ['wechat' => trans('Wechat',[])];
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
        return $this->hasMany(OrderItem::class, 'bill_order_id', 'id');
    }


}
