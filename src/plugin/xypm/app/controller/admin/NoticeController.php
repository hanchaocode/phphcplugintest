<?php

namespace plugin\xypm\app\controller\admin;

use plugin\saiadmin\basic\BaseController;
use plugin\xypm\app\logic\NoticeLogic;
use support\Request;
use support\Response;

/**
 * 公告管理
 *
 * @icon fa fa-circle-o
 */
class NoticeController extends BaseController
{



    public function __construct()
    {
        $this->logic = new NoticeLogic();

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
            ['createtime', ''],
            ['status', ''],

        ]);

        $query = $this->logic->search($where);

        $data = $this->logic->getList($query);
        return $this->success($data);
    }

}
