<?php

namespace plugin\xypm\app\controller\admin\goods;

use Exception;
use plugin\saiadmin\basic\BaseController;

use plugin\xypm\app\logic\goods\OrderLogic;
use plugin\xypm\app\model\admin\goods\Order;
use support\Request;
use support\Response;
use think\facade\Db;
use think\exception\DbException;
use think\exception\PDOException;
use think\exception\ValidateException;


/**
 * 订单管理
 *
 * @icon fa fa-circle-o
 */
class OrderController extends BaseController
{




    public function __construct()
    {
        $this->logic = new OrderLogic();

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
            ['order_sn', ''],
//            ['user.nickname', ''],
            ['createtime', ''],
            ['paytime', ''],
            ['status', ''],

        ]);


        $query = $this->logic->search($where);

        $data = $this->logic->getList($query);
        return $this->success($data);
    }



    /**
     * 详情
     *
     * @param $id
     */
    public function read(Request $request,$id = null):Response
    {

        $row = Order::with(['item','user'])->where(['id'=>$id])->find()->toArray();




        return $this->success($row,'');


    }

    /**
     * 确认收货
     */
    public function take_delivery($id = null){
        $row = $this->logic->read($id);
        if (!$row) {
            return $this->fail(trans('No Results were found'));
        }

        if($row['status'] != 2){
            return $this->fail(trans('订单状态错误', []));
        }

        $result = false;
        $result = $row->save(['status'=>3,'take_delivery_time'=>time()]);
        return $this->success('操作成功');
    }

    

    /**
     * 配送
     *
     * @param $id
     */
    public function delivery($id = null)
    {
        $row = $this->logic->read($id);


        if($row['status'] != 1){
            return $this->fail('订单状态错误');
        }



        $result =  $this->logic->edit($row['id'],['status'=>2,'delivery_time'=>time()]);
        return $this->success('操作成功');
    }




}
