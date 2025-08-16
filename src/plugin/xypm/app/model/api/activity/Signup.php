<?php

namespace plugin\xypm\app\model\api\activity;


use plugin\saiadmin\basic\BaseNormalModel;
use plugin\xypm\app\model\api\Activity as ActivityModel;
use plugin\xypm\app\model\api\User;
use plugin\xypm\exception\ApiException;
use think\facade\Db;


class Signup extends BaseNormalModel
{
    

    // 表名
    protected $name = 'xypm_activity_signup';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'integer';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = false;

    // 追加属性
    protected $append = [

    ];

    
    /**
     * 列表
     */
    public static function getList($params)
    {
        extract($params);
        $user = User::info();

        $where = ['user_id'=>$user->id];

        $order = 'id desc';

        $list = self::with(['activity'])->where($where)->order($order);

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
     * 报名
     */
    public static function add($params)
    {

        $activity = ActivityModel::getDetail($params['activity_id']);
        
        if(!$activity){
            throw new ApiException('活动不存在或已下架！');
        }

        if($activity['number'] == $activity['signup_num']){
            throw new ApiException('报名人数已满！');
        }

        $user = User::info();

        return Db::transaction(function () use ($user,$params,$activity) {
            
            self::create(['activity_id'=>$params['activity_id'],'user_id'=>$user->id,'realname'=>$params['realname'],'mobile'=>$params['mobile'],'buildname'=>$params['buildname']]);
            $activity->setInc('signup_num');
            return true;
        });


    }


    public static function getDetail($id)
    {

        $detail = (new self)->where('id', $id)->find();

        if (!$detail || $detail->status == 'hidden') {
            return null;
        }

        return $detail;
    }

    public function activity()
    {
        return $this->belongsTo('\plugin\xypm\app\model\api\Activity', 'activity_id', 'id', [], 'LEFT');
    }


}
