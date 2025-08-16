<?php
// +----------------------------------------------------------------------
// | saiadmin [ saiadmin快速开发框架 ]
// +----------------------------------------------------------------------
// | Author: your name
// +----------------------------------------------------------------------
namespace plugin\xypm\app\logic\build;

use plugin\saiadmin\basic\BaseLogic;

use plugin\xypm\app\model\admin\Build;
use Throwable;

/**
 * 楼宇管理逻辑层
 */
class BuildLogic extends BaseLogic
{


    /**
     * 构造函数
     */
    public function __construct()
    {
        $this->model = new Build();

    }
    public function batchadd($data): mixed
    {
        $this->model->saveAll($data);

        return $this->model->getKey();
    }

    public  function options(){
        return $this->model->select()->toArray();

    }
}
