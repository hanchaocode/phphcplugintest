<?php

namespace plugin\xypm\app\controller\api;

use plugin\xypm\basic\FrontController;
use app\common\library\Sms as Smslib;
use think\Hook;
use think\Validate;
use support\Request;
use support\Response;

/**
 * 手机短信接口
 */
class SmsController extends FrontController
{
    protected $noNeedLogin = ['send'];
    protected $noNeedRight = ['*'];
    
    /**
     * 发送验证码
     *
     * @param string $mobile 手机号
     * @param string $event 事件名称
     */
    public function send(Request $request): Response
    {
        $mobile = $request->input("mobile");
        $event = $request->input("event");

        if (!$mobile || !Validate::regex($mobile, "^1\d{10}$")) {
            return $this->fail(trans('手机号不正确'));
        }
        $last = Smslib::get($mobile, $event);
        if ($last && time() - $last['createtime'] < 60) {
            return $this->fail(trans('发送频繁'));
        }
        $ipSendTotal = \app\common\model\Sms::where(['ip' => $request->ip()])->whereTime('createtime', '-1 hours')->count();
        if ($ipSendTotal >= 5) {
            return $this->fail(trans('请不要频繁发送'));
        }
        if (!Hook::get('sms_send')) {
            return $this->fail(trans('请在后台插件管理安装阿里云短信插件'));
        }
        $ret = Smslib::send($mobile, mt_rand(100000, 999999), $event);
        if ($ret) {
            return $this->success(trans('发送成功'));
        } else {
            return $this->fail(trans('发送失败，请检查短信配置是否正确'));
        }
    }
    
}