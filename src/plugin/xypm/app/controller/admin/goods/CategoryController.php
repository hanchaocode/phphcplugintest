<?php

namespace plugin\xypm\app\controller\admin\goods;



use plugin\saiadmin\basic\BaseController;
use plugin\xypm\app\logic\CategoryLogic;

use support\Request;
use support\Response;

use think\exception\DbException;
use think\exception\PDOException;



/**
 * 分类管理
 */
class CategoryController extends BaseController
{






    public function __construct()
    {
        $this->logic = new CategoryLogic();


        parent::__construct();
    }


    /**
     * 查看
     */
    public function index(Request $request):Response
    {


        $where = $request->more([
            ['name', ''],
            ['status', ''],

        ]);
        $where['type'] = 'goods';

//        $data = $this->logic->search($where)->select();

        $data = $this->logic->tree($where);


        return $this->success($data);

    }





}
