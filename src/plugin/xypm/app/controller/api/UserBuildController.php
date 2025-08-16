<?php

namespace plugin\xypm\app\controller\api;

use plugin\xypm\basic\FrontController;
use plugin\xypm\app\model\api\user\Build as UserBuildModel;
use support\Request;
use support\Response;

/**
 * 会员房产接口
 */
class UserBuildController extends FrontController
{
    protected $noNeedLogin = [];
    protected $noNeedRight = ['*'];
    

	/**
	 * 列表
	 */
	public function lists(Request $request): Response
    {
    	$params = $request->post();
        $data = UserBuildModel::getList($params);
        return $this->success('账户列表', $data);
    }


    /**
     * 代缴账单
     */
    public function bill(Request $request): Response
    {
        $params = $request->post();
        $data = UserBuildModel::getBillList($params);
        return $this->success('代缴账单', $data);
    }

    /**
     * 绑定
     */
    public function bind(Request $request): Response
    {
        $params = $request->post();
        
        $bind = UserBuildModel::bind($params);
        return $this->success('绑定成功', $bind);
    }

    /**
     * 详情
     * @param Request $request
     * @param $id
     */
    public function detail(Request $request, $id=null): Response
    {
        $id = $request->post('id');
        $detail = UserBuildModel::getDetail($id);

        if(!$detail){
            return $this->fail('账户不存在！');
        }
        return $this->success('账户详情', $detail);
    }

    /**
     * 删除
     */
    public function del(Request $request): Response
    {
        $id = $request->get('id');
        return $this->success('删除成功', UserBuildModel::del($id));
    }

    /**
     * 设置默认
     */
     public function default(Request $request): Response
     {
         $id = $request->get('id');
         return $this->success('设置成功', UserBuildModel::setDefault($id));
     }
	
}