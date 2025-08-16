<?php

namespace plugin\xypm\app\controller\admin;

use plugin\xypm\app\model\admin\activity\Activity as ActivityModel;
use plugin\xypm\app\model\admin\goods\Category as CategoryModel;
use plugin\xypm\app\model\admin\Goods as GoodsModel;
use plugin\xypm\app\model\admin\Link;
use plugin\xypm\app\model\admin\Notice as NoticeModel;
use plugin\saiadmin\basic\BaseController;
use plugin\xypm\app\logic\LinkLogic;
use support\Request;
use support\Response;

/**
 * 链接管理
 *
 * @icon fa fa-circle-o
 */
class LinkController extends BaseController
{
    public function __construct()
    {
        $this->logic = new LinkLogic();

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
            ['url', ''],

        ]);

        $query = $this->logic->search($where);

        $data = $this->logic->getList($query);
        return $this->success($data);
    }
    /**
     * 生成链接
     */
    public function build(){
        //基础链接
        $basicLink = [
            ['name'=>'首页','url'=>'/pages/index','type'=>'basic'],
            ['name'=>'商城','url'=>'/pages/shop','type'=>'basic'],
            ['name'=>'我的','url'=>'/pages/user','type'=>'basic'],
            ['name'=>'公告列表','url'=>'/pages/service/notice/list','type'=>'basic'],
            ['name'=>'投诉建议','url'=>'/pages/service/suggest/add','type'=>'basic'],
            ['name'=>'维修申报','url'=>'/pages/service/repair/add','type'=>'basic'],
            ['name'=>'活动列表','url'=>'/pages/service/activity/list','type'=>'basic'],
            ['name'=>'商品分类','url'=>'/pages/shop/category','type'=>'basic'],
            ['name'=>'联系客服','url'=>'contact','type'=>'basic'],
            ['name'=>'分享好友','url'=>'share','type'=>'basic'],
            
        ];

        //会员中心
        $userLink = [
            ['name'=>'我的房产','url'=>'/pages/user/build/list','type'=>'user'],
            ['name'=>'房产账单','url'=>'/pages/user/bill/list','type'=>'user'],
            ['name'=>'我的报修','url'=>'/pages/user/service/repair','type'=>'user'],
            ['name'=>'我的建议','url'=>'/pages/user/service/suggest','type'=>'user'],
            ['name'=>'我的收藏','url'=>'/pages/user/favorite','type'=>'user'],
            ['name'=>'个人资料','url'=>'/pages/user/info','type'=>'user'],
            ['name'=>'我的活动','url'=>'/pages/user/service/activity','type'=>'user'],
            ['name'=>'商城订单-全部','url'=>'/pages/user/shop/order','type'=>'user'],
            ['name'=>'商城订单-待付款','url'=>'/pages/user/shop/order?status=1','type'=>'user'],
            ['name'=>'商城订单-待配送','url'=>'/pages/user/shop/order?status=2','type'=>'user'],
            ['name'=>'商城订单-已配送','url'=>'/pages/user/shop/order?status=3','type'=>'user'],
            ['name'=>'商城订单-已完成','url'=>'/pages/user/shop/order?status=4','type'=>'user'],
            ['name'=>'商城订单-已取消','url'=>'/pages/user/shop/order?status=5','type'=>'user'],
        ];
        
        //公告链接
        $noticeLink = [];
        $notice = NoticeModel::where(['status'=>'normal'])->select();
        foreach($notice as $s){
            $noticeLink[] = ['name'=>$s['title'],'url'=>'/pages/service/notice/detail?id='.$s['id'],'type'=>'notice'];
        }

        //活动链接
        $activityLink = [];
        $activity = ActivityModel::where(['status'=>'normal'])->select();
        foreach($activity as $c){
            $activityLink[] = ['name'=>$c['title'],'url'=>'/pages/course/detail?id='.$c['id'],'type'=>'activity'];
        }
        
        //商品链接
        $goodsLink = [
            ['name'=>'商品列表','url'=>'/pages/shop/goods/list','type'=>'goods'],
        ];
        $goodsCategory = CategoryModel::where(['type'=>'goods','status'=>'normal'])->select();
        foreach($goodsCategory as $cc){
            $goodsLink[] = ['name'=>'商品分类-'.$cc['name'],'url'=>'/pages/shop/goods/list?cid='.$cc['id'].'&name='.$cc['name'],'type'=>'goods'];
        }
        $goods = GoodsModel::where(['status'=>'up'])->select();
        foreach($goods as $c){
            $goodsLink[] = ['name'=>'商品详情-'.$c['title'],'url'=>'/pages/shop/goods/detail?id='.$c['id'],'type'=>'goods'];
        }

        $allLink = array_merge($basicLink,$userLink,$noticeLink,$activityLink,$goodsLink);
        foreach($allLink as $l){
            $link = $this->logic->where($l)->find();
            if(!$link){
                Link::create($l);
            }
        }
        return $this->success('生成成功');
    }


}