<?php

namespace plugin\xypm\app\controller\api;

use plugin\xypm\app\model\api\Token;
use plugin\xypm\basic\FrontController;

use plugin\xypm\utils\Random;
use support\Request;
use support\Response;

/**
 * Token接口
 */
class TokenController extends FrontController
{
    protected $noNeedLogin = ['check'];
    protected $noNeedRight = ['*'];
    
    /**
     * 检测Token是否过期
     *
     */
    public function check(Request $request): Response
    {
        $token = getCurrentInfo();
        $tokenInfo =Token::where('token',$token)->find();
        if($tokenInfo == false){
            return $this->success('', ['token' => null]);
        }
        return $this->success('', ['token' => $tokenInfo['token'], 'expires_in' => $tokenInfo['expires_in']]);
    }

    /**
     * 刷新Token
     *
     */
    public function refresh(Request $request): Response
    {
        //删除源Token
        $token = $this->auth->getToken();
        Token::delete($token);
        //创建新Token
        $token = Random::uuid();
        Token::set($token, $this->auth->id, 2592000);
        $tokenInfo = Token::get($token);
        return $this->success('', ['token' => $tokenInfo['token'], 'expires_in' => $tokenInfo['expires_in']]);
    }
}
