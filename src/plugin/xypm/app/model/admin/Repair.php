<?php

namespace plugin\xypm\app\model\admin;

use plugin\saiadmin\basic\BaseNormalModel;

use think\model\concern\SoftDelete;



class Repair extends BaseNormalModel
{

    use SoftDelete;

    

    // 表名
    protected $name = 'xypm_repair';
    


    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = 'deletetime';

    // 追加属性
    protected $append = [
        'status_text'
    ];
    

    
    public function getStatusList()
    {
        return ['0' => trans('Status 0',[],'repair'), '1' => trans('Status 1',[],'repair'), '2' => trans('Status 2',[],'repair')];
    }


    public function getStatusTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['status']) ? $data['status'] : '');
        $list = $this->getStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }

    public function getImagesAttr($value, $data)
    {
        $imagesArray = [];
        if (!empty($value)) {
            $imagesArray = explode(',', $value);
            foreach ($imagesArray as &$v) {
//                $v = cdnurl($v, true);
                continue;
            }
            return $imagesArray;
        }
        return $imagesArray;
    }


    public function searchCreatetimeAttr($query, $value)
    {
        $query->whereTime('createtime', 'between', $value);
    }



}
