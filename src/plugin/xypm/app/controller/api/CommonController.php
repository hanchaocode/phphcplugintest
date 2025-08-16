<?php

namespace plugin\xypm\app\controller\api;



use plugin\xypm\app\model\admin\Area;
use plugin\xypm\app\model\api\Page;
use plugin\xypm\basic\FrontController;
use plugin\xypm\app\model\api\Config;
use support\Cache;
use support\Request;
use support\Response;


/**
 * 通用接口
 */
class CommonController extends FrontController
{
	protected $noNeedLogin = ['config','cityData'];
	protected $noNeedRight = ['*'];

	/**
	 * 加载目标信息和通用配置
	 */
	public function config():Response
	{
		$cacheTime = 100;
		// 首页
		$indexPage = (new Page)
			->where(['type'=>'index','is_use'=>1])
			->cache(true, $cacheTime)
			->field('page, item')
			->find();			
		if(!$indexPage){
			return $this->fail(trans('首页模板尚未发布，请到后台【装修管理】中发布模板',[],'common'));
		}

		// 商城
		$shopPage = (new Page)
			->where(['type'=>'shop','is_use'=>1])
			->cache(true, $cacheTime)
			->field('page, item')
			->find();		
			
		//我的
		$userPage = (new Page)
			->where(['type'=>'user','is_use'=>1])
			->cache(true, $cacheTime)
			->field('page, item')
			->find();
		
		$pageData  = [
			"index" => $indexPage,
			"shop"	=> $shopPage,
			"user"	=> $userPage
		];
		return $this->success( 'init',[
			"appStyle" => Config::getValueByName('appstyle'),
			"appConfig" => Config::getValueByName('xypm'),
			"shareConfig" => Config::getValueByName('share'),
			"tabBarList"  => Config::getValueByName('tabbar'),
			"pageData" => $pageData,
		]);
	}

	/**
     * 根据经纬度获取城市数据
     *
     * @param string $lng     经度
     * @param string $lat     纬度
     */
    public function cityData(Request $request): Response
    {
		$lng = $request->get('lng');
		$lat = $request->get('lat');

		// 添加缓存标记，避免重复执行
		$level = 3;
        if (Cache::tag('geo')->get('geo:area_level_' . $level) !== true) {
            self::area2RedisGeo($level);
        }
		return $this->success('城市信息', Area::getCityFromLngLat($lng, $lat));
    }

	/**
     * 区域经纬度到redis
    */
    private function area2RedisGeo($level) {
        $areas = Area::where(['level' => $level])->select();
        $namearr = [1 => 'geo:province', 2 => 'geo:city', 3 => 'geo:district'];
        $redis = Cache::store('redis')->handler();
        foreach ($areas as $area) {
            if (method_exists($redis, 'geoadd')) {
                $redis->geoadd($namearr[$area['level']], $area['lng'], $area['lat'], $area['id']);
            }
        }
        Cache::tag('geo')->set('geo:area_level_' . $level, true);
    }

	/**
	 * 省市区
	 */
	public function area(Request $request): Response
    {
        $data['provinceData'] = Area::where('level', 1)->order('id asc')->field('id as value, name as label, pid, level')->select();
        foreach ($data['provinceData'] as $k => $p) {
            $data['cityData'][$k] = Area::where(['level' => 2, 'pid' => $p->value])->order('id asc')->field('id as value, name as label, pid, level')->select();
            foreach ($data['cityData'][$k] as $i => $c) {
                $data['areaData'][$k][$i] = Area::where(['level' => 3, 'pid' => $c->value])->order('id asc')->field('id as value, name as label, pid, level')->select();
            }
        }

        return $this->success('省市区', $data);
    }
}
