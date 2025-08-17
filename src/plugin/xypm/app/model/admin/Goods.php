<?php

namespace plugin\xypm\app\model\admin;

use plugin\saiadmin\basic\BaseModel;
use plugin\xypm\app\model\admin\goods\Category;

use think\model\concern\SoftDelete;



class Goods extends BaseModel
{

    use SoftDelete;

    

    // 表名
    protected $name = 'xypm_goods';
    


    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = 'deletetime';

    // 追加属性
    protected $append = [
        'is_sku_text',
        'status_text',
        'category_ids_text'
    ];
    

    
    public function getIsSkuList()
    {
        return ['0' => trans('单规格'), '1' => trans('多规格')];
    }

    public function getStatusList()
    {
        return ['up' => trans('Status up'),  'down' => trans('Status down')];
    }

    public function searchLinePriceAttr($query, $value)
    {
        // 确保 $value 是一个数组，并且有两个元素
        if (is_array($value) && count($value) === 2) {
            list($min, $max) = $value;

            // 如果 min 和 max 都是有效值，则执行查询
            if ($min !== '' || $max !== '') {
                // 将空字符串转换为 null，这样可以使用数据库的 NULL 处理
                $min = $min === '' ? null : $min;
                $max = $max === '' ? null : $max;

                // 构建查询条件
                if ($min !== null && $max !== null) {
                    $query->whereBetween('line_price', [$min, $max]);
                } elseif ($min !== null) {
                    $query->where('line_price', '>=', $min);
                } elseif ($max !== null) {
                    $query->where('line_price', '<=', $max);
                }
            }
        }

    }
    public function searchCategoryIdsAttr($query, $value)
    {
        $query->whereIn('category_ids', implode(',',$value));
    }
    public function searchTitleAttr($query, $value)
    {
        $query->where('title', 'like', '%'.$value.'%');
    }
    public function getIsSkuTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['is_sku']) ? $data['is_sku'] : '');
        $list = $this->getIsSkuList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getStatusTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['status']) ? $data['status'] : '');
        $list = $this->getStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }

    public function getCategoryIdsTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['category_ids']) ? $data['category_ids'] : '');
        $ids = explode(',', $value);

        // 获取所有匹配的记录
        $categories = Category::whereIn('id',$ids)->select();

        // 提取名称并合并为一个字符串
        $names = [];
        foreach ($categories as $category) {
            $names[] = $category->name;
        }

        return implode(',', $names);

    }

    public function searchCreatetimeAttr($query, $value)
    {
        $query->whereTime('createtime', 'between', $value);
    }


}
