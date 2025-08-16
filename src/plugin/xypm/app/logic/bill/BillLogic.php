<?php
// +----------------------------------------------------------------------
// | saiadmin [ saiadmin快速开发框架 ]
// +----------------------------------------------------------------------
// | Author: your name
// +----------------------------------------------------------------------
namespace plugin\xypm\app\logic\bill;

use plugin\saiadmin\basic\BaseLogic;
use plugin\xypm\app\model\admin\Bill;


/**
 * 账单管理逻辑层
 */
class BillLogic extends BaseLogic
{


    /**
     * 构造函数
     */
    public function __construct()
    {
        $this->model = new Bill();

    }


}
