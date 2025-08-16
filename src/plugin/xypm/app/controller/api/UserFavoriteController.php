<?php

namespace plugin\xypm\app\controller\api;
use plugin\xypm\basic\FrontController;
use plugin\xypm\app\model\api\user\Favorite as UserFavoriteModel;
use support\Request;
use support\Response;

/**
 * 会员收藏接口
 */
class UserFavoriteController extends FrontController
{
    protected $noNeedLogin = [];
    protected $noNeedRight = ['*'];
    

	/**
	 * 列表
	 */
	public function lists(Request $request): Response
    {
    	$params = $request->post();
        $data = UserFavoriteModel::getList($params);
        return $this->success('账户列表', $data);
    }

    /**
     * 新增｜删除
     */
    public function add(Request $request): Response
    {
        $params = $request->post();
        $result = UserFavoriteModel::add($params);
        return $this->success($result ? '收藏成功' : '取消收藏', $result);
    }


	
}