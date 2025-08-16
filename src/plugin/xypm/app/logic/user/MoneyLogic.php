<?php
// +----------------------------------------------------------------------
// | saiadmin [ saiadmin快速开发框架 ]
// +----------------------------------------------------------------------
// | Author: your name
// +----------------------------------------------------------------------
namespace plugin\xypm\app\logic\user;

use plugin\saiadmin\basic\BaseLogic;
use plugin\xypm\app\model\admin\Bill;
use plugin\xypm\app\model\api\user\Money;


/**
 * 余额记录管理逻辑层
 */
class MoneyLogic extends BaseLogic
{


    /**
     * 构造函数
     */
    public function __construct()
    {
        $this->model = new Money();

    }


}
