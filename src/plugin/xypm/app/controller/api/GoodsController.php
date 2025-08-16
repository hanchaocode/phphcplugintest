<?php

namespace plugin\xypm\app\controller\api;
use plugin\xypm\app\model\api\goods\SkuPrice;
use plugin\xypm\basic\FrontController;
use plugin\xypm\app\model\api\Category as CategoryModel;
use plugin\xypm\app\model\api\Goods as GoodsModel;
use plugin\xypm\app\model\api\user\View as ViewModel;
use support\Request;
use support\Response;

/**
 * 商品接口
 */
class GoodsController extends FrontController
{
    protected $noNeedLogin = ['lists','detail','categories'];
    protected $noNeedRight = ['*'];

	/**
	 * 商品列表
	 */
	public function lists(Request $request): Response
    {


        if($request->isPost()){
//            $params = $request->post();
            $params[] =['page',$request->post('page',1)];
        } else{
            $params = $request->get();
        }


        $data = GoodsModel::getList($params);
        return $this->success('商品列表', $data);
    }




    /**
     * 商品详情
     * @param Request $request
     * @param $id
     */
    public function detail(Request $request, $id): Response
    {
        $id = $request->get('id');
        $detail = GoodsModel::getDetail($id);

        if(!$detail){
            $this->fail('商品不存在！');
        }

        // 记录足记
        ViewModel::addView($detail,'goods');

        $sku_price = $detail['sku_price'];

        $detail = json_decode(json_encode($detail), true);
        $detail['sku_price'] = $sku_price;

         $detail['stock'] = SkuPrice::where(['goods_id'=>$id,'status'=>'up'])->sum('stock');


        return $this->success('商品详情', $detail);
    }

    /**
     * 商品分类
     */
    public function categories(Request $request){
        $params = $request->get();
        $data = CategoryModel::getAllCategory($params);
        return $this->success('商品分类', $data);
    }
	
}