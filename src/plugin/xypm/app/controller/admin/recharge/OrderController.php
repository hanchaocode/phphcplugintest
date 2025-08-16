<?php

namespace plugin\xypm\app\controller\admin\recharge;

use plugin\saiadmin\basic\BaseController;
use plugin\xypm\app\logic\recharge\OrderLogic;

use support\Request;
use support\Response;

/**
 * 充值订单管理
 *
 * @icon fa fa-circle-o
 */
class OrderController extends BaseController
{





    public function __construct()
    {
        $this->logic = new OrderLogic();

        parent::__construct();
    }

    /**
     * 数据列表
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $where = $request->more([
            ['id', ''],
            ['order_sn', ''],
            ['user_nickname', ''],
            ['pay_fee', ''],
            ['money', ''],
            ['platform', ''],
            ['createtime', ''],
            ['status', ''],
        ]);

        $query = $this->logic->search($where);
        $data = $this->logic->getList($query);
        return $this->success($data);
    }



    




}
