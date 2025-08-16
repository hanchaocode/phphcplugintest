<?php

namespace plugin\xypm\app\controller\api;
use plugin\xypm\basic\FrontController;
use plugin\xypm\app\model\api\Suggest as SuggestModel;
use support\Request;
use support\Response;

/**
 * 投诉建议接口
 */
class SuggestController extends FrontController
{
    protected $noNeedLogin = [];
    protected $noNeedRight = ['*'];

    /**
	 * 列表
	 */
	public function lists(Request $request): Response
    {
    	$params = $request->post();
        $data = SuggestModel::getList($params);
        return $this->success('报名列表', $data);
    }

    /**
     * 添加
     */
	public function add(Request $request): Response
    {
        $params = $request->post();
        $signup = SuggestModel::add($params);
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
        return $this->success('报名详情', SuggestModel::getDetail($params));
    }
	
}