<?php



namespace plugin\xypm\app\controller\admin\activity;

use plugin\saiadmin\basic\BaseController;
use plugin\xypm\app\logic\activity\ActivityLogic;
use support\Request;
use support\Response;



/**
 * 活动管理
 *
 * @icon fa fa-circle-o
 */
class ActivityController extends BaseController
{



    public function __construct()
    {
        $this->logic = new ActivityLogic();

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
            ['title', ''],
            ['organizer', ''],
            ['createtime', ''],
            ['status', ''],
        ]);
        $query = $this->logic->search($where);
        $data = $this->logic->getList($query);
        return $this->success($data);
    }












}
