<?php

namespace plugin\xypm\app\controller\api;
use plugin\xypm\app\model\api\Third;
use plugin\xypm\app\model\api\User;
use plugin\xypm\utils\Decrypt\weixin\wxBizDataCrypt;
use plugin\xypm\basic\FrontController;

use plugin\xypm\app\model\api\Config;

use plugin\xypm\utils\Random;
use support\Request;
use support\Response;
use think\facade\Db;


/**
 * XYkeep会员接口
 */
class UserController extends FrontController
{
    protected $noNeedLogin = ['phone','login','h5wechat','wechat'];
    protected $noNeedRight = ['*'];
    /**
     * @var array|string[]
     */
    private array $allowFields;


    public function __construct()
    {

        $this->allowFields = ['id','username','nickname','mobile','avatar','level','gender','birthday','bio','money','score','successions','maxsuccessions','prevtime','logintime','loginip','jointime'];
    
        parent::__construct();
    }
	/**
     * 小程序微信授权登录
     */
    public function wechat(Request $request): Response
    {
        //设置过滤方法

		if ($request->isPost()) {
			$post = $request->post();
			$platform = request()->header('platform');
		   
		    // 获取配置
		    $config = Config::getValueByName('wxminiprogram');

			if(empty($config['app_id']) || empty($config['secret'])){
				return $this->fail(trans('请到后台配置中心平台配置中配置微信小程序AppID和AppSecret'));
			}

		        $params = [
			    'appid'    => $config['app_id'],
			    'secret'   => $config['secret'],
			    'js_code'  => $post['code'],
			    'grant_type' => 'authorization_code'
			];
			
			$ret = \think\facade\Db::transaction(function () use ($params,$platform) {
				$isInfo = 0;
				try {
					
					$result = \plugin\xypm\utils\Http::sendRequest("https://api.weixin.qq.com/sns/jscode2session", $params, 'GET');
					$json = (array)json_decode($result['msg'], true);
					
					// 判断third是否存在,存在快速登录
					$third = (new Third)->where(['platform' => $platform, 'openid' => $json['openid']])->find();
					
					if ($third && $third['user_id'] != 0) {
						//如果已经有账号则直接登录
						$isInfo = 2;
						$third->save([
							'openid' => $json['openid'],
							'logintime' => time(),
						]);
						$this->auth->direct($third['user_id']);
					} else {
					
						// 开始登录
						$isInfo = 1;
						$this->auth->register('U'.Random::alnum(6), Random::alnum(), '', '', [
							'nickname' => 'U-'.Random::nozero(6), 
							'avatar' => Config::getValueByName('xypm')['useravatar']?Config::getValueByName('xypm')['useravatar']:'',
                            'create_time' => time()
						]);

						// 新增$third
						$third = new Third();
						$third->platform  = $platform;
						$third->openid  = $json['openid'];
						$third->session_key  = $json['session_key'];
						$third->logintime  = time();
						$third->user_id  = $this->auth->id;
						$third->save();
						
					}
					return $isInfo;
				} catch (\Exception $e) {
					return $this->fail($e->getMessage());
				}
				return $isInfo;
			});		
			
		    if ($ret) {
			    return $this->success('登录成功', self::userInfo($ret));
		    } else {
			    return $this->fail($this->auth->getError());
		    }
		}
		return $this->fail(trans('非法请求'));
    }
	
	/**
     * 公众号微信授权登录
     */
    public function h5wechat(Request $request): Response
    {
        //设置过滤方法
		$request->filter(['strip_tags']);
		if ($request->isPost()) {
			$post = $request->post();
			$platform = request()->header('platform');
		   
		    // 获取配置
		    $config = Config::getValueByName('wxOfficialAccount');

			if(empty($config['app_id']) || empty($config['secret'])){
				return $this->fail(trans('请到后台配置中心平台配置中配置微信公众号AppID和AppSecret'));
			}

		        $params = [
			    'appid'    => $config['app_id'],
			    'secret'   => $config['secret'],
			    'code'       => $post['code'],
				'grant_type' => 'authorization_code'
			];
			
			$ret = \think\facade\Db::transaction(function () use ($params,$platform) {
				try {
					
					$result = \plugin\xypm\utils\Http::sendRequest('https://api.weixin.qq.com/sns/oauth2/access_token', $params, 'GET');
					if ($result['ret']) {
						$access = (array)json_decode($result['msg'], true);

						//获取用户信息
						$queryArr = [
							"access_token" => $access['access_token'],
							"openid"       => $access['openid']
						];
						$ret = \plugin\xypm\utils\Http::sendRequest("https://api.weixin.qq.com/sns/userinfo", $queryArr, 'GET');
						if ($ret['ret']) {
							$json = (array)json_decode($ret['msg'], true);
							$third = model('plugin\xypm\app\model\api\Third')->get(['platform' => $platform, 'openid' => $json['openid']]);
							if ($third && $third['user_id'] != 0) {
							    $third->save([
									'openid' => $json['openid'],
								    'logintime' => time(),
							    ]);
							    $ret = $this->auth->direct($third['user_id']);
							    
							} else {
							   

							    $nickname = $json['nickname'];
							    $avatar = $json['headimgurl'];
								
								// 注册账户
								$this->auth->register('U'.Random::alnum(6), Random::alnum(), '', '', [
									'nickname' => $nickname, 
									'avatar' => $avatar,
                                    'create_time' => time()
								]);
								
								// 新增$third
								$third = model('plugin\xypm\app\model\api\Third');
							    $third->platform  = $platform;
								$third->openid  = $json['openid'];
							    $third->logintime  = time();
							    $third->user_id  = $this->auth->id;
							    $third->save();
							}
							return true;
						}else{
							return $this->fail('获取用户信息失败！'); 
						}
					}else{
						return $this->fail('获取openid失败！'); 
					}
					return true;
				} catch (\Exception $e) {
					return $this->fail($e->getMessage());
				}
				return true;
			});		
			
		    if ($ret) {
			    return $this->success(trans('Logged in successful'), self::userInfo());
		    } else {
			    return $this->fail($this->auth->getError());
		    }
		}
		return $this->fail(trans('非法请求'));
    }

	/**
     * 登录
     */
    public function login(Request $request): Response
    {
		//设置过滤方法

		if ($request->isPost()) {
			$account = $request->post('mobile');
			$password = $request->post('password');
			if (!$account || !$password) {
				return $this->fail(trans('无效参数'));
			}
			$ret = $this->auth->login($account, $password);
			if ($ret) {
				return $this->success(trans('登录成功'), self::userInfo());
			} else {
				return $this->fail($this->auth->getError());
			}
		}
		return $this->fail(trans('非法请求'));
    }

	/**
     * 退出登录
     */
    public function logout(Request $request): Response
    {
        if (!$request->isPost()) {
            return $this->fail(trans('无效参数'));
        }
        $this->auth->logout();
        return $this->success(trans('注销成功'));
    }
    
    /**
     * 手机号授权登录
     */
    public function phone(Request $request): Response
    {
        //设置过滤方法

        $post = $request->post();
        $platform = request()->header('platform');
        if (!isset($post['iv'])) {
            return $this->fail(trans('获取手机号异常'));
        }
        // 获取配置
        $config = Config::getValueByName('wxminiprogram');
        // 微信小程序一键登录
        $params = [
            'appid'    => $config['app_id'],
            'secret'   => $config['secret'],
            'js_code'  => $post['code'],
            'grant_type' => 'authorization_code'
        ];


//        $ret = Db::transaction(function () use ($params,$platform,$config,$post) {
//            try {
//                $isInfo = $this->checkSession($params, $platform, $config, $post);
//            } catch (\Exception $e) {
//                return $this->fail($e->getMessage());
//            }
//            return $isInfo;
//         });
        $ret =0;

         $user_id = $this->checkSessionUser($params, $platform, $config, $post);

//          if ($ret) {
          if ($user_id) {
              $userinfo = self::userInfo($ret);
              file_put_contents("/tmp/wechat.log",json_encode($userinfo),FILE_APPEND);
              return $this->success('登录成功',$userinfo );
          }

        return $this->fail($this->auth->getError().$user_id);


    }
    private function checkSessionUser($params,$platform,$config,$post){
        $isInfo = 0;
        $result = \plugin\xypm\utils\Http::sendRequest("https://api.weixin.qq.com/sns/jscode2session", $params, 'GET');
        $json = (array)json_decode($result['msg'], true);
        file_put_contents("/tmp/wechat.log",json_encode($result),FILE_APPEND);
        // 判断third是否存在ID,存在快速登录
        $third = (new Third)->where(['platform' => $platform, 'openid' => $json['openid']])->find();

        if ($third && $third['user_id'] != 0) {
            //如果已经有账号则直接登录
            $isInfo = 2;
            file_put_contents("/tmp/wechat.log",json_encode($third),FILE_APPEND);
            $ok = $this->auth->direct($third['user_id']);
            if(!$ok){
//                file_put_contents("/tmp/wechat.log",$this->auth->getError(),FILE_APPEND);
                return -1;
            }
            return $third['user_id'];
        } else {
            // 手机号解码
            $decrypt = new wxBizDataCrypt($config['app_id'], $json['session_key']);
            $decrypt->decryptData($post['encryptedData'], $post['iv'], $data);
            $data = (array)json_decode($data, true);
            // 开始登录
            $mobile = $data['phoneNumber'];
            $user = (new User())->where('mobile', $mobile)->find();
            if ($user) {
                if ($user->status != 'normal') {
                    return $this->fail(('账号已经锁定'));
                }
                //如果已经有账号则直接登录
                $isInfo = 2;

                $ok = $this->auth->direct($user->id);
                if(!$ok){

                    return -1;
                }
                return $user['id'];
            } else {
                $isInfo = 1;
                $result = $this->auth->register('U' . Random::alnum(6), Random::alnum(), '', $mobile, [
                    'nickname' => 'U-' . Random::nozero(6),
                    'avatar' => Config::getValueByName('xypm')['useravatar'] ? Config::getValueByName('xypm')['useravatar'] : '',
                    'create_time' => time()
                ]);
                if(!$result){
                    $isInfo = -1;
                    return $isInfo;
//                    return $this->fail('用户注册失败');
                }
                return  $this->auth->id;

            }

            // 新增$third
            $third = new Third();
            $third->platform = $platform;
            $third->openid = $json['openid'];
            $third->session_key = $json['session_key'];
            $third->logintime = time();
            $third->user_id = $this->auth->id;
            $third->save();
            return  $this->auth->id;

        }


    }
	/**
	 * 刷新用户信息
	 */
	public function refresh(Request $request): Response
	{


		if ($request->isPost()) {
			return $this->success(trans('刷新成功'), self::userInfo());
		}
		return $this->fail(trans('非法请求'));
	}

	/**
     * 修改信息
     */
    public function profile(Request $request): Response
    {
        $user = $this->auth->getUser();
        $nickname = $request->post('nickname');
        $avatar = $request->post('avatar', '', 'trim,strip_tags,htmlspecialchars');
        
        $user->nickname = $nickname;
        if (!empty($avatar)) {
            $user->avatar = $avatar;
        }
        $user->save();
        return $this->success('修改成功',self::userInfo());
    }

	/**
	 * 登录后返回用户信息
	 */
	private function userInfo($isInfo = false)
	{
//		$userInfo = $this->auth->getUserinfo();
        $user = $this->auth->getUser();
        $user_id = $user['id'];
        $userInfo = User::field($this->auth->getAllowFields())->find($user_id);
		$userInfo['is_info'] = $isInfo;
		$userInfo['token'] = $this->auth->getToken();
		return[
			'userInfo' => $userInfo,
		];
	}
	
}