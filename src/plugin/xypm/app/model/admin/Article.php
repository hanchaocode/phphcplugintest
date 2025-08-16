<?php

namespace plugin\xypm\app\model\admin;

use plugin\saiadmin\basic\BaseNormalModel;

use think\model\concern\SoftDelete;


class Article extends BaseNormalModel
{

    use SoftDelete;

    // 表名
    protected $name = 'xypm_article';



    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'status_text'
    ];




    public function getStatusList()
    {
        return ['normal' => trans('Normal',[],'common'), 'hidden' => trans('Hidden',[],'common')];
    }


    public function getStatusTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['status']) ? $data['status'] : '');
        $list = $this->getStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function searchCreatetimeAttr($query, $value)
    {
        $query->whereTime('createtime', 'between', $value);
    }



}
