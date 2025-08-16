<?php

namespace plugin\xypm\app\model\api\user;

use plugin\xypm\app\model\api\User;
use plugin\saiadmin\basic\BaseNormalModel;


class Money extends BaseNormalModel
{
    

    // 表名
    protected $name = 'xypm_user_money';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'integer';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';

    // 追加属性
    protected $append = [
        'type_text'
    ];
    

    
    public function getTypeList()
    {
        return ['sys' => trans('后台操作',[],'user/money'), 'pay_goods' => trans('购买商品',[],'user/money'),'pay_bill' => trans('交物业费',[],'user/money'),'recharge' => trans('充值',[],'user/money')];
    }


    public function getTypeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['type']) ? $data['type'] : '');
        $list = $this->getTypeList();
        return isset($list[$value]) ? $list[$value] : '';
    }
    

    /**
     * 列表
     */
    public static function getList($params)
    {
        extract($params);
        $user = User::info();
        $list = self::where(['user_id'=>$user->id])->order('id desc')->paginate();
        return $list;
    }


}
