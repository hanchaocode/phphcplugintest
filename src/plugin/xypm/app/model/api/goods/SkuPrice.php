<?php

namespace plugin\xypm\app\model\api\goods;


use plugin\saiadmin\basic\BaseNormalModel;
use think\model\concern\SoftDelete;



class SkuPrice extends BaseNormalModel
{

    use SoftDelete;

    // 表名
    protected $name = 'xypm_goods_sku_price';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'integer';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = 'deletetime';

    // 追加属性
    protected $append = [
        'status_text',
        'goods_sku_id_arr'
    ];


    public function searchTitleAttr($query, $value)
    {
        $query->where('title', 'like', '%'.$value.'%');
    }
    public function getImageAttr($value, $data)
    {
        if (!empty($value)) return cdnurl($value, true);
        return $value;

    }

    public function getGoodsSkuIdArrAttr($value, $data)
    {
        if (empty($data['goods_sku_ids'])) return [];
        return  explode(',', $data['goods_sku_ids']);
    }


    public static function onBeforeInsert($model)
    {
        $info = getCurrentInfo();
        $info && $model->setAttr('weigh', $info['id']);
    }
    public function getStatusList()
    {
        return ['up' => trans('Status up',[],'goods/skuprice'),  'down' => trans('Status down',[],'goods/skuprice')];
    }

    public function getStatusTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['status']) ? $data['status'] : '');
        $list = $this->getStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }



}
