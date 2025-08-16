<?php
// +----------------------------------------------------------------------
// | saiadmin [ saiadmin快速开发框架 ]
// +----------------------------------------------------------------------
// | Author: your name
// +----------------------------------------------------------------------
namespace plugin\xypm\app\logic\build;

use plugin\saiadmin\basic\BaseLogic;
use plugin\xypm\app\model\admin\build\Area;

/**
 * 区域管理逻辑层
 */
class AreaLogic extends BaseLogic
{


    /**
     * 构造函数
     */
    public function __construct()
    {
        $this->model = new Area();

    }


    public  function options($type){
        if (empty($type)) {
            return $this->model->select()->toArray();
        }
        return $this->model->where(['type'=>$type])->select()->toArray();

    }
}
