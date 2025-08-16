<?php

namespace plugin\xypm\app\controller\api;

use plugin\xypm\basic\FrontController;
use plugin\xypm\app\model\api\Member as MemberModel;
use support\Request;
use support\Response;

/**
 * 小区成员接口
 */
class MemberController extends FrontController
{
    protected $noNeedLogin = [];
    protected $noNeedRight = ['*'];
    

	/**
	 * 户主成员列表
	 */
	public function lists(Request $request): Response
    {
        $member_id = $request->get('member_id', 0);
        $page = $request->get('page', 1);
        $data = MemberModel::getList(['pid'=>$member_id,'page'=>$page]);
        return $this->success('成员列表', $data);
    }

    /**
     * 户主添加成员
     */
	public function add(Request $request): Response
    {
        $params = $request->post();
        $signup = MemberModel::add($params);
        return $this->success('报名成功', $signup);
    }

    /**
     * 删除
     */
    public function del(Request $request): Response
    {
        $id = $request->input('id');
        return $this->success('删除成功', MemberModel::del($id));
    }
    
	
}