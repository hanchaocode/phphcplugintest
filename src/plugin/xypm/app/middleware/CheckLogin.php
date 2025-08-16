<?php
namespace plugin\xypm\app\middleware;

use ReflectionClass;
use Tinywan\Jwt\JwtToken;
use Webman\Http\Request;
use Webman\Http\Response;
use Webman\MiddlewareInterface;
use plugin\xypm\exception\ApiException;
use plugin\xypm\utils\Auth;

/**
 * 登录检查中间件
 */
class CheckLogin implements MiddlewareInterface
{
    public function process(Request $request, callable $handler): Response
    {
        // 通过反射获取控制器哪些方法不需要登录
        $controller = new ReflectionClass($request->controller);
        $noNeedLogin = $controller->getDefaultProperties()['noNeedLogin'] ?? [];
        $apiType = $controller->getDefaultProperties()['type'] ?? '';

//        throw new ApiException('您的登录凭证错误或者已过期，请重新登录'.in_array($request->action, $noNeedLogin), 401);
        // 访问的方法需要登录
        if (!in_array($request->action, $noNeedLogin)) {

            if ($apiType && $apiType == 'frontend') {
                // Assuming token is passed in the Authorization header
                $token = $request->header('TOKEN');
                $auth = Auth::instance([],$token);

                if (empty($token)) {
                    throw new ApiException($auth->getError() ?: '您的登录凭证错误或者已过期，请重新登录', 401);
                }
                if (!$auth->init($token)) {
                    throw new ApiException($auth->getError() ?: '您的登录凭证错误或者已过期，请重新登录', 401);
                }

                $request->setHeader('check_login', true);

//                $request->setHeader('check_user', $auth->getUserinfo());
            }
            else{

                try {
                    $token = JwtToken::getExtend();
                } catch (\Throwable $e) {
                    throw new ApiException('您的登录凭证错误或者已过期，请重新登录', 401);
                }
                if ($token['plat'] !== 'saiadmin') {
                    throw new ApiException('登录凭证校验失败');
                }
                $request->setHeader('check_login', true);
                $request->setHeader('check_admin', $token);
            }




        }

        return $handler($request);
    }
}
