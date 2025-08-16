<?php

namespace plugin\xypm\app\model\api;

use plugin\saiadmin\basic\BaseNormalModel;
use think\model\concern\SoftDelete;



class Suggest extends BaseNormalModel
{

    use SoftDelete;

    

    // 表名
    protected $name = 'xypm_suggest';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'integer';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = 'deletetime';

    // 追加属性
    protected $append = [
        'status_text'
    ];

    /**
     * 列表
     */
    public static function getList($params)
    {
        extract($params);
        $user = User::info();

        $where = ['user_id'=>$user->id];

        if(isset($status) && $status != 'all'){
            $where['status'] = $status;
        }

        $order = 'id desc';

        $list = self::where($where)->order($order);

        if (isset($page)) {
            $list = $list->paginate();
        } else {
            if(isset($limit)){
                $list = $list->limit($limit)->select();
            }else{
                $list = $list->select();
            }
        }

        return $list;
    }
    

     /**
     * 添加
     */
    public static function add($params)
    {
        $user = User::info();
        $params['user_id'] = $user->id;
        return self::create($params);
    }
    
    public function getImagesAttr($value, $data)
    {
        $imagesArray = [];
        if (!empty($value)) {
            $imagesArray = explode(',', $value);
            foreach ($imagesArray as &$v) {
                $v = cdnurl($v, true);
            }
            return $imagesArray;
        }
        return $imagesArray;
    }
    
    public function getStatusList()
    {
        return ['0' => trans('待处理',[],'suggest'), '1' => trans('处理中',[],'suggest'), '2' => trans('已处理',[],'suggest')];
    }


    public function getStatusTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['status']) ? $data['status'] : '');
        $list = $this->getStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }




}
