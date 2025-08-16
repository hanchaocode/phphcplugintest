<?php

namespace plugin\xypm\service;



use plugin\xypm\app\model\api\Config;
use support\Log;
use Yansongda\Pay\Pay;

class PayService
{
    protected $config;
    protected $platform;
    protected $payment;
    protected $notify_url;
    protected $type;
    public $method;


    public function __construct($payment, $platform = '', $notify_url = '', $type = 'pay')
    {
        $this->platform = $platform;
        $this->payment = $payment;
        $this->notify_url = $notify_url;
        $this->type = $type;

        $this->setPaymentConfig();
    }

    private function setPaymentConfig()
    {
        $paymentConfig = json_decode(Config::get(['name' => $this->payment])->value, true);

        $this->config = $paymentConfig;
        $this->config['notify_url'] = $this->notify_url;

        $this->config['http'] = [
            'timeout' => 10,
            'connect_timeout' => 10,
        ];

        if ($this->payment === 'wechat') {
            $this->setWechatAppId();
        }
    }

    private function setWechatAppId()
    {
        switch ($this->platform) {
            case 'wxMiniProgram':
                $platformConfig = json_decode(Config::get(['name' => $this->platform])->value, true);
                $this->config['miniapp_id'] = $platformConfig['app_id'];
                $this->config['app_id'] = $platformConfig['app_id'];
                break;
            case 'wxOfficialAccount':
                $platformConfig = json_decode(Config::get(['name' => $this->platform])->value, true);
                $this->config['app_id'] = $platformConfig['app_id'];
                break;
        }
    }


    private function setPaymentMethod()
    {
        $method = [
            'wechat' => [
                'wxMiniProgram' => 'miniapp', //小程序支付
                'wxOfficialAccount' => 'mp',   //公众号支付
            ],
        ];
        
        $this->method = $method[$this->payment][$this->platform];
    }

    public function create($order)
    {
        $this->setPaymentMethod();

        $method = $this->method;

        switch ($this->payment) {
            case 'wechat':
                if (isset($this->config['mode']) && $this->config['mode'] === 'service') {
                    $order['sub_openid'] = $order['openid'] ?? '';
                    unset($order['openid']);
                }
                $order['total_fee'] = $order['total_fee'] * 100;
                unset($order['order_id']);
                $pay = Pay::wechat($this->config)->$method($order);
                break;
            
        }

        return $pay;
    }

    
    public function notify($callback)
    {
        $pay = $this->getPay();

        try {
            $data = $pay->verify(); 

            $result = $callback($data, $pay);

        } catch (\Exception $e) {
            Log::error('notify-error:' . $e->getMessage());
        }

        return $result;
    }

    private function getPay()
    {
        switch ($this->payment) {
            case 'wechat':
                $pay = Pay::wechat($this->config);
                break;
        }

        return $pay;
    }
}
