<?php

namespace plugin\xypm\app\controller\admin\user;

use plugin\saiadmin\basic\BaseController;
use plugin\xypm\app\logic\user\MoneyLogic;
use support\Request;
use support\Response;

/**
 * 会员余额明细管理
 *
 * @icon fa fa-circle-o
 */
class MoneyController extends BaseController
{


    public function __construct()
    {
        $this->logic = new MoneyLogic();

        parent::__construct();
    }


    /**
     * 查看
     */
    public function index(Request $request):Response
    {


        $where = $request->more([
            ['id', ''],
            ['type', ''],
            ['path', ''],
            ['status', ''],
        ]);
        $user_id = $request->get('user_id');
        $where['user_id'] = $user_id;
        $query = $this->logic->search($where);

        $data = $this->logic->getList($query);

        return $this->success($data);

    }


}
