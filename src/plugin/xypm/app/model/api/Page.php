<?php
namespace plugin\xypm\app\model\api;

use plugin\saiadmin\basic\BaseNormalModel;

use think\model\concern\SoftDelete;

class Page extends BaseNormalModel
{
    use SoftDelete;

    // 表名
    protected $name = 'xypm_page';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = 'deletetime';

	/**
	 * 将page字段转换数组
	 */
	public function getPageAttr($value)
	{
		$status = json_decode($value, true);
	    return $status;
	}
	
	/**
	 * 将item字段转换数组
	 */
	public function getItemAttr($value)
	{
		$status = json_decode($value, true);
	    return $status;
	}
    
}
