<?php

namespace plugin\xypm\app\model\admin;

use plugin\saiadmin\basic\BaseNormalModel;

use think\model\concern\SoftDelete;


class Page extends BaseNormalModel
{

    use SoftDelete;

    // 表名
    protected $name = 'xypm_page';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'integer';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = 'deletetime';

    // 追加属性
    protected $append = [
        'type_text',
        'status_text'
    ];
    
    public function getTypeList()
    {
        return ['index' => trans('首页模板',[],'page'),'shop' => trans('商城模板',[],'page'),'user' => trans('用户中心模板',[],'page')];
    }

    public function getStatusList()
    {
        return ['normal' => trans('Normal',[],'page'), 'hidden' => trans('Hidden',[],'page')];
    }

	public function getPageAttr($value)
	{
		$status = json_decode($value, true);
	    return $status;
	}
	
	public function getItemAttr($value)
	{
		$status = json_decode($value, true);
	    return $status;
	}


    public function getTypeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['type']) ? $data['type'] : '');
        $list = $this->getTypeList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getStatusTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['status']) ? $data['status'] : '');
        $list = $this->getStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }




}
