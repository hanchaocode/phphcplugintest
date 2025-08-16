<?php
// +----------------------------------------------------------------------
// | saiadmin [ saiadmin快速开发框架 ]
// +----------------------------------------------------------------------
// | Author: your name
// +----------------------------------------------------------------------
namespace plugin\xypm\app\logic\recharge;

use plugin\saiadmin\basic\BaseLogic;
use plugin\xypm\app\model\admin\recharge\Recharge;


/**
 * 充值管理逻辑层
 */
class RechargeLogic extends BaseLogic
{


    /**
     * 构造函数
     */
    public function __construct()
    {
        $this->model = new Recharge();

    }




}
