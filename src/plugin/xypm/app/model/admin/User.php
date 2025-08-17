<?php

namespace plugin\xypm\app\model\admin;

use app\admin\model\UserGroup;
use plugin\saiadmin\basic\BaseNormalModel;
use think\Model;

use function plugin\xypm\app\model\letter_avatar;

/**
 * 会员模型
 */
class User extends BaseNormalModel
{


    protected $name = 'xypm_user';
    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    // 追加属性
    protected $append = [
        'url',
        'status_text',
    ];

    /**
     * 获取个人URL
     * @param string $value
     * @param array  $data
     * @return string
     */
    public function getUrlAttr($value, $data)
    {
        return "/u/" . $data['id'];
    }

    /**
     * 获取头像
     * @param string $value
     * @param array  $data
     * @return string
     */
    public function getAvatarAttr($value, $data)
    {
        if (!$value) {
            //如果不需要启用首字母头像，请使用

//            $value = letter_avatar($data['nickname']);
            $value ='U';
        }
        return $value;
    }

    public function searchMobileAttr($query, $value)
    {
        $query->where('mobile', 'like', '%'.$value.'%');
    }

    public function getStatusList()
    {
        return ['normal' => trans('Normal',[],'common'),'hidden' => trans('Hidden',[],'common')];
    }


    public function getStatusTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['status']) ? $data['status'] : '');
        $list = $this->getStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }

    /**
     * 获取验证字段数组值
     * @param string $value
     * @param array  $data
     * @return  object
     */
    public function getVerificationAttr($value, $data)
    {
        $value = array_filter((array)json_decode($value, true));
        $value = array_merge(['email' => 0, 'mobile' => 0], $value);
        return (object)$value;
    }

    /**
     * 设置验证字段
     * @param mixed $value
     * @return string
     */
    public function setVerificationAttr($value)
    {
        $value = is_object($value) || is_array($value) ? json_encode($value) : $value;
        return $value;
    }

//    public function group()
//    {
//        return $this->belongsTo('app\admin\model\UserGroup', 'group_id', 'id', [], 'LEFT');
//    }

    public function third()
    {
        return $this->belongsTo('plugin\xypm\app\model\admin\Third', 'id', 'user_id', [], 'LEFT');
    }
}
