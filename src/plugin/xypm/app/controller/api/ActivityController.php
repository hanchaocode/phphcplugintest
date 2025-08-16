<?php

namespace plugin\xypm\app\controller\api;
use plugin\xypm\basic\FrontController;
use plugin\xypm\app\model\api\Activity as ActivityModel;
use plugin\xypm\app\model\api\user\View as ViewModel;
use support\Request;
use support\Response;

/**
 * 活动接口
 */
class ActivityController extends FrontController
{
    protected $noNeedLogin = ['lists','detail'];
    protected $noNeedRight = ['*'];
    
	/**
	 * 活动列表
	 */
	public function lists(Request $request): Response
    {
    	$params = $request->get();
        $data = ActivityModel::getList($params);
        return $this->success('活动列表', $data);
    }
	
    /**
     * 活动详情
     * @param Request $request
     * @param $id
     */
    public function detail(Request $request, $id): Response
    {
        $id = $request->get('id');
        $detail = ActivityModel::getDetail($id);
        if(!$detail){
            return $this->fail('活动不存在！');
        }
        ViewModel::addView($detail,'Activity'); // 记录足记
        return $this->success('活动详情', $detail);
    }
}