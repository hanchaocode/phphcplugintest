<?php

namespace plugin\xypm\app\controller\api;
use plugin\xypm\basic\FrontController;
use plugin\xypm\app\model\api\Article as ArticleModel;
use support\Request;
use support\Response;

/**
 * 文章接口
 */
class ArticleController extends FrontController
{
    protected $noNeedLogin = ['lists','detail'];
    protected $noNeedRight = ['*'];
    
	
    /**
     * 文章详情
     * @param Request $request
     * @param $id
     */
    public function detail(Request $request, $id): Response
    {
        $id = $request->get('id');
        $detail = ArticleModel::getDetail($id);

        if(!$detail){
            return $this->fail('文章不存在！');
        }

        return $this->success('文章详情', $detail);
    }
	
}