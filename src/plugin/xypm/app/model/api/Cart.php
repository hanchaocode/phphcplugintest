<?php

namespace plugin\xypm\app\model\api;

use plugin\saiadmin\basic\BaseNormalModel;

/**
 * 购物车模型
 */
class Cart extends BaseNormalModel
{

    // 表名
    protected $name = 'xypm_cart';
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = 'deletetime';

    protected $hidden = ['createtime', 'updatetime', 'deletetime'];

    // 追加属性
    protected $append = [
    ];

    public static function getList()
    {
        $user = User::info();
        // 已删除的商品从购物车中删除
        self::whereNotExists(function ($query) {
            $goodsTableName = (new Goods())->getTable();
            $tableName = (new self())->getTable();
            $query = $query->table($goodsTableName)->where($goodsTableName . '.id=' . $tableName . '.goods_id');
            return $query;
        })->where([
            'user_id' => $user->id
        ])->delete();

        $cartData = self::with(['goods','sku_price'])->where(['user_id' => $user->id])->select();

        foreach ($cartData as $key => &$cart) {
            if (!is_null($cart['goods']['deletetime']) || $cart['goods']['status'] === 'down' || empty($cart['sku_price']) || !is_null($cart['sku_price']['deletetime'])) {
                $cart['cart_type'] = 'invalid';
            }
        }

        return $cartData;
    }

    public static function add($goodsList)
    {

        $user = User::info();

        foreach ($goodsList as $v) {
            $where = [
                'user_id' => $user->id,
                'goods_id' => $v['goods_id'],
                'sku_price_id' => $v['sku_price_id'],
                'deletetime' => null
            ];
            $cart = self::where($where)->find();
            if ($cart) {
                $cart->buy_num += $v['buy_num'];
                $cart->save();
            }else{
                $cartData = [
                    'user_id' => $user->id,
                    'goods_id' => $v['goods_id'],
                    'buy_num' => $v['buy_num'],
                    'sku_price_id' => $v['sku_price_id']
                ];
                $cart = self::create($cartData);
            }

        }

        return $cart;

    }

    public static function edit($params)
    {
        extract($params);
        $user = User::info();
        $where['user_id'] = $user->id;
        switch ($act) {
            case 'del':
                self::where(['id'=>['in',$ids]])->delete();
                break;
            case 'change':
                foreach ($cart_list as $v) {
                    $where['id'] = $v;
                    self::where($where)->update(['goods_num' => $value]);
                }
                break;
        }

        return true;

    }

    public function goods()
    {
        return $this->hasOne(Goods::class, 'id', 'goods_id');
    }

    public function skuPrice()
    {
        return $this->hasOne('plugin\xypm\app\model\api\goods\SkuPrice', 'id', 'sku_price_id');
    }
    

}
