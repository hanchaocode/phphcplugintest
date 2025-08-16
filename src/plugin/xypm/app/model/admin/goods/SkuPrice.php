<?php

namespace plugin\xypm\app\model\admin\goods;

use think\Model;
use think\model\concern\SoftDelete;


class SkuPrice extends Model
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
        'goods_sku_text'
    ];
    
    
    public function getGoodsSkuTextAttr($value, $data)
    {

        if (empty($value)) {
            return '';
        }
        return array_filter(explode(',', $value));
    }


    public static function onBeforeInsert($model)
    {
        $info = getCurrentInfo();
        $info && $model->setAttr('weigh', $info['id']);
    }

    
    public function getStatusList()
    {
        return ['up' => trans('Status up'),  'down' => trans('Status down')];
    }


    public function getStatusTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['status']) ? $data['status'] : '');
        $list = $this->getStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }




}
