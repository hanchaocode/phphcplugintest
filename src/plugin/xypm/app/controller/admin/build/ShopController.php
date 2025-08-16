<?php




namespace plugin\xypm\app\controller\admin\build;


use plugin\saiadmin\basic\BaseController;


use plugin\xypm\app\logic\build\ShopLogic;
use support\Request;
use support\Response;



/**
 * 商铺管理
 *
 * @icon fa fa-circle-o
 */
class ShopController extends BaseController
{




    public function __construct()
    {
        $this->logic = new ShopLogic();

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
            ['build_area_id', ''],
            ['ownername', ''],
            ['mobile', ''],
            ['floor', ''],
            ['number', ''],
            ['buildarea', ''],
            ['createtime', ''],
            ['status', ''],
        ]);
        $query = $this->logic->search($where);
        $data = $this->logic->getList($query);
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
        $build = $this->logic->search(['number' => $data['number']])->find();
        if (!empty($build)) {

            return  $this->fail('编号已存在');
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
        $house = $this->logic->search(['number' => $data['number']])->find();
        if (!empty($house) && $id != $house->id) {

            return  $this->fail('编号已存在');
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
     * 选项列表
     * @param Request $request
     * @return Response
     */
    public function select(Request $request): Response
    {

        $data = $this->logic->options();

        foreach ($data as &$d) {
            $d['buildname']= $d['area']['name'].'-'.$d['number'];

        }
        return $this->success($data);
    }

    /**
     * 添加数据
     * @param Request $request
     * @return Response
     */
    public function multiadd(Request $request)
    {




        $params = $request->post();

        $startNum = intval($params['startnum']);
        $endNum = intval($params['endnum']);

        if($startNum > $endNum){
            return $this->fail('结束编号需要大于等于开始编号');
        }

        if($endNum - $startNum > 10000){
            return $this->fail('单次最多添加10000条数据,请更改开始结束编号');
        }

        $data = [];
        for($i = $startNum;$i <= $endNum;$i++){
            $number = $params['nameprefix'].$i.$params['suffix'];
            $shop = $this->logic->search(['number'=>$number])->find();
            if(empty($shop)){
                array_push($data,['number'=>$number,'build_area_id'=>$params['build_area_id'],'floor'=>$params['floor']]);
            }
        }



        $key = $this->logic->batchadd($data);
        $this->afterChange('save', $key);
        return $this->success('操作成功');
    }
    /**
     * 删除数据
     * @param Request $request
     * @return Response
     */
    public function destroy(Request $request) : Response
    {
        $ids = $request->input('ids', '');
        if (!empty($ids)) {

            $list = $this->logic->search(['id'=> $ids])->select();

            foreach ($list as $item) {
                if($item['status'] == 1){

                    return $this->fail('商铺'.$item['number'].'已绑定户主，请先在户主管理-》商铺户主中删除对应户主信息');
                }

            }
            $this->logic->destroy($ids);
            $this->afterChange('destroy', $ids);
            return $this->success('操作成功');
        }
       return $this->fail('参数错误，请检查');

    }




}
