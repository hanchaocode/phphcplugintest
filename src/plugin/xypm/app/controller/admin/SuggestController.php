<?php

namespace plugin\xypm\app\controller\admin;

use plugin\saiadmin\basic\BaseController;
use plugin\xypm\app\logic\SuggestLogic;
use plugin\xypm\app\model\admin\Suggest;
use support\Request;
use support\Response;

/**
 * 投诉建议管理
 *
 * @icon fa fa-circle-o
 */
class SuggestController extends BaseController
{



    public function __construct()
    {
        $this->logic = new SuggestLogic();

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
            ['content', ''],
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
        Suggest::where('id','in',implode(',',$ids))->update(['status' => $status]);
        return $this->success('','操作成功');
    }


}
