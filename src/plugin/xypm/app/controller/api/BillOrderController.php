<?php

namespace plugin\xypm\app\controller\api;
use plugin\xypm\basic\FrontController;
use plugin\xypm\app\model\api\bill\Order as BillOrderModel;
use support\Request;
use support\Response;

/**
 * 账单订单接口
 */
class BillOrderController extends FrontController
{
    protected $noNeedLogin = [];
    protected $noNeedRight = ['*'];
    

    /**
	 * 列表
	 */
	public function lists(Request $request): Response
    {
    	$params = $request->post();
        $data = BillOrderModel::getList($params);
        return $this->success('订单列表', $data);
    }

    /**
	 * 加载（确认订单数据）
	 */
	public function init2(Request $request): Response
    {
    	$params = $request->post();
        $data = BillOrderModel::getInitData($params);
        return $this->success('订单数据', $data);
    }

    /**
     * 创建
     */
	public function add(Request $request): Response
    {
        $params = $request->post();
        $order = BillOrderModel::addOrder($params);
        return $this->success('订单创建成功', $order);
    }
	
}