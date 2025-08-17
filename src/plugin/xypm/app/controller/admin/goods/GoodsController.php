<?php

namespace plugin\xypm\app\controller\admin\goods;



use Exception;

use plugin\saiadmin\basic\BaseController;
use plugin\xypm\app\logic\goods\GoodsLogic;

use plugin\xypm\app\model\admin\goods\Sku;
use plugin\xypm\app\model\admin\goods\SkuPrice;
use support\Request;
use support\Response;
use think\facade\Db;
use think\exception\DbException;
use think\exception\PDOException;
use think\exception\ValidateException;

/**
 * 商品管理
 *
 * @icon fa fa-circle-o
 */
class GoodsController extends BaseController
{




    public function __construct()
    {
        $this->logic = new GoodsLogic();


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
            ['title', ''],
            ['price', ''],
            ['line_price', ''],
            ['category_ids', ''],
            ['views', ''],
            ['sales', ''],
            ['status', ''],
            ['createtime', ''],

        ]);

        $query = $this->logic->search($where);

        $data = $this->logic->getList($query);
        return $this->success($data);
    }
    /**
     * 添加
     *
     */
    public function save(Request $request): Response
    {


        $data = $request->post();
        $sku = $request->post("sku_data");



        if (!$data['is_sku']) {
            if (!preg_match('/^[0-9]+(.[0-9]{1,8})?$/', $data['price'])) {
                return $this->fail("请填写正确的价格");
            }
        }




        $goods_id = $this->logic->add($data);

        if ($goods_id) {
            if ($data['is_sku']) {
                $this->editMultSku($goods_id, $sku,'add');
            } else {
                $this->editSimSku($goods_id, $data,'add');
            }

        }



        return $this->success();
    }

    /**
     * 添加编辑多规格
     */
    protected function editMultSku($goods_id, $sku, $type = 'add') {
        $skuData = json_decode($sku, true);
        $skuList = json_decode($skuData['listData'],true);
        $skuPrice = json_decode($skuData['priceData'],true);

        
        if (count($skuList) < 1) {
            throw new Exception('请填写规格列表');
        }
//        foreach ($skuList as $key => $sku) {
//            if (count($sku['values']) <= 0) {
//                throw new Exception('主规格至少要有一个子规格');
//            }
//
//            // 验证子规格不能为空
//            foreach ($sku['values'] as $k => $child) {
//                if (!isset($child['name']) || empty(trim($child['name']))) {
//                    throw new Exception('子规格不能为空');
//                }
//            }
//        }

        if (count($skuPrice) < 1) {
            throw new Exception('请填写规格价格');
        }


        foreach ($skuPrice as &$price) {
            if (empty($price['price'])) {
                throw new Exception('请填写规格价格');
            }
            if ($price['stock'] === '') {
                throw new Exception('请填写规格库存');
            }
        }

//        $allChildrenSku = $this->saveSkuList($goods_id, $skuList, $type);
        $allChildrenSku = $this->saveSkuList($goods_id, $skuList, $type);

        if ($type == 'add') {
            foreach ($skuPrice as $s3 => &$k3) {
//                throw new Exception('请填写规格库存'.json_encode($allChildrenSku));

                $k3['goods_sku_ids'] = implode('',$allChildrenSku);
                $k3['goods_id'] = $goods_id;
                $k3['goods_sku_text'] = implode(',', $k3['goods_sku_text']);
                $k3['createtime'] = time();
                $k3['updatetime'] = time();

                unset($k3['id']);
                unset($k3['temp_id']);      

            }
            (new SkuPrice)->saveAll($skuPrice);

        } else {
            $oldSkuPriceIds = array_column($skuPrice, 'id');

            SkuPrice::where('goods_id', $goods_id)->where('id', 'not in', $oldSkuPriceIds)->delete();
            foreach ($skuPrice as $s3 => &$k3) {
//                $data['goods_sku_ids'] = $this->checkRealIds($k3['temp_id'], $allChildrenSku);
                $data['goods_sku_ids'] = implode(',',$allChildrenSku);
                $data['goods_id'] = $goods_id;
                $data['goods_sku_text'] = implode(',', $k3['goods_sku_text']);
                $data['weigh'] = $k3['weigh'];
                $data['image'] = $k3['image'];
                $data['stock'] = $k3['stock'];
                $data['sn'] = $k3['sn'];
                $data['weight'] = $k3['weight'];
                $data['price'] = $k3['price'];
                $data['status'] = $k3['status'];
                $data['createtime'] = time();
                $data['updatetime'] = time();
                if ($k3['id']) {
                    $goodsSkuPrice = SkuPrice::find($k3['id']);
                } else {
                    $goodsSkuPrice = new SkuPrice();
                }

                if ($goodsSkuPrice) {
                    $goodsSkuPrice->save($data);

                }
            }
        }

        return true;
    }

    private function checkRealIds($newGoodsSkuIds, $allChildrenSku)
    {
        $newIdsArray = [];
        foreach ($newGoodsSkuIds as $id) {
            $newIdsArray[] = $allChildrenSku[$id];
        }
        return implode(',', $newIdsArray);

    }

    private function saveSkuList($goods_id, $skuList, $type = 'add') {
        $allChildrenSku = [];

        if ($type == 'edit') {
            $oldSkuIds = [];
            foreach ($skuList as $key => $sku) {
                $oldSkuIds[] = $sku['id'];

                $childSkuIds = [];
                if ($sku['values']) {
                    $childSkuIds = array_column($sku['values'], 'id');
                }

                $oldSkuIds = array_merge($oldSkuIds, $childSkuIds);
                $oldSkuIds = array_unique($oldSkuIds);
            }

            Sku::where('goods_id', $goods_id)->where('id', 'not in', $oldSkuIds)->delete();
        }
        $allSkuIds = [];
        foreach ($skuList as $s1 => &$k1) {
            if (isset($k1['id'])) {
                Sku::where('id', $k1['id'])->update([
                    'name' => $k1['name'],
                ]);

                $skuId[$s1] = $k1['id'];
                if($k1['pid']) {
                    $allSkuIds[]= $k1['id'];
                }

            } else {
                $skuId[$s1] = Sku::insertGetId([
                    'name' => $k1['name'],
                    'pid' => 0,
                    'goods_id' =>$goods_id
                ]);
            }
            $k1['id'] = $skuId[$s1];
            foreach ($k1['values'] as $s2 => &$k2) {
                if (isset($k2['id'])) {
                    Sku::where('id', $k2['id'])->update([
                        'name' => $k2['name'],
                    ]);

                    $skuChildrenId[$s1][$s2] = $k2['id'];
                    $allSkuIds[]= $k2['id'];
                } else {
                    $skuChildrenId[$s1][$s2] = Sku::insertGetId([
                        'name' => $k2['name'],
                        'pid' => $k1['id'],
                        'goods_id' =>$goods_id
                    ]);
                    if($k1['id']){
                        $allSkuIds[]=  $skuChildrenId[$s1][$s2];
                    }

                }
                if(!isset($k2['temp_id'])) {
                    $k2['temp_id'] = '';
                }
                $allChildrenSku[$k2['temp_id']] = $skuChildrenId[$s1][$s2];
                $k2['id'] = $skuChildrenId[$s1][$s2];
                $k2['pid'] = $k1['id'];
            }
        }

        return $allSkuIds;
    }

    /**
     * 读取
     * @param Request $request
     * @return Response
     */
    public function read(Request $request,$id = null): Response
    {


        $row = $this->logic->read($id)->toArray();
        $skuList = Sku::where(['pid' => 0, 'goods_id' => $id])->select();
        if ($skuList) {
            foreach ($skuList as &$s) {
                $s->values = Sku::where(['pid' => $s->id, 'goods_id' => $id])->select();

            }
        }



        $skuPrice = SkuPrice::where(['goods_id' => $id])->select()->toArray();

        $row['is_sku'] = intval($row['is_sku']);
        $row['stock'] = $skuPrice[0]['stock'];
//        $row['sn'] = $skuPrice[0]['sn'];

        $row['sku_data'] = ['listData'=>$skuList->toArray(),'priceData'=>$skuPrice];

        return $this->success($row,'');

    }

    /**
     * 编辑
     *
     * @param $ids
     * @return string
     * @throws DbException
     * @throws \think\Exception
     */
    public function update(Request $request,$id = null): Response
    {




        $params = $request->post();
        $row = $this->logic->read($id);




        $sku = $request->post("sku_data");


        $goods_id =  $request->post('id',$id);

        $row->save($params);

        if ($params['is_sku']) {
            $result = $this->editMultSku($goods_id, $sku, 'edit');
        } else {
            $result = $this->editSimSku($goods_id, $params, 'edit');
        }

        if (false === $result) {
            return $this->fail(trans('No rows were updated'));
        }
        return $this->success();
    }

    /**
     * 添加编辑单规格
     */
    protected function editSimSku($goods_id, $params, $type = 'add') {

        $data = [
            "goods_id" =>$goods_id,
            "stock" => $params['stock'] ?? 0,
            "sn" => $params['sn'] ?? "",
            "price" => $params['price'] ?? 0,
            "status" => 'up'
        ];
        if ($type == 'add') {
            $goodsSkuPrice = new SkuPrice();
        } else {
            $goodsSkuPrice = SkuPrice::where('goods_id', $goods_id)->order('id', 'asc')->find();
        }
        return $goodsSkuPrice->save($data);
    }




}