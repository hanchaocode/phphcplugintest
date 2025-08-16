<?php

namespace plugin\xypm\app\controller\api;
use plugin\xypm\basic\FrontController;
use plugin\xypm\app\model\api\goods\Order as GoodsOrderModel;
use support\Request;
use support\Response;

/**
 * 商城订单接口
 */
class GoodsOrderController extends FrontController
{
    protected $noNeedLogin = [];
    protected $noNeedRight = ['*'];
    

    /**
	 * 列表
	 */
	public function lists(Request $request): Response
    {
    	$params = $request->post();
        $data = GoodsOrderModel::getList($params);
        return $this->success('分销订单列表', $data);
    }

    /**
	 * 加载（确认订单数据）
	 */
	public function inits(Request $request): Response
    {
    	$params = $request->post();
        $data = GoodsOrderModel::getInitData($params);
        return $this->success('订单数据', $data);
    }

    /**
     * 创建
     */
	public function add(Request $request): Response
    {
        $params = $request->post();
        $order = GoodsOrderModel::addOrder($params);
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
        return $this->success('订单详情', GoodsOrderModel::getDetail($params));
    }

    public function cancel(Request $request): Response
    {
        $params = $request->post();
        return $this->success('取消成功', GoodsOrderModel::cancelOrder($params));
    }
    
    /**
     * 确认收货
     */
    public function take_delivery(Request $request): Response
    {
        $params = $request->post();
        return $this->success('收货成功', GoodsOrderModel::takeDelivery($params));
    }
	
}