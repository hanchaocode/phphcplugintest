<?php

namespace plugin\xypm\app\controller\api;
use plugin\xypm\service\PayService;
use plugin\xypm\basic\FrontController;
use plugin\xypm\app\model\api\bill\Order as BillOrderModel;
use plugin\xypm\app\model\api\goods\Order as GoodsOrderModel;
use plugin\xypm\app\model\api\recharge\Order as RechargeOrderModel;
use plugin\xypm\app\model\api\Third;
use plugin\xypm\app\model\api\User;

use think\facade\Db;
use support\Log;
use plugin\xypm\utils\Tools;
use support\Request;
use support\Response;

class PayController extends FrontController
{

    protected $noNeedLogin = ['prepay', 'notifyx'];
    protected $noNeedRight = ['*'];


    /**
     * 拉起支付
     */
    public function prepay(Request $request): Response
    {




        $user = User::info();
        $order_sn = $request->post('order_sn');
        $payment = $request->post('payment');
        $order_type = $request->post('order_type');
        $platform = request()->header('platform');

        list($order, $title) = $this->getOrderInstance($order_type);
        $order = $order->where('user_id', $user->id)->where('order_sn', $order_sn)->find();
        
        if (!$order) {
            return $this->fail("订单不存在");
        }

        if (in_array($order->status, [-1])) {
            return $this->fail("订单已失效");
        }

        if ($payment == 'balance') {
            // 余额支付
            return $this->balancePay($order,$order_type);
        }

        try {
            Tools::xypmCheckEnv('yansongda');
        } catch (\Exception $e) {
            return $this->fail("支付配置错误：" . $e->getMessage());
        }




        $order_data = [
            'order_id' => $order->id,
            'out_trade_no' => $order->order_sn,
            'total_fee' => $order->total_fee,
        ];


        if ($payment == 'wechat') {
            if (in_array($platform, ['wxOfficialAccount', 'wxMiniProgram'])) {
                if (isset($openid) && $openid) {
                    $order_data['openid'] = $openid;
                } else {
                    $third = Third::where([
                        'user_id' => $order->user_id,
                        'platform' => $platform
                    ])->find();
        
                    $order_data['openid'] = $third ? $third->openid : '';
                }
            }
            $order_data['body'] = $title;
        }
        
        try {
            $notify_url = $request->root(true) . '/app/xypm/api/pay/notifyx/payment/' . $payment . '/platform/' . $platform .'/order_type/'.$order_type;
            $pay = new PayService($payment, $platform, $notify_url);
            $result = $pay->create($order_data);
        } catch (\Exception $e) {
            return $this->fail("支付配置错误：" . $e->getMessage());
        }

        return $this->success('获取预付款成功', [
            'pay_data' => $result,
        ]);
    }

    /**
     * 支付成功回调
     */
    public function notifyx(Request $request): Response
    {

        $payment = $request->get('payment');
        $platform = $request->get('platform');
        $order_type = $request->get('order_type');

        $pay = new PayService($payment, $platform);

        $result = $pay->notify(function ($data, $pay = null) use ($payment,$order_type) {
            
            try {
                $out_trade_no = $data['out_trade_no'];

                list($order, $title) = $this->getOrderInstance($order_type);
                
                
                if ($payment == 'wechat' && ($data['result_code'] != 'SUCCESS' || $data['return_code'] != 'SUCCESS')) {
                    // 微信交易未成功，返回 false，让微信再次通知
                    return false;
                }

                // 支付成功流程
                $pay_fee = $data['total_fee'] / 100;

                $order = $order->where('order_sn', $out_trade_no)->find();

                if (!$order || $order->status > 0) {
                    // 订单不存在，或者订单已支付
                    return $pay->success()->send();
                }

                Db::transaction(function () use ($order, $data, $payment, $pay_fee) {
                    $notify = [
                        'order_sn' => $data['out_trade_no'],
                        'transaction_id' => $payment == 'alipay' ? $data['trade_no'] : $data['transaction_id'],
                        'notify_time' => date('Y-m-d H:i:s', strtotime($data['time_end'] ?? $data['notify_time'])),
                        'payment_json' => json_encode($data),
                        'pay_fee' => $pay_fee,
                        'pay_type' => $payment
                    ];

                    $order->paySuccess($order, $notify);

                });

                return $pay->success()->send();
            } catch (\Exception $e) {
                Log::error('notifyx-error:' . json_encode($e->getMessage()));
            }
        });

        return $result;
    }

    // 余额支付
    public function balancePay ($order,$order_type) {
        $order = Db::transaction(function () use ($order,$order_type) {
            if (!$order) {
                $this->fail("订单已支付");
            }
            $total_fee = $order->total_fee;

            $user = User::info();

            User::money(-$total_fee, $user->id, 'pay_'.$order_type, '',$order->id);

            $notify = [
                'order_sn' => $order['order_sn'],
                'transaction_id' => '',
                'notify_time' => date('Y-m-d H:i:s'),
                'buyer_email' => $user->id,
                'pay_fee' => $order->total_fee,
                'pay_type' => 'balance'
            ];
            $notify['payment_json'] = json_encode($notify);
            $order->paySuccess($order, $notify);

            return $order;
        });

        return $this->success('支付成功', $order);
    }


    /**
     * 根据订单类型获取订单实例
     */
    private function getOrderInstance($order_type) 
    {
        $order = null;
        $title = '订单支付';

        // 商城订单
        if($order_type == 'goods'){
            $order = new GoodsOrderModel();
            $title = '商城'.$title;
        }

        // 交费订单
        if($order_type == 'bill'){
            $order = new BillOrderModel();
            $title = '交物业费'.$title;
        }

        // 充值订单
        if ($order_type == 'recharge') {
            $order = new RechargeOrderModel();
            $title = '充值'.$title;
        }

        return [$order, $title];
    }
}
