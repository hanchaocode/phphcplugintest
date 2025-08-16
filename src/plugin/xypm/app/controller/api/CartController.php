<?php

namespace plugin\xypm\app\controller\api;
use plugin\xypm\basic\FrontController;
use plugin\xypm\app\model\api\Cart as CartModel;
use support\Request;
use support\Response;

/**
 * 购物车接口
 */
class CartController extends FrontController
{

    protected $noNeedLogin = [];
    protected $noNeedRight = ['*'];

    public function lists(Request $request): Response
    {
        $data = CartModel::getList();
        return $this->success('购物车列表', $data);
    }

    public function add(Request $request): Response
    {
        $params = $request->post();
        return $this->success('添加成功', CartModel::add($params));
    }

    public function edit(Request $request): Response
    {
        $params = $request->post();
        return $this->success('', CartModel::edit($params));
    }

}