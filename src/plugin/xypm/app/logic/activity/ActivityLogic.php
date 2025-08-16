<?php
// +----------------------------------------------------------------------
// | saiadmin [ saiadmin快速开发框架 ]
// +----------------------------------------------------------------------
// | Author: your name
// +----------------------------------------------------------------------
namespace plugin\xypm\app\logic\activity;

use plugin\saiadmin\basic\BaseLogic;
use plugin\xypm\app\model\admin\activity\Activity;


/**
 * 活动管理逻辑层
 */
class ActivityLogic extends BaseLogic
{


    /**
     * 构造函数
     */
    public function __construct()
    {
        $this->model = new Activity();

    }


}
