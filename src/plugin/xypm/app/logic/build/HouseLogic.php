<?php
// +----------------------------------------------------------------------
// | saiadmin [ saiadmin快速开发框架 ]
// +----------------------------------------------------------------------
// | Author: your name
// +----------------------------------------------------------------------
namespace plugin\xypm\app\logic\build;

use plugin\saiadmin\basic\BaseLogic;
use plugin\xypm\app\model\admin\build\House;


/**
 * 房屋管理逻辑层
 */
class HouseLogic extends BaseLogic
{

    /**
     * 构造函数
     */
    public function __construct()
    {
        $this->model = new House();

    }
    public function batchadd($data): mixed
    {
        $this->model->saveAll($data);
        return $this->model->getKey();
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
        return $this->model->with('build')->withSearch($withSearch, $data);
    }


    public  function options(){
        return $this->model->with('build')->select()->toArray();

    }
}
