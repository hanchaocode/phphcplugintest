<?php




namespace plugin\xypm\app\controller\admin\build;

use plugin\saiadmin\basic\BaseController;
use plugin\xypm\app\logic\build\AreaLogic;
use support\Request;
use support\Response;



/**
 *区域管理
 *
 * @icon fa fa-circle-o
 */
class AreaController extends BaseController
{




    public function __construct()
    {
        $this->logic = new AreaLogic();

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
            ['name', ''],
            ['type', ''],
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
        $where = $request->more([
            ['type', ''],

        ]);
        $data = $this->logic->options($where['type']);
        return $this->success($data);
    }

    /**
     * 添加数据
     * @param Request $request
     * @return Response
     */
    public function save(Request $request) : Response
    {
        $data = $request->post();
        if ($this->validate) {
            if (!$this->validate->scene('save')->check($data)) {
                return $this->fail($this->validate->getError());
            }
        }
        $build = $this->logic->search(['name' => $data['name']])->find();
        if (!empty($build)) {

            return  $this->fail('区域已存在');
        }
        $key = $this->logic->add($data);
        if ($key > 0) {
            $this->afterChange('save', $key);
            return $this->success('操作成功');
        } else {
            return $this->fail('操作失败');
        }
    }

    /**
     * 修改数据
     * @param $id
     * @param Request $request
     * @return Response
     */
    public function update(Request $request, $id) : Response
    {
        $id = $request->input('id', $id);
        if (empty($id)) {
            return $this->fail('参数错误，请检查');
        }
        $data = $request->post();
        if ($this->validate) {
            if (!$this->validate->scene('update')->check($data)) {
                return $this->fail($this->validate->getError());
            }
        }
        $build = $this->logic->search(['name' => $data['name']])->find();
        if (!empty($build) && $id != $build->id) {

            return  $this->fail('区域已存在');
        }
        $result = $this->logic->edit($id, $data);
        if ($result) {
            $this->afterChange('update', $result);
            return $this->success('操作成功');
        } else {
            return $this->fail('操作失败');
        }
    }






}
