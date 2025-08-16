<?php
// +----------------------------------------------------------------------
// | saiadmin [ saiadmin快速开发框架 ]
// +----------------------------------------------------------------------
// | Author: your name
// +----------------------------------------------------------------------
namespace plugin\xypm\app\logic;


use plugin\saiadmin\basic\BaseLogic;

use plugin\xypm\app\model\admin\Notice;


/**
 * 公告管理逻辑层
 */
class NoticeLogic extends BaseLogic
{

    /**
     * 构造函数
     */
    public function __construct()
    {
        $this->model = new Notice();

    }




}
