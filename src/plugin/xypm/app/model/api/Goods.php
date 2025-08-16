<?php

namespace  plugin\xypm\app\model\api;

use plugin\saiadmin\basic\BaseNormalModel;

use plugin\xypm\app\model\api\goods\Sku;
use plugin\xypm\app\model\api\goods\SkuPrice;
use plugin\xypm\exception\ApiException;
use plugin\xypm\utils\Tools;
use support\Cache;
use think\model\concern\SoftDelete;
use think\Collection;



class Goods extends BaseNormalModel
{

    use SoftDelete;



    // 表名
    protected $name = 'xypm_goods';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'integer';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = 'deletetime';

    // 追加属性
    protected $append = [
        'is_sku_text',
        'status_text',
        'stock'
    ];

    //列表动态隐藏字段
    public static $list_hidden = ['content','images','updatetime','deletetime'];

    public static function getDetail($id)
    {
        $user = User::info();

        $detail = (new self)->where('id', $id)->with(['favorite' => function ($query) use ($user) {
            $user_id = empty($user) ? 0 : $user->id;
            return $query->where(['user_id' => $user_id]);
        }])->find();

        if (!$detail || $detail->status === 'down') {
            return null;
        }

        $detail = $detail->append(['sku']);

        return $detail;
    }

    /**
     * params 请求参数
     */
    public static function getList($params)
    {

        extract($params);

        $where = [
            'status' => 'up'
        ];
        if (isset($order)) {
            $order = self::getListOrder($order);

        }else{
            $order = 'weigh desc';
        }
        if (isset($search) && $search !== '') {
            $where['title'] = ['like', "%$search%"];
        }

        if (isset($ids) && $ids !== '') {
            $order = 'field(id, ' . $ids . ')';
            $idsArray = explode(',', $ids);
            $where['id'] = ['in', $idsArray];
        }

//        throw new ApiException(json_encode($where));


        $category_ids = [];
        if (isset($category_id) && $category_id != 0) {
            $category_ids = Category::getCategoryIds($category_id);
        }

        $goods = self::where($where)->where(function ($query) use ($category_ids) {
            foreach($category_ids as $key => $category_id) {
                $category_id = Tools::xypmFilterSql($category_id);
                $query->whereOrRaw("find_in_set($category_id, category_ids)");
            }
        });

        $order = Tools::xypmFilterSql($order);
        $goods = $goods->field('*,(sales + show_sales) as total_sales')->orderRaw($order)->order('id desc');

        $cacheKey = 'goodslist-' . (isset($page) ? 'page' : 'all') . '-' . md5(json_encode($params));

        // 判断缓存
        $goodsCache = Cache::get($cacheKey);
        if ($goodsCache) {
            // 存在缓存直接 返回
            $goodsCache = json_decode($goodsCache, true);
            return $goodsCache ? : [];
        }

        if (isset($page)) {
            $goods = $goods->paginate();
            $goodsData = $goods->items();
        } else {
            $goods = $goodsData = $goods->select();
        }

        $data = [];
        if ($goodsData) {
            // todo
            $collection = new Collection($goodsData);
            // 手动过滤字段
            $data = $collection->map(function ($item) {
                // 假设你想隐藏 'secret_field'
                unset($item['secret_field']);
                return $item;
            })->toArray();
        }

        if (isset($page)) {
            $goods->data = $data;
        } else {
            $goods = $data;
            Cache::set($cacheKey, json_encode($goods), (600 + mt_rand(0, 300)));
        }

        return $goods;
    }

    //列表排序
    private static function getListOrder($orderStr)
    {
        $order = 'weigh desc';
        $orderList = json_decode(htmlspecialchars_decode($orderStr), true);
        extract($orderList);
        if (isset($defaultOrder) && $defaultOrder === 1) {
            $order = 'weigh desc';
        }
        if (isset($priceOrder) && $priceOrder === 1) {
            $order = "convert(`price`, DECIMAL(10, 2)) asc";
        }elseif (isset($priceOrder) && $priceOrder === 2) {
            $order = "convert(`price`, DECIMAL(10, 2)) desc";
        }
        if (isset($salesOrder) && $salesOrder === 1){
            $order = 'sales desc';
        }
        return $order;

    }

    protected function getSkuAttr($value, $data)
    {
        $sku = Sku::where([
            'goods_id'=>$data['id'],
            'pid' => 0,
        ])->select()->toArray();
        foreach ($sku as $s => &$k) {
            $sku[$s]['content'] = Sku::where([
                'goods_id' => $data['id'],
                'pid' => $k['id'],
            ])->select()->toArray();
        }
        return $sku;
    }

    public function getImageAttr($value, $data)
    {
        if (!empty($value)) return cdnurl($value, true);

    }

    public function getStockAttr($value, $data)
    {
        return skuPrice::where([
            'goods_id' => $data['id'],
            'status' => 'up',
            'deletetime' => null
        ])->sum('stock');
    }

    public function getImagesAttr($value, $data)
    {
        $imagesArray = [];
        if (!empty($value)) {
            $imagesArray = explode(',', $value);
            foreach ($imagesArray as &$v) {
                $v = cdnurl($v, true);
            }
            return $imagesArray;
        }
        return $imagesArray;
    }

    public function getTagsAttr($value, $data)
    {
        return explode(',', $value);
    }

    public function getIsSkuList()
    {
        return ['0' => trans('单规格'), '1' => trans('多规格')];
    }

    public function getStatusList()
    {
        return ['up' => trans('Status up'),  'down' => trans('Status down')];
    }


    public function getIsSkuTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['is_sku']) ? $data['is_sku'] : '');
        $list = $this->getIsSkuList();
        return isset($list[$value]) ? $list[$value] : '';
    }

    public function getContentAttr($value, $data)
    {

        $content = $data['content'];
        $pattern = '/style="(.*?)"/i';
        $replacement = '';
        $content = preg_replace($pattern, $replacement, $content);
        $content = str_replace("<img src=\"/uploads", "<img src=\"" . cdnurl("/uploads", true), $content);
        $content = str_replace("<video src=\"/uploads", "<video src=\"" . cdnurl("/uploads", true), $content);
        $content = str_replace("<img src=\"/assets", "<img src=\"" . cdnurl("/assets", true), $content);
        $content = str_replace("<video src=\"/assets", "<video src=\"" . cdnurl("/assets", true), $content);
        return $content;

    }

    public function getStatusTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['status']) ? $data['status'] : '');
        $list = $this->getStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }

    public function favorite()
    {
        return $this->hasOne('\plugin\xypm\app\model\api\user\Favorite', 'target_id', 'id')->where(['type'=>'goods']);
    }

    public function skuPrice()
    {
        return $this->hasMany(SkuPrice::class, 'goods_id', 'id')->order('id', 'asc');
    }


}
