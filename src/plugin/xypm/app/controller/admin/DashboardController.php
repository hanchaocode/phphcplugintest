<?php

namespace plugin\xypm\app\controller\admin;

use plugin\xypm\app\model\admin\bill\Order;
use plugin\xypm\app\model\admin\Build;
use plugin\xypm\app\model\admin\build\Garage;
use plugin\xypm\app\model\admin\build\House;
use plugin\xypm\app\model\admin\build\Parking;
use plugin\xypm\app\model\admin\build\Shop;
use plugin\xypm\app\model\admin\goods\Order as GoodsOrder;
use plugin\xypm\app\model\admin\Member;
use plugin\xypm\app\model\admin\recharge\Order as RechargeOrder;
use plugin\saiadmin\basic\BaseController;
use support\Response;

use plugin\xypm\utils\Tools;


/**
 * 控制台
 */
class DashboardController extends BaseController
{

    /**
     * 查看
     */
    public function index(): Response
    {
        $data = [
            'totalBuild'       => Build::count(), //楼宇
            'totalHouse'       => House::count(), //房屋
            'totalShop'        => Shop::count(), //商铺
            'totalParking'     => Parking::count(), //车位
            'totalGarage'      => Garage::count(), //储物间
            'totalm1'          => Member::where('identity', 1)->count(), //业主
            'totalm2'          => Member::where('identity', 2)->count(), //成员
            'totalm3'          => Member::where('identity', 3)->count(), //租户
        ];

        //今日起始时间戳
        $beginToday = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
        $endToday = mktime(0, 0, 0, date("m"), date("d") + 1, date("Y")) - 1;
        //本月起始时间戳
        $beginThismonth = mktime(0, 0, 0, date("m"), 1, date("Y"));
        $endThismonth = mktime(23, 59, 59, date("m"), date("t"), date("Y"));

        $data['orderTotalMoney'] = Order::where('status', 1)->sum('total_fee');
        $data['orderTotalNum'] = Order::where('status', 1)->count();
        $data['orderTodayMoney'] = Order::where('status', 1)
            ->whereBetween('createtime', [$beginToday, $endToday])
            ->sum('total_fee');
        $data['orderTodayNum'] = Order::where('status', 1)
            ->whereBetween('createtime', [$beginToday, $endToday])
            ->count();
        $data['orderMonthMoney'] = Order::where('status', 1)
            ->whereBetween('createtime', [$beginThismonth, $endThismonth])
            ->sum('total_fee');
        $data['orderMonthNum'] = Order::where('status', 1)
            ->whereBetween('createtime', [$beginThismonth, $endThismonth])
            ->count();

        $data['rechargeTotalMoney'] = RechargeOrder::where('status', 1)->sum('total_fee');
        $data['rechargeTotalNum'] = RechargeOrder::where('status', 1)->count();
        $data['rechargeTodayMoney'] = RechargeOrder::where('status', 1)
            ->whereBetween('createtime', [$beginToday, $endToday])
            ->sum('total_fee');
        $data['rechargeTodayNum'] = RechargeOrder::where('status', 1)
            ->whereBetween('createtime', [$beginToday, $endToday])
            ->count();
        $data['rechargeMonthMoney'] = RechargeOrder::where('status', 1)
            ->whereBetween('createtime', [$beginThismonth, $endThismonth])
            ->sum('total_fee');
        $data['rechargeMonthNum'] = RechargeOrder::where('status', 1)
            ->whereBetween('createtime', [$beginThismonth, $endThismonth])
            ->count();

        $data['goodsTotalMoney'] = GoodsOrder::where('status', '>', 0)->sum('total_fee');
        $data['goodsTotalNum'] = GoodsOrder::where('status', '>', 0)->count();
        $data['goodsTodayMoney'] = GoodsOrder::where('status', '>', 0)
            ->whereBetween('createtime', [$beginToday, $endToday])
            ->sum('total_fee');
        $data['goodsTodayNum'] = GoodsOrder::where('status', '>', 0)
            ->whereBetween('createtime', [$beginToday, $endToday])
            ->count();
        $data['goodsMonthMoney'] = GoodsOrder::where('status', '>', 0)
            ->whereBetween('createtime', [$beginThismonth, $endThismonth])
            ->sum('total_fee');
        $data['goodsMonthNum'] = GoodsOrder::where('status', '>', 0)
            ->whereBetween('createtime', [$beginThismonth, $endThismonth])
            ->count();

        $weekDays = Tools::xypmGetWeeks();

        $orderDayTotalMoney = [];
        $orderDayTotalNum = [];
        $rechargeDayTotalMoney = [];
        $rechargeDayTotalNum = [];
        $goodsDayTotalMoney = [];
        $goodsDayTotalNum = [];
        foreach($weekDays as $wd){
            $orderDayTotalMoney[] = Order::whereBetween('createtime', [strtotime(date('Y').'-'.$wd), strtotime(date('Y').'-'.$wd)+86400])->sum('total_fee');
            $orderDayTotalNum[] = Order::whereBetween('createtime', [strtotime(date('Y').'-'.$wd), strtotime(date('Y').'-'.$wd)+86400])->count();

            $rechargeDayTotalMoney[] = RechargeOrder::where('status', 1)
                ->whereBetween('createtime', [strtotime(date('Y').'-'.$wd), strtotime(date('Y').'-'.$wd)+86400])
                ->sum('total_fee');
            $rechargeDayTotalNum[] = RechargeOrder::where('status', 1)
                ->whereBetween('createtime', [strtotime(date('Y').'-'.$wd), strtotime(date('Y').'-'.$wd)+86400])
                ->count();

            $goodsDayTotalMoney[] = GoodsOrder::whereBetween('createtime', [strtotime(date('Y').'-'.$wd), strtotime(date('Y').'-'.$wd)+86400])->sum('total_fee');
            $goodsDayTotalNum[] = GoodsOrder::whereBetween('createtime', [strtotime(date('Y').'-'.$wd), strtotime(date('Y').'-'.$wd)+86400])->count();
        }

        $data['weekDays'] = $weekDays;
        $data['orderDayTotalMoney'] = $orderDayTotalMoney;
        $data['orderDayTotalNum'] = $orderDayTotalNum;
        $data['rechargeDayTotalMoney'] = $rechargeDayTotalMoney;
        $data['rechargeDayTotalNum'] = $rechargeDayTotalNum;
        $data['goodsDayTotalMoney'] = $goodsDayTotalMoney;
        $data['goodsDayTotalNum'] = $goodsDayTotalNum;

        // Return the data as JSON
        return json(['data'=>$data,'code'=>200]);
    }

}
