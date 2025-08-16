<?php

namespace plugin\xypm\app\controller\admin\bill;



use Exception;
use plugin\saiadmin\basic\BaseController;

use plugin\xypm\app\logic\bill\OrderLogic;

use support\Request;
use support\Response;
use think\facade\Db;
use think\exception\DbException;
use think\exception\PDOException;

use think\response\Json;


/**
 * 交费订单管理
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
            ['user_id', ''],
            ['realname', ''],
            ['mobile', ''],
            ['total_fee', ''],
            ['buildname', ''],
            ['buildtype', ''],
            ['pay_type', ''],
            ['platform', ''],
            ['createtime', ''],
            ['status', ''],
        ]);
        $query = $this->logic->search($where);
        $data = $this->logic->getList($query);
        return $this->success($data);
    }




}
