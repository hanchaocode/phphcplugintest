<?php
// +----------------------------------------------------------------------
// | saiadmin [ saiadmin快速开发框架 ]
// +----------------------------------------------------------------------
// | Author: your name
// +----------------------------------------------------------------------
namespace plugin\xypm\app\logic;


use plugin\saiadmin\basic\BaseLogic;
use plugin\saiadmin\utils\Helper;
use plugin\xypm\app\model\admin\goods\Category;


/**
 *分类管理逻辑层
 */
class CategoryLogic extends BaseLogic
{

    /**
     * 构造函数
     */
    public function __construct()
    {
        $this->model = new Category();

    }


    public  function options(){
        return $this->model->select()->toArray();

    }


    /**
     * 数据树形化
     * @param $where
     * @return array
     */
    public function tree($where = []): array
    {
        $query = $this->search($where);
        if (request()->input('tree', 'false') === 'true') {
            $query->field('id, id as value, name as label, pid');
        }
        $query->order('weigh', 'desc');
        $data = $this->getAll($query);
        return Helper::makeTree($data, pidName: 'pid');
    }

}
