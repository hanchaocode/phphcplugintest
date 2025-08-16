<?php

namespace plugin\xypm\app\controller\admin;

use app\admin\model\xypm\user\Money as UserMoneyModel;

use plugin\saiadmin\basic\BaseController;
use plugin\xypm\app\logic\UserLogic;
use plugin\xypm\app\model\admin\user\Money;
use support\Request;
use support\Response;

use think\exception\PDOException;





/**
 * 会员管理
 *
 * @icon fa fa-user
 */
class UserController extends BaseController
{

    public function __construct()
    {
        $this->logic = new UserLogic();

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
            ['nickname', ''],
            ['mobile', ''],
            ['createtime', ''],
            ['third_platform', ''],
            ['status', ''],

        ]);

        $query = $this->logic->search($where);

        $data = $this->logic->getList($query);


//        foreach ($data as &$v) {
            //
//            $v->avatar = $v->avatar ? cdnurl($v->avatar, true) : letter_avatar($v->nickname);
//            $v->hidden(['password', 'salt']);
//            continue;
//        }
        return $this->success($data);
    }

    


    /**
     * 调整余额
     * @param Request $request
     * @return Response
     */
    public function recharge(Request $request): Response
    {


        $data = $request->post();
        $id = $data['id'];
        $row = $this->logic->search(['id'=>$id])->find();
        if (!$row) {
            $this->fail(trans('记录不存在'));
        }

        


        $before = $row['money'];
        $after = $row['money']+$data['num'];

        $row->save(['money'=>$after]);
        $result = false;
        $result = Money::create([
            'user_id' => $row['id'],
            'type'    => 'sys',
            'money'    => $data['num'],
            'before'    => $before,
            'after'    => $after,
            'remark'   => $data['remark']
        ]);
        if(!$result){
            return $this->fail('操作失败');
        }
        return $this->success('调整成功');
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
    //添加
    public function save(Request $request):Response
    {
        return    $this->success();
    }
    //编辑
    public function update(Request $request,$id):Response
    {
        return    $this->success();
    }
    //删除
    public function destroy($ids = null):Response {
        return    $this->success();
    }

}
