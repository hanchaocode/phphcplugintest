<?php

namespace plugin\xypm\app\controller\admin;

use plugin\saiadmin\basic\BaseController;
use plugin\xypm\app\logic\RepairLogic;
use plugin\xypm\app\model\admin\Repair;
use support\Request;
use support\Response;

/**
 * 报修管理
 *
 * @icon fa fa-circle-o
 */
class RepairController extends BaseController
{



    public function __construct()
    {
        $this->logic = new RepairLogic();

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
            ['buildname', ''],
            ['realname', ''],
            ['mobile', ''],
            ['createtime', ''],
            ['status', ''],

        ]);

        $query = $this->logic->search($where);

        $data = $this->logic->getList($query);
        return $this->success($data);
    }



    /**
     * 批量处理
     */
    public function handle(Request $request,$ids)
    {
        $status=$request->post('status');
        Repair::where('id','in',implode(',',$ids))->update(['status' => $status]);
        return $this->success('','操作成功');
    }

}
