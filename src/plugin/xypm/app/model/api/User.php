<?php

namespace plugin\xypm\app\model\api;



use plugin\xypm\app\model\api\user\Money as UserMoneyModel;


use plugin\saiadmin\basic\BaseNormalModel;
use plugin\xypm\exception\ApiException;
use plugin\xypm\utils\Auth;

/**
 * 会员模型
 */
class User extends BaseNormalModel

{
    protected $name = 'xypm_user';
    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';


    public static function info($token='')
    {

        $token = request()->header('token');
        $auth =Auth::instance($token);
        if ($auth->isLogin()) {

            $user= $auth->getUser();

            return $user;
        }


//        $token = getCurrentInfo();
//        if(empty($token)){
//            return null;
//        };
//
//        if(!isset($token['id'])){
//            return null;
//        }
//        $user = (new self())->where('id',$token['id'])->find();
//        if(empty($user)){
//            return null;
//        }
//        return $user;
        return null;
    }

    /**
     * 获取头像
     * @param   string $value
     * @param   array  $data
     * @return string
     */
    public function getAvatarAttr($value, $data)
    {
        if (!$value) {
            $config = Config::getValueByName('xypm');
            $value = $config['useravatar'];
        }

        return cdnurl($value, true);
    }

    /**
     * 变更会员余额
     * @param int    $money   余额
     * @param int    $user_id 会员ID
     * @param string $memo    备注
     */
    public static function money($money, $user_id, $type,$remark = '',$sid = 0)
    {

        // 判断金额
        if ($money == 0) {
            throw new ApiException('请输入正确的金额');
        }

        $user = self::lock(true)->find($user_id);
        $before = $user->money;
        $after = function_exists('bcadd') ? bcadd($user->money, $money, 2) : $user->money + $money;

        // 只有后台操作，余额才可以是负值
        if ($after < 0 && !in_array($type, ['sys'])) {
            throw new ApiException('可用余额不足');
        }

        //更新会员信息
        $user->save(['money' => $after]);
        
        //写入日志
        UserMoneyModel::create(['user_id' => $user_id,'type'=>$type, 'money' => $money, 'before' => $before, 'after' => $after,'type'=>$type, 'remark' => $remark,'service_id'=>$sid]);
   
        return true;
    }
    
    /**
     * 获取验证字段数组值
     * @param   string $value
     * @param   array  $data
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
    
   
}
