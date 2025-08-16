<?php

namespace plugin\xypm\app\controller\admin;




use plugin\xypm\app\logic\build\GarageLogic;
use plugin\xypm\app\logic\build\HouseLogic;
use plugin\xypm\app\logic\build\ParkingLogic;
use plugin\xypm\app\logic\build\ShopLogic;
use plugin\xypm\app\model\admin\user\Build as UserBuildModel;

use plugin\saiadmin\basic\BaseController;

use plugin\xypm\app\logic\MemberLogic;
use support\Request;
use support\Response;





/**
 * 户主管理
 *
 * @icon fa fa-circle-o
 */
class MemberController extends BaseController
{



    public function __construct()
    {
        $this->logic = new MemberLogic();

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
            ['buildtype', ''],
            ['build_id', ''],
            ['realname', ''],
            ['mobile', ''],
            ['idcard', ''],
            ['remark', ''],
            ['status', ''],
            ['createtime', ''],
        ]);
        $where['identity'] = 1;
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
        $type = $data['buildtype'];
        $buildTypeList = $this->logic->getBuildTypeList();
        if(empty($data['build_id'])){
            $this->fail('请选择'.$buildTypeList[$type]);
        }
        $build = $this->logic->search(['build_id' => $data['build_id'],'buildtype'=>$type])->find();
        if (!empty($build)) {
            return  $this->fail($buildTypeList[$type].$data['buildname'].'户主已添加');
        }

        $save_data = [
            'ownername'=>$data['realname'],
            'mobile'=>$data['mobile'],
            'buildarea'=>$data['buildarea'],
            'billdate'=>$data['billdate'],
            'status'=>1
        ];
        if($type == 'house'){
            $model = (new HouseLogic())->read($data['build_id']);
            if ($model->isEmpty()) {
                return $this->fail('未查找到房屋信息');
            }
            $save_data['buildarea'] = $data['buildarea'];
            $model->save($save_data);
        }

        if($type == 'shop'){
            $model = (new ShopLogic())->findOrEmpty($data['build_id']);
            if ($model->isEmpty()) {
                return $this->fail('未查找到房屋信息');
            }
            $save_data['buildarea'] = $data['buildarea'];
            $model->save($save_data);
        }

        if($type == 'parking'){
            $model = (new ParkingLogic)->findOrEmpty($data['build_id']);
            if ($model->isEmpty()) {
                return $this->fail('未查找到房屋信息');
            }
            $model->save($save_data);

        }

        if($type == 'garage'){

            $model = (new GarageLogic)->findOrEmpty($data['build_id']);
            if ($model->isEmpty()) {
                return $this->fail('未查找到房屋信息');
            }
            $model->save($save_data);
        }

        $data['buildtype'] = $type;
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
        $type = $data['buildtype'];
        $save_data = [
            'ownername'=>$data['realname'],
            'mobile'=>$data['mobile'],

            'billdate'=>$data['billdate'],
            'status'=>1
        ];
        $row = $this->logic->search(['id'=>$id])->find();
        if($data['build_id'] != $row['build_id']){
            $member = $this->logic->search(['build_id'=>$data['build_id'],'buildtype'=>$row['buildtype']])->find();
            $buildTypeList =  $this->logic->getBuildTypeList();
            if(!empty($member)){
                $this->fail($buildTypeList['house'].$data['buildname'].'户主已添加');
            }
        }
        if($type == 'house'){
            $model = (new HouseLogic)->findOrEmpty($data['build_id']);
            if ($model->isEmpty()) {
                return $this->fail('未查找到房屋信息');
            }
            $save_data['buildarea'] = $data['buildarea'];
//            $model->save($save_data);
            (new HouseLogic)->edit($data['build_id'], $save_data);
        }

        if($type == 'shop'){
            $model = (new ShopLogic)->findOrEmpty($data['build_id']);
            if ($model->isEmpty()) {
                return $this->fail('未查找到房屋信息');
            }
            $save_data['buildarea'] = $data['buildarea'];
//            $model->save($save_data);
            (new GarageLogic)->edit($data['build_id'], $save_data);
        }

        if($type == 'parking'){
            $model = (new ParkingLogic)->findOrEmpty($data['build_id']);
            if ($model->isEmpty()) {
                return $this->fail('未查找到房屋信息');
            }
//            $model->save($save_data);
            (new ParkingLogic)->edit($data['build_id'], $save_data);

        }

        if($type == 'garage'){
            $this->logic->add($data);
            $model = (new GarageLogic)->findOrEmpty($data['build_id']);
            if ($model->isEmpty()) {
                return $this->fail('未查找到房屋信息');
            }
//            $model->save($save_data);
            (new GarageLogic)->edit($data['build_id'], $save_data);
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
     * 删除数据
     * @param Request $request
     * @return Response
     */
    public function destroy(Request $request) : Response
    {
        $ids = $request->input('ids', '');
        if (!empty($ids)) {



            $list = $this->logic->where('id','in', $ids)->select();

            foreach ($list as $item) {
                if($item['status'] == 1){
                    return $this->fail('户主'.$item['realname'].'已绑定会员，请先解绑会员在删除');
                }else if($item['mnums'] > 0){
                    return $this->fail('户主'.$item['realname'].'下存在成员,请先删除成员');
                }

            }
            foreach ($list as $item) {
                $type =$item['buildtype'];
                $save_data = [
                    'ownername' => '',
                    'mobile' => '',
                    'status' => 0
                ];
                if ($type == 'house') {
                    $model = (new HouseLogic)->findOrEmpty($item['build_id']);
                    if ($model->isEmpty()) {
                        return $this->fail('未查找到房屋信息');
                    }
                    $model->save($save_data);

                }

                if ($type == 'shop') {
                    $model = (new ShopLogic)->findOrEmpty($item['build_id']);
                    if ($model->isEmpty()) {
                        return $this->fail('未查找到房屋信息');
                    }
                    $model->save($save_data);

                }

                if ($type == 'parking') {
                    $model = (new ParkingLogic)->findOrEmpty($item['build_id']);
                    if ($model->isEmpty()) {
                        return $this->fail('未查找到房屋信息');
                    }
                    $model->save($save_data);
                }

                if ($type == 'garage') {


                    $model = (new GarageLogic)->findOrEmpty($item['build_id']);
                    if ($model->isEmpty()) {
                        return $this->fail('未查找到房屋信息');
                    }
                    $model->save($save_data);
                }
                $this->logic->destroy($ids);
            }
            $this->afterChange('destroy', $ids);

            return $this->success('操作成功');
        } else {
            return $this->fail('参数错误，请检查');
        }
    }




    /**
     * 成员管理
     *
     */
    public function family(Request $request): Response
    {


        $where = $request->more([
            ['id', ''],
            ['pid', ''],
            ['name', ''],
            ['mobile', ''],
            ['idcard', ''],
        ]);
        $member_id = $request->input('pid', '');
        $where['pid'] = $member_id;
        $query = $this->logic->search($where);

        $data = $this->logic->getList($query);
        return $this->success($data);
    }


    /**
     * 添加成员
     * @param Request $request
     * @return Response
     */
    public function savefamily(Request $request) : Response
    {
        $data = $request->post();
//        return $this->fail('操作失败'.json_encode($data));
        if ($this->validate) {
            if (!$this->validate->scene('save')->check($data)) {
                return $this->fail($this->validate->getError());
            }
        }

        $member_id = $request->input('pid', '');

        $member = $this->logic->read($member_id);

        $data['pid'] = $member['id'];
        $data['build_id'] = $member['build_id'];
        $data['buildname'] = $member['buildname'];
        $data['buildtype'] = $member['buildtype'];
        $data['buildarea'] = $member['buildarea'];

        $member->setInc('mnums');

        $key = $this->logic->add($data);
        if ($key > 0) {
            $this->afterChange('save', $key);
            return $this->success('操作成功');
        } else {
            return $this->fail('操作失败');
        }
    }

    /**
     * 修改成員
     * @param $id
     * @param Request $request
     * @return Response
     */
    public function updatefamily(Request $request, $id) : Response
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

        $result = $this->logic->edit($id, $data);
        if ($result) {
            $this->afterChange('update', $result);
            return $this->success('操作成功');
        } else {
            return $this->fail('操作失败');
        }
    }


    /**
     * 解绑成员
     */
    public function unbind(Request $request,$ids = null)
    {

        $row = $this->logic->read($ids);
        if (empty($id)) {
            return $this->fail('参数错误，请检查');
        }
        if ($row->mnums > 0) {
            $this->fail('请先在成员管理中删除成员在解绑！');
        }


        $userBuild = UserBuildModel::get(['user_id' => $row['user_id'], 'member_id' => $row['id']]);
        if($userBuild){
            $userBuild->delete();
        }
        $row->user_id = 0;
        $row->status = 0;
        $row->save();

        return $this->success('操作成功');

    }



    /**
     * 导入数据
     * @param Request $request
     * @return Response
     */
    public function import(Request $request) : Response
    {
        $file = current($request->file());
        if (!$file || !$file->isValid()) {
            return $this->fail('未找到上传文件');
        }
        $type = $request->post('type');
        $this->logic->import($type,$file);
        return $this->success('导入成功');
    }




}
