<?php
// +----------------------------------------------------------------------
// | saiadmin [ saiadmin快速开发框架 ]
// +----------------------------------------------------------------------
// | Author: your name
// +----------------------------------------------------------------------
namespace plugin\xypm\app\logic;


use plugin\saiadmin\basic\BaseLogic;
use plugin\saiadmin\exception\ApiException;
use plugin\xypm\app\model\admin\Page;


/**
 *页面管理逻辑层
 */
class PageLogic extends BaseLogic
{

    /**
     * 构造函数
     */
    public function __construct()
    {
        $this->model = new Page();

    }



    /**
     * 读取数据
     * @param $id
     * @return mixed
     */
    public function read($id,$is_deleted=false): mixed
    {


        if($is_deleted){
            $model =  $this->model->onlyTrashed()->findOrEmpty($id);
        } else{
            $model = $this->model->findOrEmpty($id);
        }

        if ($model->isEmpty()) {
            throw new ApiException('数据不存在');
        }
        return $model;
    }

    public function search(array $searchWhere = [],$is_deleted=false): mixed
    {
        $withSearch = array_keys($searchWhere);
        $data = $searchWhere;
        foreach ($withSearch as $k => $v) {
            if ($data[$v] === '') {
                unset($data[$v]);
                unset($withSearch[$k]);
            }
        }
        if($is_deleted){
            return $this->model->onlyTrashed()->withSearch($withSearch, $data);
        }
        return $this->model->withSearch($withSearch, $data);
    }

}
