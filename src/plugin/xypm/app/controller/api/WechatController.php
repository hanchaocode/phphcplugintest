<?php

namespace plugin\xypm\app\controller\api;

use plugin\xypm\basic\FrontController;
use app\common\model\Wechat as WechatModel;



use plugin\xypm\service\Wechat as WechatService;

use support\Request;
use support\Response;

/**
 * 微信接口
 */
class WechatController extends FrontController
{
    protected $noNeedLogin = ['jssdkconfig','login']; 
    protected $noNeedRight = ['*'];

    //分享页面
    protected $pages = ['pages/index','pages/store/detail','pages/shop/goods/detail'];


    public function wxacode(Request $request)
    {
        $spm = $request->get('spm');
        $path = 'pages/index';
        $sence =  'spm:'.$spm;

        $spmArr = explode('.', $spm);
        $path = $this->pages[$spmArr[1]-1];

        if($spmArr[2] > 0){
            $sence.=',id:'.$spmArr[2];
        }

        $wechat = new WechatService('wxMiniProgram');
        $content = $wechat->getApp()->app_code->getUnlimit($sence, [
            'page' => $path,
        ]);

        if ($content instanceof \EasyWeChat\Kernel\Http\StreamResponse) {
            return response($content->getBody(), 200, ['Content-Length' => strlen($content)])->contentType('image/png');
        } else {
            return $this->fail('获取失败', '小程序码获取失败');
        }
    }

    /**
     * 获取JSSDK配置
     */
    public function jssdk(Request $request)
    {
        $params = $request->post();
        $uri = urldecode($params['uri']);
        $wechat = new WechatService('wxOfficialAccount');
        $jssdk = $wechat->getApp()->jssdk->setUrl($uri);
        $res = $wechat->buildConfig($jssdk, [
            'checkJsApi',
            'updateTimelineShareData',
            'updateAppMessageShareData',
            "onMenuShareAppMessage",
            "onMenuShareTimeline",
            'getLocation',
            'openLocation',
            'scanQRCode',
            'chooseWXPay',
            'chooseImage',
            'previewImage',
            'uploadImage',
            'openAddress',
        ]);
        $this->success('获取成功', $res);
    }

}
