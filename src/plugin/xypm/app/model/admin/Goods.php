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
        $categories = Category::where('id', 'in',$ids)->select();

        // 提取名称并合并为一个字符串
        $names = [];
        foreach ($categories as $category) {
            $names[] = $category->name;
        }

        return implode(',', $names);

    }



}
