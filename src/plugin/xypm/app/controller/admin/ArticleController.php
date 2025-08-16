<?php

namespace plugin\xypm\app\controller\admin;

use plugin\saiadmin\basic\BaseController;
use plugin\xypm\app\logic\ArticleLogic;


use support\Request;
use support\Response;

/**
 * 文章管理
 *
 * @icon fa fa-circle-o
 */
class ArticleController extends BaseController
{



    public function __construct()
    {
        $this->logic = new ArticleLogic();

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
            ['views', ''],
            ['createtime', ''],
            ['status', ''],

        ]);

        $query = $this->logic->search($where);

        $data = $this->logic->getList($query);
        return $this->success($data);
    }



    /**
     * 选项列表
     * @param Request $request
     * @return Response
     */
    public function select(Request $request): Response
    {

        $data = $this->logic->options();


        return $this->success($data);
    }
}
