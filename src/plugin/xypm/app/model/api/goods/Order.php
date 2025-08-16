<?php

namespace plugin\xypm\app\model\api\goods;


use plugin\saiadmin\basic\BaseNormalModel;
use plugin\xypm\app\model\api\Cart;
use plugin\xypm\app\model\api\Goods as GoodsModel;
use plugin\xypm\app\model\api\goods\OrderItem as OrderItemModel;
use plugin\xypm\app\model\api\User;
use plugin\xypm\app\model\api\user\Build as UserBuildModel;
use plugin\xypm\exception\ApiException;
use think\facade\Db;
use think\model\concern\SoftDelete;



class Order extends BaseNormalModel
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
        'status_text',
        'ext_arr'
    ];

    public function getExtArrAttr($value, $data)
    {
        $ext = (isset($data['ext']) && $data['ext']) ? json_decode($data['ext'], true) : [];
        return $ext;
    }

    // 加载订单数据
    public static function getInitData($params)
    {
        extract($params);

        // 检测并重新组装要购买的商品列表,返回商品列表和总价
        list($orderGoodsList,$goodsAmount,$totalNum) = self::checkGoods($goods_list);

        // 订单运费
        $deliveryAmount = 0;
        
        // 订单总金额，需支付金额
        $totalAmount = bcadd($goodsAmount, $deliveryAmount, 2);
        $totalFee = $totalAmount;
        $totalFee = $totalFee < 0 ? 0 : $totalFee;

        //获取用户默认房产
        $userBuild = UserBuildModel::getList([]);
    
        return [
            'order_goods_list' => $orderGoodsList, //商品列表
            'goods_amount' => $goodsAmount, //商品总价
            'total_amount' => $totalAmount, //订单总金额
            'total_fee' => $totalFee, //支付金额
            'total_num' => $totalNum, //商品件数
            'user_build' => $userBuild, //用户房产列表
        ];
    }

    
    /**
     * 确认收货
     */
    public static function takeDelivery($params)
    {

        extract($params);
        $user = User::info();
        $order = self::where(['user_id'=>$user->id,'id'=>$id])->find();

        if($order['status'] != 2){
            throw new ApiException('订单状态错误');
        }

        return  $order->save(['status'=>3,'take_delivery_time'=>time()]);
    }



    /**
     * 创建订单
     */
    public static function addOrder($params)
    {
        $user = User::info();
        extract($params);
        $order_type = $order_type ?? 'goods';

        extract(self::getInitData($params));

        if($realname == ''){
            throw new ApiException('请输入联系人姓名！');
        }

        if($mobile == ''){
            throw new ApiException('请输入联系电话！');
        }

        $order = Db::transaction(function () use (
            $user,
            $order_type,
            $goods_amount,
            $total_num,
            $total_amount,
            $total_fee,
            $order_goods_list,
            $realname,
            $mobile,
            $buildname,
            $remark,
            $from
        ) {
            
            $orderData = [];
            $orderData['order_sn'] = xypmCreateOrderNo();
            $orderData['user_id'] = $user->id;
            $orderData['type'] = $order_type;
            $orderData['from'] = $from;
            $orderData['total_num'] = $total_num;
            $orderData['goods_amount'] = $goods_amount;
            $orderData['total_amount'] = $total_amount;
            $orderData['total_fee'] = $total_fee;
            $orderData['buildname'] = $buildname;
            $orderData['realname'] = $realname;
            $orderData['mobile'] = $mobile;
            $orderData['remark'] = $remark;
            $orderData['status'] = 0;
            $orderData['platform'] = request()->header('platform');
            $order = new Order();
            $order->save($orderData);

            // 添加订单选项
            foreach ($order_goods_list as $key => $buyinfo) {
                $detail = $buyinfo['detail'];
                $current_sku_price = $detail['current_sku_price'];

                $orderItem = new OrderItem();
                $orderItem->goods_order_id = $order->id;
                $orderItem->goods_id = $buyinfo['goods_id'];
                $orderItem->goods_sku_price_id = $buyinfo['sku_price_id'];
                $orderItem->goods_sku_text = $current_sku_price['goods_sku_text'];
                $orderItem->goods_title = $detail->title;
                $orderItem->goods_image = empty($current_sku_price['image']) ? $detail->image : $current_sku_price['image'];
                $orderItem->goods_price = $detail->current_sku_price->price;
                $orderItem->buy_num = $buyinfo['buy_num'] ?? 1;
                $orderItem->goods_weight = $detail->current_sku_price->weight;
                $orderItem->save();
            }

            return $order;
        });

        // 删除购物车
        if ($from == 'cart') {
            foreach ($order_goods_list as $delCart) {
                Cart::where([
                    'user_id' => $user->id,
                    'goods_id' => $delCart['goods_id'],
                    'sku_price_id' => $delCart['sku_price_id'],
                ])->delete();
            }
        }

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

        //更新商品销量
        $orderItem = OrderItemModel::where(['goods_order_id'=>$order['id']])->select();
        foreach($orderItem as $oi){
            $goods = (new GoodsModel)->where(['id'=>$oi['goods_id']])->find();
            $goods->setInc('sales',$oi['buy_num']);
        }
        
        return $order;
    }

    // 取消订单
    public static function cancelOrder($params)
    {
        $user = User::info();
        extract($params);
        $order = self::where(['user_id'=>$user->id,'id'=>$id,'status'=>0])->find($id);
        if (!$order) {
            throw new ApiException('订单不存在或已取消');
        }
        $order->status = -1;        // 取消订单
        $order->ext = json_encode($order->setExt($order, ['cancel_time' => time()]));      // 取消时间
        $order->save();
        return $order;
    }

    
    // 下单前检测，商品状态、库存
    public static function checkGoods($goods_list)
    {

        $orderGoodsList = [];
        $goodsAmount = 0;
        $totalNum = 0;
        foreach ($goods_list as $k => $buyinfo) {
            $buyinfo['buy_num'] = intval($buyinfo['buy_num']) < 1 ? 1 : intval($buyinfo['buy_num']);
            $sku_price_id = $buyinfo['sku_price_id'];
            $detail = GoodsModel::getDetail($buyinfo['goods_id']);
            $sku_prices = $detail['sku_price'];
            foreach ($sku_prices as $k => $sku_price) {
                if ($sku_price['id'] == $sku_price_id) {
                    $detail->current_sku_price = $sku_price;
                    break;
                }
            }

            if (!$detail || $detail->status == 'down') {
                throw new ApiException('商品不存在或已下架');
            }

            if (!isset($detail->current_sku_price) || !$detail->current_sku_price || $detail->current_sku_price->status != 'up') {
                throw new ApiException('商品规格不存或已下架');
            }

            //商品详情
            $buyinfo['detail'] = $detail;
            $orderGoodsList[] = $buyinfo;

            // 当前库存，小于要购买的数量
            if ($detail->current_sku_price['stock'] < $buyinfo['buy_num']) {
                self::checkAndException('商品库存不足');
            }

            // 当前商品总价
            $currentAmount = bcmul($detail->current_sku_price->price, $buyinfo['buy_num'], 2);
            $goodsAmount = bcadd($goodsAmount, $currentAmount, 2);

            // 商品件数
            $totalNum = $totalNum + $buyinfo['buy_num'];
        }

        if (!count($orderGoodsList)) {
            throw new ApiException('请选择要购买的商品');
        }
        
        return [$orderGoodsList,$goodsAmount,$totalNum];
    }

    // 订单详情
    public static function getDetail($params)
    {
        $user = User::info();
        extract($params);

        $order = self::with(['item'])->where('user_id', $user->id);

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

    public static function getList($params)
    {
        extract($params);
        $user = User::info();
        $where = ['user_id'=>$user->id];
        if($status != 'all'){
            $where['status'] = $status;
        }
        $list = self::with(['item'])->where($where)->order('id desc')->paginate();

        return $list;
    }

    public function setExt($order, $field, $origin = [])
    {
        $newExt = array_merge($origin, $field);

        $orderExt = $order['ext_arr'];

        return array_merge($orderExt, $newExt);
    }
    
    public function getPayTypeList()
    {
        return ['wechat' => trans('Wechat',[],'goods/order')];
        return ['wxMiniProgram' => trans('Wxminiprogram',[],'goods/order')];
        return [ '-1' => trans('已取消',[],'goods/order'), '0' => trans('待付款',[],'goods/order'), '1' => trans('待配送',[],'goods/order'),'2' => trans('已配送',[],'goods/order'),'3' => trans('已完成',[],'goods/order')];
    }

    public function getPlatformList()
    {
        return ['wxMiniProgram' => trans('Wxminiprogram')];
    }

    public function getStatusList()
    {
        return [ '-1' => trans('已取消'), '0' => trans('待付款'), '1' => trans('待配送'),'2' => trans('已配送'),'3' => trans('已完成')];
    }

    public function getStatusTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['status']) ? $data['status'] : '');
        $list = $this->getStatusList();
        return isset($list[$value]) ? $list[$value] : '';
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

    protected function setPaytimeAttr($value)
    {
        return $value === '' ? null : ($value && !is_numeric($value) ? strtotime($value) : $value);
    }

    public function item()
    {
        return $this->hasMany(OrderItem::class, 'goods_order_id', 'id');
    }


}
