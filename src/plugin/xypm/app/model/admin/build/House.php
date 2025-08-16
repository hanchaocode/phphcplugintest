<?php

namespace plugin\xypm\app\model\admin\build;


use plugin\saiadmin\basic\BaseNormalModel;


class House extends  BaseNormalModel
{



    // 表名
    protected $name = 'xypm_build_house';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'integer';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';


    protected $readonly = ['created_by', 'create_time'];

    // 追加属性
    protected $append = [
        'status_text'
    ];



    public function searchCreateTimeAttr($query, $value)
    {
        $query->whereTime('createtime', 'between', $value);
    }

    /**
     * ID IN 查询搜索器
     * @param $query
     * @param $value 支持数组或逗号分隔的字符串
     */
    public function searchIdAttr($query, $value)
    {
        if (empty($value)) {
            return;
        }

        // 支持数组或逗号分隔的字符串
        $ids = is_array($value) ? $value : explode(',', $value);

        // 过滤非数字值
        $ids = array_filter($ids, function($id) {
            return is_numeric($id);
        });

        if (!empty($ids)) {
            $query->where('id', 'in', $ids);
        }
    }
    public function getStatusList()
    {
        return ['1' => trans('Status 1',[],'build_house'), '0' => trans('Status 0',[],'build_house')];
    }


    public function getStatusTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['status']) ? $data['status'] : '');
        $list = $this->getStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }

    public function build()
    {
        return $this->belongsTo('plugin\xypm\app\model\admin\Build', 'build_id', 'id', [], 'LEFT');
    }




}
