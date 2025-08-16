<?php
// +----------------------------------------------------------------------
// | saiadmin [ saiadmin快速开发框架 ]
// +----------------------------------------------------------------------
// | Author: your name
// +----------------------------------------------------------------------
namespace plugin\xypm\app\logic\recharge;

use plugin\saiadmin\basic\BaseLogic;
use plugin\xypm\app\model\admin\recharge\Order;
use plugin\xypm\exception\ApiException;


/**
 * 充值订单管理逻辑层
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
                if($v == 'user_nickname' && !empty($data[$v])) {
                    unset($data[$v]);
                    unset($withSearch[$k]);
                    continue;
                }
                if ($data[$v] === '') {
                    unset($data[$v]);
                    unset($withSearch[$k]);
                }
            }
    //        return $this->model->with(['user'])->withSearch($withSearch, $data);


            $query = $this->model
                ->join('xypm_user user', 'user.id = xypm_recharge_order.user_id');
            if(!empty($searchWhere['user_nickname'])){
                $query = $query->where('user.nickname', 'like', '%'.$searchWhere['user_nickname'].'%');
            }
            return $query->withSearch($withSearch, $data)->field('xypm_recharge_order.*,user.nickname');
        }


}
