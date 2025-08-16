<?php

namespace plugin\xypm\app\controller\api;
use plugin\xypm\basic\FrontController;
use plugin\xypm\app\model\api\Repair as RepairModel;
use support\Request;
use support\Response;

/**
 * 维修申报接口
 */
class RepairController extends FrontController
{
    protected $noNeedLogin = [];
    protected $noNeedRight = ['*'];

    /**
	 * 列表
	 */
	public function lists(Request $request): Response
    {
    	$params = $request->post();
        $data = RepairModel::getList($params);
        return $this->success('申报列表', $data);
    }

    /**
     * 添加
     */
	public function add(Request $request): Response
    {
        $params = $request->post();
        $signup = RepairModel::add($params);
        return $this->success('申报成功', $signup);
    }

    /**
     * 详情
     * @param Request $request
     * @param $id
     */
    public function detail(Request $request, $id): Response
    {
        $params = $request->post();
        return $this->success('申报详情', RepairModel::getDetail($params));
    }
	
}