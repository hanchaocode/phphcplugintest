<?php

namespace plugin\xypm\app\controller\admin;


use plugin\saiadmin\app\logic\system\SystemConfigLogic;
use plugin\saiadmin\basic\BaseController;
use plugin\saiadmin\utils\Arr;
use plugin\xypm\app\logic\ConfigLogic;
use support\Request;
use support\Response;
use think\Exception;


/**
 * 配置
 */
class ConfigController extends BaseController
{




    public function __construct()
    {
        $this->logic = new ConfigLogic();

        parent::__construct();
    }



    /**
     * 查看
     */
    public function index()
    {
        $siteList = [];
        $groupList = $this->logic->getGroupList();
        foreach ($groupList as $k => $v) {
            $siteList[$k]['name'] = $k;
            $siteList[$k]['title'] = $v;
            $siteList[$k]['list'] = [];
        }

        $data = $this->logic->getAll([]);
        foreach ($data as $k => $v) {
            if (!isset($siteList[$v['group']])) {
                continue;
            }
            $value = $v->toArray();
            $value['title'] = trans($value['title'],[],'config');
            if (in_array($value['type'], ['select', 'selects', 'checkbox', 'radio'])) {
                $value['value'] = explode(',', $value['value']);
            }
            $value['content'] = json_decode($value['content'], true);
            $value['tip'] = htmlspecialchars($value['tip']);
            $siteList[$v['group']]['list'][] = $value;
        }
        $index = 0;
        foreach ($siteList as $k => &$v) {
            $v['active'] = !$index ? true : false;
            $index++;
        }

        $data['siteList'] = $siteList;
        $data['typeList'] =  $this->logic->getTypeList();
        $data['ruleList'] =  $this->logic->getRegexList();
        $data['groupList'] =  $this->logic->getGroupList();
        return $this->success($data);
    }




    public function set(Request $request): Response
    {



        $data = $request->more([

            ['name', ''],

        ]);
        $name = $data['name'];
        $config = $this->logic->search(['name' => $name])->value('value');
        $config = json_decode($config, true);

        return  $this->success($config);
    }


    /**
     * 保存
     * @param Request $request
     * @return Response
     */
    public function edit(Request $request): Response
    {


        $data = $request->more([

            ['name', ''],
            ['group', ''],
            ['type', ''],
            ['value', ''],

        ]);


        try {
            $config = $this->logic->search(['name' => $data['name']])->find();
            if (!$config) {
                $this->logic->save([
                    'name' => $data['name'],
                    'group' => $data['group'],
                    'type' => 'array',
                    'value' => $data['value'],
                ]);
            } else {
                $config->value = $data['value'];
                $config->save();
            }

        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }



        return $this->success('更新成功');


    }

    public function cdnurl(Request $request): Response
    {


        return $this->success(['cdnurl'=>cdnurl()]);
    }

}
