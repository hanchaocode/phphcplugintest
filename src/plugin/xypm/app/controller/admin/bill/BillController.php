<?php

namespace plugin\xypm\app\controller\admin\bill;

use plugin\xypm\app\model\admin\Bill;
use plugin\xypm\app\model\admin\build\Garage;
use plugin\xypm\app\model\admin\build\House;
use plugin\xypm\app\model\admin\build\Parking;
use plugin\xypm\app\model\admin\build\Shop;
use plugin\xypm\app\model\admin\Config;

use plugin\saiadmin\basic\BaseController;
use plugin\xypm\app\logic\bill\BillLogic;

use support\Request;
use support\Response;

use think\exception\DbException;
use think\exception\PDOException;



/**
 * 账单管理
 *
 * @icon fa fa-circle-o
 */
class BillController extends BaseController
{




    public function __construct()
    {
        $this->logic = new BillLogic();

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
            ['billdate', ''],
            ['buildtype', ''],
            ['createtime', ''],
            ['status', ''],
        ]);
        $query = $this->logic->search($where);
        $data = $this->logic->getList($query);
        return $this->success($data);
    }


    /**
     * 生成账单
     */
    public function build(Request $request): Response
    {


        $data = $request->post();
        $config = Config::getValueByName('fees');
        if($config == null){
            return $this->fail('请先在配置中心-》收费配置中配置好物业费收费标准在生成账单。');
        }
        $type=$data['buildtype'];
        $fee = 0;
        if($type != 'house'){
            $fee = floatval($config[$type]);
            if($fee <= 0){
                return $this->fail('请配置正确的物业费收费标准在生成账单。');
            }
        }

        $endDate = $data['enddate'];
        $endY = explode('-',$endDate)[0];
        $endM = explode('-',$endDate)[1];

        $data = [];
        if($type == 'house'){
            $houseList = House::with(['build'])->where(['status'=>1])->select();


            foreach($houseList as $house){

                if(empty($house['build']['status'])){continue;}

                if($house['build']['status'] == 'high'){
                    $fee = floatval($config['house']);
                }
                if($house['build']['status'] == 'multilayer'){
                    $fee = floatval($config['house1']);
                }
                if($house['build']['status'] == 'villa'){
                    $fee = floatval($config['house2']);
                }

                if($fee <= 0){
                    return $this->fail('请配置正确的物业费收费标准在生成账单。');
                }

                $startDate = $house['billdate']; //开始收费日期
                if(empty($startDate)){
                    return $this->fail(Bill::getBuildtypeList()[$type].'中收费日期不能为空，请去完善信息');
                }


                $bill = Bill::where(['build_id'=>$house['id'],'buildtype'=>'house'])->order('id desc')->find();
                $startD = explode('-',$startDate)[2];
                if(!empty($bill)){
                    $startDate = date("Y-m", strtotime("+1 months", strtotime($bill['cycle'])));
                }


//                return $this->fail("startdate:{$startDate},endDate:{$endDate}");
                if(empty($startDate) || strtotime($startDate) > strtotime($endDate)){
                    continue;
                }

                $startY = explode('-',$startDate)[0];
                $startM = explode('-',$startDate)[1];
               
                $k = 0;
                for($i = intval($startM);$i<= intval($endM)+($endY-$startY)*12;$i++){
                    $k++;
                    if($k > 12){
                        break;
                    }
                    $cycle = (intval($startY)+ floor($i/12-0.01)).'-' . (($i > 12)?(($i-12)>9?$i-12:'0'.($i-12)):(($i>9)?$i:'0'.$i));

                    $t = date('t',strtotime($cycle));
                    $billdate = $cycle.'-01~'.$cycle.'-'.$t;
                    $money = $fee * floatval($house['buildarea']);

                    if(empty($bill) && $i == intval($startM)){
                        $billdate = $cycle.'-'.$startD.'~'.$cycle.'-'.$t;

                        if($startD != '01'){
                            $money = $money * ((intval($t)-intval($startD)) / intval($t));
                        }
                    }

                    if($money > 0){
                        $data[] = [
                            'build_id' => $house['id'],
                            'buildname' =>  $house['build']['name'].$house['unit'].'单元'.$house['number'],
                            'buildtype' => 'house',
                            'cycle' => $cycle,
                            'billdate' => $billdate,
                            'money' => floor($money*100)/100
                        ];
                    }
                    
                }
    
            }
        }

        if($type == 'shop'){
            $shopList = Shop::with(['area'])->where(['shop.status'=>1])->select();
            foreach($shopList as $shop){
                $startDate = $shop['billdate']; //开始收费日期
                if(empty($startDate)){
                    return $this->fail(Bill::getBuildtypeList()[$type].'中收费日期不能为空，请去完善信息');
                }
                $bill = Bill::where(['build_id'=>$shop['id'],'buildtype'=>'shop'])->order('id desc')->find();
                $startD = explode('-',$startDate)[2];
                if(!empty($bill)){
                    $startDate = date("Y-m", strtotime("+1 months", strtotime($bill['cycle'])));
                }

                if(empty($startDate) || strtotime($startDate) > strtotime($endDate)){
                    continue;
                }
    
                $startY = explode('-',$startDate)[0];
                $startM = explode('-',$startDate)[1];
               
                $k = 0;
                for($i = intval($startM);$i<= intval($endM)+($endY-$startY)*12;$i++){
                    $k++;
                    if($k > 12){
                        break;
                    }
                    $cycle = (intval($startY)+ floor($i/12-0.01)).'-' . (($i > 12)?(($i-12)>9?$i-12:'0'.($i-12)):(($i>9)?$i:'0'.$i));

                    $t = date('t',strtotime($cycle));
                    $billdate = $cycle.'-01~'.$cycle.'-'.$t;
                    $money = $fee * floatval($shop['buildarea']);

                    if(empty($bill) && $i == intval($startM)){
                        $billdate = $cycle.'-'.$startD.'~'.$cycle.'-'.$t;

                        if($startD != '01'){
                            $money = $money * ((intval($t)-intval($startD)) / intval($t));
                        }
                    }

                    $data[] = [
                        'build_id' => $shop['id'],
                        'buildname' =>  $shop['area']['name'].'-'.$shop['number'],
                        'buildtype' => 'shop',
                        'cycle' => $cycle,
                        'billdate' => $billdate,
                        'money' => $money
                    ];
                    
                }
    
            }
        }

        if($type == 'parking'){
            $parkingList = Parking::with(['area'])->where(['parking.status'=>1])->select();
            foreach($parkingList as $parking){
                $startDate = $parking['billdate']; //开始收费日期
                if(empty($startDate)){
                    return $this->fail(Bill::getBuildtypeList()[$type].'中收费日期不能为空，请去完善信息');
                }
                $bill = Bill::where(['build_id'=>$parking['id'],'buildtype'=>'parking'])->order('id desc')->find();
                $startD = explode('-',$startDate)[2];
                if(!empty($bill)){
                    $startDate = date("Y-m", strtotime("+1 months", strtotime($bill['cycle'])));
                }

                if(empty($startDate) || strtotime($startDate) > strtotime($endDate)){
                    continue;
                }
    
                $startY = explode('-',$startDate)[0];
                $startM = explode('-',$startDate)[1];
               
                $k = 0;
                for($i = intval($startM);$i<= intval($endM)+($endY-$startY)*12;$i++){
                    $k++;
                    if($k > 12){
                        break;
                    }
                    $cycle = (intval($startY)+ floor($i/12-0.01)).'-' . (($i > 12)?(($i-12)>9?$i-12:'0'.($i-12)):(($i>9)?$i:'0'.$i));

                    $t = date('t',strtotime($cycle));
                    $billdate = $cycle.'-01~'.$cycle.'-'.$t;
                    $money = $fee;

                    if(empty($bill) && $i == intval($startM)){
                        $billdate = $cycle.'-'.$startD.'~'.$cycle.'-'.$t;

                        if($startD != '01'){
                            $money = $money * ((intval($t)-intval($startD)) / intval($t));
                        }
                    }

                    $data[] = [
                        'build_id' => $parking['id'],
                        'buildname' =>  $parking['area']['name'].'-'.$parking['number'],
                        'buildtype' => 'parking',
                        'cycle' => $cycle,
                        'billdate' => $billdate,
                        'money' => $money
                    ];
                    
                }
    
            }
        }

        if($type == 'garage'){
            $garageList = Garage::where(['status'=>1])->select();
            foreach($garageList as $garage){
                $startDate = $garage['billdate']; //开始收费日期
                if(empty($startDate)){
                    return $this->fail(Bill::getBuildtypeList()[$type].'中收费日期不能为空，请去完善信息');
                }
                $bill = Bill::where(['build_id'=>$garage['id'],'buildtype'=>'garage'])->order('id desc')->find();
                $startD = explode('-',$startDate)[2];
                if(!empty($bill)){
                    $startDate = date("Y-m", strtotime("+1 months", strtotime($bill['cycle'])));
                }

                if(empty($startDate) || strtotime($startDate) > strtotime($endDate)){
                    continue;
                }
    
                $startY = explode('-',$startDate)[0];
                $startM = explode('-',$startDate)[1];
               
                $k = 0;
                for($i = intval($startM);$i<= intval($endM)+($endY-$startY)*12;$i++){
                    $k++;
                    if($k > 12){
                        break;
                    }
                    $cycle = (intval($startY)+ floor($i/12-0.01)).'-' . (($i > 12)?(($i-12)>9?$i-12:'0'.($i-12)):(($i>9)?$i:'0'.$i));

                    $t = date('t',strtotime($cycle));
                    $billdate = $cycle.'-01~'.$cycle.'-'.$t;
                    $money = $fee * floatval($garage['buildarea']);

                    if(empty($bill) && $i == intval($startM)){
                        $billdate = $cycle.'-'.$startD.'~'.$cycle.'-'.$t;

                        if($startD != '01'){
                            $money = $money * ((intval($t)-intval($startD)) / intval($t));
                        }
                    }

                    $data[] = [
                        'build_id' => $garage['id'],
                        'buildname' =>  $garage['number'],
                        'buildtype' => 'garage',
                        'cycle' => $cycle,
                        'billdate' => $billdate,
                        'money' => $money
                    ];
                    
                }
    
            }
        }




        if($data){
            $result = $this->logic->saveAll($data);
        }




        return $this->success('操作成功');
    }

    //编辑
    public function edit($ids = null) {
        return;
    }


}
