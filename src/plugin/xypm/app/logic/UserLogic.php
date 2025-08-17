<?php
// +----------------------------------------------------------------------
// | saiadmin [ saiadmin快速开发框架 ]
// +----------------------------------------------------------------------
// | Author: your name
// +----------------------------------------------------------------------
namespace plugin\xypm\app\logic;


use plugin\saiadmin\basic\BaseLogic;
use plugin\xypm\app\model\admin\User;


/**
 *會員管理逻辑层
 */
class UserLogic extends BaseLogic
{

    /**
     * 构造函数
     */
    public function __construct()
    {
        $this->model = new User();

    }

    public function search(array $searchWhere = []): mixed
    {
        $withSearch = array_keys($searchWhere);
        $data = $searchWhere;
        foreach ($withSearch as $k => $v) {
            if($v == 'third_platform' && !empty($data[$v])) {
                unset($data[$v]);
                unset($withSearch[$k]);
                continue;
            }
            if ($data[$v] === '') {
                unset($data[$v]);
                unset($withSearch[$k]);
            }
        }
        //        return $this->model->with(['third'])->withSearch($withSearch, $data);


        $query = $this->model
            ->join('xypm_third third', 'xypm_user.id = third.user_id');
        if(!empty($searchWhere['third_platform'])){
            $query = $query->where('xypm_third.platform', $searchWhere['third_platform']);
        }
        return $query->withSearch($withSearch, $data)->field('xypm_user.*,third.platform as third_platform  ');
    }


}
