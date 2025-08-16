<?php



namespace plugin\xypm\app\controller\admin\recharge;

use plugin\saiadmin\basic\BaseController;

use plugin\xypm\app\logic\recharge\RechargeLogic;
use support\Request;
use support\Response;



/**
 * 充值管理
 *
 * @icon fa fa-circle-o
 */
class RechargeController extends BaseController
{



    public function __construct()
    {
        $this->logic = new RechargeLogic();

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
            ['facevalue', ''],
            ['buyprice', ''],
            ['status', ''],
            ['createtime', ''],
        ]);
        $query = $this->logic->search($where);
        $data = $this->logic->getList($query);
        return $this->success($data);
    }












}
