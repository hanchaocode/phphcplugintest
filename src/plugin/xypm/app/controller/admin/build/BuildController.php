<?php




namespace plugin\xypm\app\controller\admin\build;


use plugin\saiadmin\basic\BaseController;
use plugin\xypm\app\logic\build\BuildLogic;
use support\Request;
use support\Response;



/**
 * 楼宇管理
 *
 * @icon fa fa-circle-o
 */
class BuildController extends BaseController
{




    public function __construct()
    {
        $this->logic = new BuildLogic();
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
            ['number', ''],
            ['unitnum', ''],
            ['floornum', ''],
            ['roomnum', ''],
            ['createtime', ''],
            ['status', ''],
            ['name', ''],
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

            return  $this->fail('楼宇已存在');
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

            return  $this->fail('楼宇已存在');
        }
        $result = $this->logic->edit($id, $data);
        if ($result) {
            $this->afterChange('update', $result);
            return $this->success('操作成功');
        } else {
            return $this->fail('操作失败');
        }
    }


    /**
     * 批量添加数据
     * @param Request $request
     * @return Response
     */
    public function multiadd(Request $request) : Response
    {
        $params = $request->post();
        if ($this->validate) {
            if (!$this->validate->scene('save')->check($params)) {
                return $this->fail($this->validate->getError());
            }
        }

        $startNum = intval($params['startnum']);
        $endNum = intval($params['endnum']);

        if($startNum > $endNum){
            $this->fail('结束编号需要大于等于开始编号');
        }

        if($endNum - $startNum > 10000){
            $this->fail('单次最多添加10000条数据,请更改开始结束编号');
        }

        $data = [];
        for($i = $startNum;$i <= $endNum;$i++){
            $name = $params['nameprefix'].$i.$params['suffix'];

            $build = $this->logic->search(['name' => $name])->find();
            if(empty($build)){
                array_push($data,['name'=>$name,'unitnum'=>$params['unitnum'],'floornum'=>$params['floornum'],'roomnum'=>$params['roomnum'],'status'=>$params['status']]);
            }
        }

        $this->logic->batchadd($data);

        return $this->success('操作成功');
    }







}
