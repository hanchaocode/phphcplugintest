<?php



namespace plugin\xypm\app\controller\admin\build;

use plugin\saiadmin\basic\BaseController;
use plugin\xypm\app\logic\build\BuildLogic;
use plugin\xypm\app\logic\build\HouseLogic;
use plugin\xypm\app\logic\build\MemberLogic;
use plugin\xypm\app\model\admin\Build;
use support\Request;
use support\Response;



/**
 * 房屋管理
 *
 * @icon fa fa-circle-o
 */
class HouseController extends BaseController
{




    public function __construct()
    {
        $this->logic = new HouseLogic();

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
            ['build_id', ''],
            ['unit', ''],
            ['floor', ''],
            ['createtime', ''],
            ['number', ''],
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

            return  $this->fail('房号已存在');
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

            return  $this->fail('房号已存在');
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
            $d['buildname']= $d['build']['name'].$d['unit'].'单元'.$d['number'];

        }
        return $this->success($data);
    }

    /**
     * 添加数据
     * @param Request $request
     * @return Response
     */
    public function multiadd(Request $request) : Response
    {
        $data = $request->post();
        if ($this->validate) {
            if (!$this->validate->scene('save')->check($data)) {
                return $this->fail($this->validate->getError());
            }
        }
        $unithouse = intval($data['unithouse']); //单元户数
        if($unithouse < 1){
            $this->fail('单元户数输入错误', '');
        }

        $buildIds = explode(',',$data['build_id']);

        sort($buildIds);

        $allNums = 0;

        foreach($buildIds as $bid){
            $build = (new BuildLogic)->read($bid);

            $floornum = $build['floornum']; //楼宇层数
            $unitnum = $build['unitnum']; //单元数量

            $allNums += $floornum * $unitnum * $unithouse;

            if($allNums > 20000){
                $this->fail('单次生成房屋数量不能超过20000');
            }
        }

        $data = [];
        foreach($buildIds as $bid){
            $build = (new BuildLogic)->read($bid);

            $floornum = $build['floornum']; //楼宇层数
            $unitnum = $build['unitnum']; //单元数量
            for($i = 1;$i <= $floornum;$i++){
                for($k =1;$k<=$unitnum;$k++){
                    for($j=1;$j<=$unithouse;$j++){
                        $tn = $j+($k-1)*$unithouse;
                        $number = $i .($tn<10?'0'.$tn:$tn);// 房号
                        $hourse = $this->logic->search(['build_id'=>$bid,'number'=>$number])->find();
                        if(empty($hourse)){
                            array_push($data,['build_id'=>$bid,'unit'=>$k,'floor'=>$i, 'number'=>$number]);
                        }
                    }
                }
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

                    return $this->fail('房号'.$item['number'].'的房屋已绑定户主，请先在户主管理-》房屋户主中删除对应户主信息');
                }

            }
            $this->logic->destroy($ids);
            $this->afterChange('destroy', $ids);
            return $this->success('操作成功');
        } else {
            return $this->fail('参数错误，请检查');
        }
    }




}
