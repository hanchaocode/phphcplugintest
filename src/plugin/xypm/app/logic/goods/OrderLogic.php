<?php
// +----------------------------------------------------------------------
// | saiadmin [ saiadmin快速开发框架 ]
// +----------------------------------------------------------------------
// | Author: your name
// +----------------------------------------------------------------------
namespace plugin\xypm\app\logic\goods;

use plugin\saiadmin\basic\BaseLogic;
use  plugin\xypm\app\model\admin\goods\Order;

/**
 * 订单管理逻辑层
 */
class OrderLogic extends BaseLogic
{


    /**
     * 构造函数
     */
    public function __construct()
    {
        $this->model = new Order();

    }


    public function search(array $searchWhere = []): mixed
    {
        $withSearch = array_keys($searchWhere);
        $data = $searchWhere;
        foreach ($withSearch as $k => $v) {
            if ($data[$v] === '') {
                unset($data[$v]);
                unset($withSearch[$k]);
            }
        }
        return $this->model->with(['item','user'])->withSearch($withSearch, $data);
    }


}
