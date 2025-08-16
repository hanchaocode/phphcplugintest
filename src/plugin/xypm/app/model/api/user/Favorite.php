<?php

namespace plugin\xypm\app\model\api\user;

use plugin\xypm\app\model\api\Goods as GoodsModel;
use plugin\xypm\app\model\api\User;

use think\model\concern\SoftDelete;
use plugin\saiadmin\basic\BaseNormalModel;

class Favorite extends BaseNormalModel
{

    use SoftDelete;

    // 表名
    protected $name = 'xypm_user_favorite';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'integer';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = 'deletetime';

    // 追加属性
    protected $append = [
        'type_text'
    ];
    

    /**
     * 添加｜删除
     */
    public static function add($params)
    {
        extract($params);
        $user = User::info();
        
        $favorite = self::where(['target_id' => $target_id, 'user_id' => $user->id,'type' => $type])->find();
        if ($favorite) {
            $favorite->delete();
            $favorite = null;
        }else{
           $favorite = self::create([
                'user_id' => $user->id,
                'target_id' => $target_id,
                'type'  => $type
            ]);
        }
        return $favorite;
    }

    /**
     * params 请求参数
     */
    public static function getList($params)
    {
        extract($params);
        $user = User::info();
        $list = self::where(['user_id'=>$user->id])->order('id desc')->paginate();

        foreach($list as &$item){
            $target = null;
            if($item['type'] == 'goods'){
                $target = GoodsModel::getDetail($item['target_id']); 
            }
            $item['target'] = $target;
        }

        return $list;
    }

    
    public function getTypeList()
    {
        return ['goods' => trans('Goods',[],'user/favorite')];
    }


    public function getTypeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['type']) ? $data['type'] : '');
        $list = $this->getTypeList();
        return isset($list[$value]) ? $list[$value] : '';
    }



}
