<?php
// +----------------------------------------------------------------------
// | saiadmin [ saiadmin快速开发框架 ]
// +----------------------------------------------------------------------
// | Author: your name
// +----------------------------------------------------------------------
namespace plugin\xypm\app\logic;


use plugin\saiadmin\basic\BaseLogic;

use plugin\xypm\app\model\admin\Link;


/**
 *链接管理逻辑层
 */
class LinkLogic extends BaseLogic
{

    /**
     * 构造函数
     */
    public function __construct()
    {
        $this->model = new Link();

    }


    public  function options(){
        return $this->model->select()->toArray();

    }


}
