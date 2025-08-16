<?php

namespace plugin\xypm\app\model\api\user;


use plugin\xypm\app\model\api\Bill as BillModel;
use plugin\xypm\app\model\api\Member as MemberModel;
use plugin\xypm\app\model\api\User;
use plugin\xypm\exception\ApiException;
use think\facade\Db;
use plugin\saiadmin\basic\BaseNormalModel;


class Build extends BaseNormalModel
{

    // 表名
    protected $name = 'xypm_user_build';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'integer';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'type_text'
    ];


    /**
     * 户主绑定
     */
    public static function bind($params){
       
        $user = User::info();
        $params['status'] = 0;

        //查询未绑定房产信息
        $memberList = MemberModel::getList($params);

        if(empty($memberList)){
            throw new ApiException('没有查询到未绑定房产');
        }

        $bind = Db::transaction(function () use ($user, $memberList) {
            
            foreach($memberList as $member){
                //改变绑定状态
                $member->status = '1';
                $member->user_id = $user->id;
                $member->save();

                //插入用户房产数据
                (new Build())->save(['user_id'=>$user->id,'member_id'=>$member['id'],'build_id'=>$member['build_id'],'type'=>$member['buildtype']]);
            }

            return true;
        });

        return $bind;

    }

    /**
     * 详情
     */
    public static function getDetail($id)
    {
        $user = User::info();
        return self::with(['member'])->where(['id'=>$id,'user_id'=>$user->id])->find();
    }

    /**
     * 列表
     */
    public static function getList($params)
    {
        extract($params);
        $user = User::info();

        $where = ['user_id'=>$user->id];

        if(isset($type) && $type != 'all'){
            $where['type'] = $type;
        }

        $order = 'is_default desc,id desc';

        $list = self::with(['member'])->where($where)->order($order);

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
     * 待缴账单
     */
    public static function getBillList($params)
    {
        extract($params);
        $user = User::info();

        $where = ['user_id'=>$user->id];
      
        $order = 'is_default desc,id desc';

        $list = self::with(['member'])->where($where)->order($order)->paginate();

        $data = $list->items();

        foreach($data as &$item){
            $money = BillModel::getPayMoney($item['build_id'],$item['type']);
            $item['pay_money'] = $money;
        }
        $list->data = $data;

        return $list;
    }

    /**
     * 删除
     */
    public static function del($id)
    {
        $user = User::info();
        return Db::transaction(function () use ($user, $id) {

            $userBuild = self::get(['id' => $id, 'user_id' => $user->id]);
            $member = MemberModel::get($userBuild['member_id']);
            $member->user_id = 0;
            $member->status = 0;
            $member->save();
            $userBuild->delete();
            return true;
        });
    }

    /**
     * 设置默认
     */
    public static function setDefault($id){
        $user = User::info();
        return Db::transaction(function () use ($user, $id) {
            self::where(['user_id' => $user->id, 'is_default' => '1'])->update(['is_default' => '0']);
            self::where(['user_id' => $user->id, 'id' => $id])->update(['is_default' => '1']);
            return true;
        });
    }

    
    public function getTypeList()
    {
        return ['house' => trans('房屋',[],'user/build'), 'shop' => trans('商铺',[],'user/build'), 'parking' => trans('车位',[],'user/build'), 'garage' => trans('储物间',[],'user/build')];
    }

    public function getTypeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['type']) ? $data['type'] : '');
        $list = $this->getTypeList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function member()
    {
        return $this->belongsTo('\plugin\xypm\app\model\api\Member', 'member_id', 'id', [], 'LEFT');
    }



}
