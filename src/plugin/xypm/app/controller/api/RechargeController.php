<?php

namespace plugin\xypm\app\controller\api;
use plugin\xypm\basic\FrontController;
use plugin\xypm\app\model\api\recharge\Recharge as RechargeModel;
use support\Request;
use support\Response;

/**
 * 充值套餐
 */
class RechargeController extends FrontController
{
    protected $noNeedLogin = ['lists','detail'];
    protected $noNeedRight = ['*'];
    
	/**
	 * 列表
	 */
	public function lists(Request $request): Response
    {
    	$params = $request->get();
        $data = RechargeModel::getList($params);
        return $this->success('套餐列表', $data);
    }
   
	
}