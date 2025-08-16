<?php

namespace plugin\xypm\app\controller\api;
use plugin\xypm\basic\FrontController;
use plugin\xypm\app\model\api\Notice as NoticeModel;
use plugin\xypm\app\model\api\user\View as ViewModel;
use support\Request;
use support\Response;

/**
 * 公告接口
 */
class NoticeController extends FrontController
{
    protected $noNeedLogin = ['lists','detail'];
    protected $noNeedRight = ['*'];
    
	/**
	 * 公告列表
	 */
	public function lists(Request $request): Response
    {
    	$params = $request->get();
        $data = NoticeModel::getList($params);
        return $this->success('公告列表', $data);
    }
	
    /**
     * 公告详情
     * @param Request $request
     * @param $id
     */
    public function detail(Request $request, $id): Response
    {
        $id = $request->get('id');
        $detail = NoticeModel::getDetail($id);
        if(!$detail){
            return $this->fail('公告不存在！');
        }
        ViewModel::addView($detail,'notice'); // 记录足记
        return $this->success('公告详情', $detail);
    }
	
}