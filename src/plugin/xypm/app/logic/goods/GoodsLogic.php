<?php
// +----------------------------------------------------------------------
// | saiadmin [ saiadmin快速开发框架 ]
// +----------------------------------------------------------------------
// | Author: your name
// +----------------------------------------------------------------------
namespace plugin\xypm\app\logic\goods;

use plugin\saiadmin\basic\BaseLogic;

use plugin\xypm\app\model\admin\Goods;


/**
 * 商品管理逻辑层
 */
class GoodsLogic extends BaseLogic
{


    /**
     * 构造函数
     */
    public function __construct()
    {
        $this->model = new Goods();

    }


}
