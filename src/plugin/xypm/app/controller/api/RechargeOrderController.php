<?php

namespace plugin\xypm\app\controller\api;
use plugin\xypm\basic\FrontController;
use plugin\xypm\app\model\api\recharge\Order as RechargeOrderModel;
use support\Request;
use support\Response;

/**
 * 充值订单接口
 */
class RechargeOrderController extends FrontController
{
    protected $noNeedLogin = [];
    protected $noNeedRight = ['*'];
    


    /**
     * 创建
     */
	public function add(Request $request): Response
    {
        $params = $request->post();
        $order = RechargeOrderModel::addOrder($params);
        return $this->success('订单创建成功', $order);
    }

    /**
     * 详情
     * @param Request $request
     * @param $id
     */
    public function detail(Request $request, $id): Response
    {
        $params = $request->post();
        return $this->success('充值订单详情', RechargeOrderModel::getDetail($params));
    }
   
	
}