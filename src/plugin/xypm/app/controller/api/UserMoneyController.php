<?php

namespace plugin\xypm\app\controller\api;
use plugin\xypm\basic\FrontController;
use plugin\xypm\app\model\api\user\Money as UserMoneyModel;
use support\Request;
use support\Response;

/**
 * XYkeep用户余额接口
 */
class UserMoneyController extends FrontController
{
    protected $noNeedLogin = [];
    protected $noNeedRight = ['*'];
    

	/**
	 * 列表
	 */
	public function lists(Request $request): Response
    {
    	$params = $request->post();
        $data = UserMoneyModel::getList($params);
        return $this->success('账户列表', $data);
    }

    
}