<?php

namespace plugin\xypm\basic;


use plugin\saiadmin\basic\OpenController;
use plugin\xypm\exception\ApiException;
use plugin\xypm\utils\Auth;
use support\Request;
use support\Response;

/**
 * user API 控制器基类
 */
class FrontController
{
    /**
     * 不需要登录的方法列表
     * @var array
     */
    protected $noNeedLogin = [];

    protected $type = 'frontend';




    /**
     * 构造方法
     * @access public
     */
    public function __construct()
    {
        // 控制器初始化
        $this->init();
    }



    /**
     * 当前登录用户信息
     */
    protected $userinfo;




    /**
     * 逻辑层注入
     */
    protected $logic;

    /**
     * 验证器注入
     */
    protected $validate;

    /**
     * 初始化
     */

    protected function init(): void
    {
        $this->checkDefaultMethod();

        $action = strtolower(request()->action);
        $token = request()->header('TOKEN', request()->cookie('token'));

        $this->auth = Auth::instance([],$token);

        // 检查是否需要登录
        if (!in_array($action, $this->noNeedLogin)) {

            if ($token) {
                try {
                    $success = $this->auth->init($token);
                    if(!$success){
                        throw new ApiException($this->auth->getError(), 401);
                    }
                    if (!$this->auth->isLogin()) {
                        throw new ApiException('请先登录', 401);
                    }
                    $this->userinfo = $this->auth->getUserinfo();
                } catch (ApiException $e) {
                    throw $e;
                } catch (\Exception $e) {
                    throw new ApiException('令牌无效或已过期', 401);
                }
            } else {
                throw new ApiException('无效登录状态，请重新登录', 401);
            }
        }
    }




    /**
     * 检查默认方法
     * @return void
     */
    protected function checkDefaultMethod()
    {
        $functions = [
//            'lists' => 'get',
            'add' => 'post',
            'update' => 'put',
//            'detail' => 'get',
            'del' => 'post',
        ];

        $action = strtolower(request()->action);
        if (array_key_exists($action, $functions)) {
            $this->checkMethod($functions[$action]);
        }
    }

    /**
     * 验证请求方式
     * @param string $method
     * @return void
     */
    protected function checkMethod(string $method)
    {
        $m = strtolower(request()->method());
        if ($m !== strtolower($method)) {
            throw new ApiException('未找到页面', 404);
        }
    }

    /**
     * 列表数据
     * @param Request $request
     * @return Response
     */
    public function lists(Request $request): Response
    {
        $data = $this->logic->lists();
        return $this->success($data);
    }

    /**
     * 添加数据
     * @param Request $request
     * @return Response
     */
    public function add(Request $request): Response
    {
        $data = $request->post();
        if ($this->validate) {
            if (!$this->validate->scene('add')->check($data)) {
                return $this->fail($this->validate->getError());
            }
        }
        $key = $this->logic->add($data);
        if ($key > 0) {
            $this->afterChange('add', $key);
            return $this->success('操作成功');
        } else {
            return $this->fail('操作失败');
        }
    }

    /**
     * 删除数据
     * @param Request $request
     * @return Response
     */
    public function del(Request $request): Response
    {
        $ids = $request->input('ids', '');
        if (!empty($ids)) {
            $this->logic->del($ids);
            $this->afterChange('del', $ids);
            return $this->success('操作成功');
        } else {
            return $this->fail('参数错误，请检查');
        }
    }

    /**
     * 详情数据
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function detail(Request $request, $id): Response
    {
        $id = $request->input('id', $id);
        $model = $this->logic->detail($id);
        if ($model) {
            $data = is_array($model) ? $model : $model->toArray();
            return $this->success($data);
        } else {
            return $this->fail('未查找到信息');
        }
    }

    /**
     * 数据改变后执行
     * @param string $type 类型
     * @param $args
     */
    protected function afterChange(string $type, $args): void
    {
        // todo
    }

    /**
     * 操作成功返回的数据
     * @param mixed $data 要返回的数据
     * @param string $msg 提示信息
     * @return Response
     */
    public function success( $msg = '操作成功',$data = null): Response
    {
        return json(['code' => 1, 'msg' => $msg, 'data' => $data]);
    }

    /**
     * 操作失败返回的数据
     * @param string $msg 提示信息
     * @return Response
     */
    public function fail($msg = '操作失败'): Response
    {
        return json(['code' => 0, 'msg' => $msg]);
    }

}
