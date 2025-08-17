<?php

namespace plugin\xypm\app\controller\admin;


use plugin\saiadmin\basic\BaseController;
use plugin\xypm\app\logic\PageLogic;

use plugin\xypm\app\model\admin\Config;
use plugin\xypm\app\model\admin\Page;
use support\Request;
use support\Response;
use think\exception\DbException;
use think\exception\PDOException;


/**
 * 自定义装修页面管理
 *
 * @icon fa fa-circle-o
 */
class PageController extends BaseController
{





    public function __construct()
    {
        $this->logic = new PageLogic();
        $this->model = '';
        parent::__construct();
    }

    /**
     * 数据列表
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $where = $request->more([
            ['id', ''],
            ['type', ''],

        ]);

        $query = $this->logic->search($where);

        $data = $this->logic->getAll($query);
        foreach ($data as &$v) {
            $v['cover'] = cdnurl($v['cover']);
        }
        return $this->success($data);
    }



    /**
    /**
	 * 添加
	 */
	public function save(Request $request): Response
    {

        $data  = $request->post();
        $params['page_token'] = randomAlnum(16);
        $params['page'] = '{"params":{"navigationBarTitleText":"标题"},"style":{"navigationBarTextStyle":"#ffffff","navigationBarBackgroundColor":"#56a3ff","pageBackgroundColor":"#f7f7f7"}}';
        $params['item'] = '[]';
        $result = $this->logic->add($params);
        return $this->success('创建成功');

	}

    /**
     * 编辑
     *
     */
    public function update(Request $request,$ids = null): Response
    {

			
	        $params = $request->post();
			$row = $this->logic->search(['page_token'=>$params['page_token']])->find();
	        if ($params) {
				if(!array_key_exists("item",$params)){
					return $this->fail('页面还没有任何组件哦');
				}

                $data = [
                    'name'=>$params['name'],
                    'is_use'=>$params['is_use'],
                    'cover'=>$params['cover'],
                    'type'=>$params['type'],
                    'createtime'=> time(),
                    'updatetime'=> time(),
                    'page_token'=>$params['page_token'],
                    'page'=>json_encode($params['page']),
                    'item'=>json_encode($params['item'])
                ];
                $id = Page::insertGetId($data);


                $pages = (new Page)
                    ->where('page_token', $data['page_token'])
                    ->where('id', '<>', $id)
                    ->select();
				foreach ($pages as $k => $v) {
					$v->is_use = 0;
					$v->save();
				    $v->delete();
				}
				return $this->success("发布并保存成功");
	        }
	        return $this->fail('参数不能为空');


    }


    /**
     * 详情
     *
     */
    public function read(Request $request,$id): Response
    {


        $id = $request->input('id', $id);
        $row = $this->logic->read($id);




        $row = json_decode($row,true);
        return $this->success($row);
    }

	/**
	 * 历史记录
	 */
	public function history(Request $request): Response
	{	
		//设置过滤方法


        $where = $request->more([
            ['page_token', ''],
            ['name', ''],
        ]);

        $query = $this->logic->search($where,true);

        $result =$this->logic->getList($query);
        return$this->success($result);

	}

	/**
	 * 恢复历史
	 */
	public function recover(Request $request): Response{

        $id = $request->get('id');

        $row = $this->logic->read($id,true);



        $row = json_decode($row,true);
        return $this->success($row,"拉取历史数据成功");
	}

	/**
	 * 使用模板
	 */
	public function use(Request $request): Response
	{
            $ids  = $request->post('ids');
			$rows = $this->logic->read($ids);
			if(!$rows){
				return $this->fail('模板不存在');
			}

            $rows->is_use = 1;
            $rows->save();
            $result = $this->logic
                ->where('type', $rows['type'])
                ->where('id', '<>', $rows->id)
                ->select();
            // 将其他模板设为未使用
            foreach ($result as $k => $v) {
                $v->is_use = 0;
                $v->save();
            }

			return $this->success("发布成功");
			

	}


	
}
