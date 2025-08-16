<?php

namespace plugin\xypm\app\controller\api;
use plugin\xypm\basic\FrontController;
use plugin\xypm\app\model\api\activity\Signup as ActivitySignupModel;
use support\Request;
use support\Response;


/**
 * 活动报名接口
 */
class ActivitySignupController extends FrontController

{

    protected $name = 'activity_signup';
    protected $noNeedLogin = [];
    protected $noNeedRight = ['*'];

    /**
	 * 列表
	 */
	public function lists(Request $request): Response
    {
    	$params = $request->post();
        $data = ActivitySignupModel::getList($params);
        return $this->success('报名列表', $data);
    }

    /**
     * 报名
     */
	public function add(Request $request): Response
    {
        $params = $request->post();
        $signup = ActivitySignupModel::add($params);
        return $this->success('报名成功', $signup);
    }

    /**
     * 详情
     * @param Request $request
     * @param $id
     */
    public function detail(Request $request, $id): Response
    {
        $params = $request->post();
        return $this->success('报名详情', ActivitySignupModel::getDetail($params));
    }



}