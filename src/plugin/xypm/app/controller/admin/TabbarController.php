<?php

namespace plugin\xypm\app\controller\admin;

use Exception;

use plugin\saiadmin\basic\BaseController;
use plugin\xypm\app\logic\ConfigLogic;

use plugin\xypm\app\model\admin\Config;
use support\Request;
use support\Response;
use think\exception\PDOException;


/**
 * 底部导航
 */
class TabbarController extends BaseController
{

    public function __construct()
    {
        $this->logic = new ConfigLogic();

        parent::__construct();
    }

    public function get(Request $request): Response
    {




        $config = $this->logic->search(['name' => 'tabbar'])->value('value');
        $config = json_decode($config, true);
        foreach ($config['list'] as &$item) {
            $item['show'] = intval($item['show']);
            if (isset($item['iconPath'])) {
                $item['iconPath'] = cdnurl($item['iconPath']);
            }
            if (isset($item['selectedIconPath'])) {
                $item['selectedIconPath'] = cdnurl($item['selectedIconPath']);
            }
        }

        return  $this->success($config);
    }

    /**
     * 数据列表
     * @param Request $request
     * @return Response
     */
    public function save(Request $request): Response
    {



        $data = $request->post("data");
        if ($data) {
            try {
                $config = Config::where(['name' => 'tabbar'])->find();
                if(!$config) {
                    $this->logic->save([
                        'name' => 'tabbar',
                        'title' => '底部导航',
                        'group' => 'tabbar',
                        'type' => 'array',
                        'value' => $data,
                    ]);
                }else {
                    $config->value = $data;
                    $config->save();
                }

            } catch (Exception $e) {
                return $this->fail($e->getMessage());
            }
            return $this->success('保存成功');
        }
        return $this->fail('操作失败');

    }




	
}
