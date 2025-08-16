<?php

namespace plugin\xypm\app\model\api;

use plugin\xypm\exception\ApiException;
use think\facade\Db;
use plugin\saiadmin\basic\BaseNormalModel;

class Member extends BaseNormalModel
{
    

    // 表名
    protected $name = 'xypm_member';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'integer';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'buildtype_text',
        'identity_text',
        'gender_text',
        'status_text'
    ];

    /**
     * 列表
     */
    public static function getList($params)
    {
        extract($params);
        $where = [];

        $order = 'weigh desc,id desc';

        if (isset($user_id) && $user_id !== '') {
            $where['user_id'] = $user_id;
        }

        if (isset($realname) && $realname !== '') {
            $where['realname'] = $realname;
        }

        if (isset($status) && $status !== '') {
            $where['status'] = $status;
        }

        if (isset($mobile) && $mobile !== '') {
            $where['mobile'] = $mobile;
        }

        if (isset($identity) && $identity !== '') {
            $where['identity'] = $identity;
        }

        if (isset($pid) && $pid !== '') {
            $where['pid'] = $pid;
        }

        $member = self::where($where)->order($order);

        if (isset($page)) {
            $member = $member->paginate();
        } else {
            if(isset($limit)){
                $member = $member->limit($limit)->select();
            }else{
                $member = $member->select();
            }
        }

        return $member;
    }

    /**
     * 删除
     */
    public static function del($id)
    {
        $user = User::info();
        $member = self::find($id);
        if(empty($member)){
            throw new ApiException('成员不存在');
        }
        if($member['status'] == 1){
            throw new ApiException('已绑定会员不能删除');
        }

        $pMember = self::find($member['pid']);
        if (empty($pMember)) {
            throw new ApiException('户主未找到');
        }
        if($pMember && $pMember['user_id'] != $user->id ){
            throw new ApiException('您没有权限删除');
        }

        return Db::transaction(function () use ($member,$pMember) {

            $member->delete();
            $pMember->setDec('mnums');

            return true;
        });
    }

    /**
    * 添加成员
    */
    public static function add($params)
    {

        $user = User::info();
        extract($params);

        $member = self::where(['user_id'=>$user->id,'id'=>$member_id])->find();
        
        if(!$member){
            throw new ApiException('您不是业主！');
        }

        $tempMember = self::where(['build_id'=>$member['build_id'],'buildtype'=>$member['buildtype'],'realname'=>$realname,'mobile'=>$mobile])->find();

        if(!empty($tempMember)){
            throw new ApiException('成员已存在！');
        }

        return Db::transaction(function () use ($member,$params,$user) {
            $member->setInc('mnums');
            self::create([
                    'build_id' => $member['build_id'],
                    'buildname' => $member['buildname'],
                    'buildtype' => $member['buildtype'],
                    'identity' => $params['identity'],
                    'realname' => $params['realname'],
                    'pid' => $member['id'],
                    'mobile' => $params['mobile'],
                    'buildarea' => $member['buildarea'],
                ]
            );
            return true;
        });


    }



    public static function onBeforeInsert($model)
    {
        $info = getCurrentInfo();
        $info && $model->setAttr('weigh', $info['id']);
    }

    
    public function getBuildTypeList()
    {
        return ['house' => trans('房屋',[],'member'), 'shop' => trans('商铺',[],'member'), ' parking' => trans('buildtype  parking',[],'member'), 'garage' => trans('buildtype garage',[],'member')];
    }

    public function getIdentityList()
    {
        return ['1' => trans('户主',[],'member'), '2' => trans('家人',[],'member'), '3' => trans('租户',[],'member')];
    }

    public function getGenderList()
    {
        return ['male' => trans('Gender male',[],'member'), 'female' => trans('Gender female',[],'member')];
    }

    public function getStatusList()
    {
        return ['1' => trans('已绑定会员',[],'member'), '0' => trans('未绑定会员',[],'member')];
    }


    public function getBuildTypeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['buildtype']) ? $data['buildtype'] : '');
        $list = $this->getBuildTypeList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getIdentityTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['identity']) ? $data['identity'] : '');
        $list = $this->getIdentityList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getGenderTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['gender']) ? $data['gender'] : '');
        $list = $this->getGenderList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getStatusTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['status']) ? $data['status'] : '');
        $list = $this->getStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }




}
