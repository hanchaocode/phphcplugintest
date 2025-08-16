<?php

namespace plugin\xypm\utils;

use plugin\xypm\app\model\api\Token;
use plugin\xypm\app\model\api\User;
use support\Cache;
use think\facade\Db;



class Auth
{
    protected static $instance = null;
    protected $_error = '';
    protected $_logined = false;
    protected $id = null;
    protected $_user = null;
    protected $_token = '';
    protected $keeptime = 2592000;
    protected $requestUri = '';
    protected $rules = [];
    protected $config = [];
    protected $options = [];
    protected $allowFields = ['id', 'username', 'nickname', 'mobile', 'avatar', 'score'];

    public function __construct($options = [], $token = null)
    {
        $this->options = array_merge($this->config, $options);

        // 在构造函数中调用 init
        if ($token !== null) {
            $this->init($token);
        }
    }

    public static function instance($options = [], $token = null)
    {
        if (is_null(self::$instance)) {
            self::$instance = new static($options, $token);
        }

        return self::$instance;
    }

    public function getUser()
    {
        return $this->_user;
    }

    public function __get($name)
    {
        return $this->_user ? $this->_user->$name : null;
    }

    public function __isset($name)
    {
        return isset($this->_user) ? isset($this->_user->$name) : false;
    }

    public function init($token)
    {
        if ($this->_logined) {
            return true;
        }
        if ($this->_error) {
            return false;
        }
        $user_id = Cache::get($token);
        if (!$user_id) {
            return false;
        }
        $user_id = intval($user_id);
        if ($user_id > 0) {
            $user = User::find($user_id);
            if (!$user) {
                $this->setError('账号不存在');
                return false;
            }
            if ($user->status != 'normal') {
                $this->setError('账号已锁定');
                return false;
            }
            $this->_user = $user;
            $this->_logined = true;
            $this->_token = $token;

            // 在Webman中处理事件，自定义事件机制
            $this->dispatchEvent("user_init_successed", $this->_user);

            return true;
        } else {
            $this->setError('您未登录');
            return false;
        }
    }

    public function register($username, $password, $email = '', $mobile = '', $extend = [])
    {
        if (User::where('username', $username)->find()) {
            $this->setError('用户名已存在');
            return false;
        }
        if ($email && User::where('email', $email)->find()) {
            $this->setError('邮箱已存在');
            return false;
        }
        if ($mobile && User::where('mobile', $mobile)->find()) {
            $this->setError('手机已存在');
            return false;
        }

        $ip = request()->getRealIp();
        $time = time();

        $data = [
            'username' => $username,
            'password' => $password,
            'email'    => $email,
            'mobile'   => $mobile,
            'level'    => 1,
            'score'    => 0,
            'avatar'   => '',
        ];
        $params = array_merge($data, [
            'nickname'  => preg_match("/^1[3-9]{1}\d{9}$/", $username) ? substr_replace($username, '****', 3, 4) : $username,
            'salt'      => Random::alnum(),
            'jointime'  => $time,
            'joinip'    => $ip,
            'logintime' => $time,
            'loginip'   => $ip,
            'prevtime'  => $time,
            'status'    => 'normal'
        ]);
        $params['password'] = $this->getEncryptPassword($password, $params['salt']);
        $params = array_merge($params, $extend);

        return Db::transaction(function () use ($params, $data) {
            try {
                $user = User::create($params);

                $this->_user = User::find($user->id);

                $this->_token = Random::uuid();
                Token::create([
                    'token'=>$this->_token,
                    'user_id'=>$user->id,

                ]);
                Cache::set($this->_token, $user->id, $this->keeptime);

                $this->_logined = true;

                $this->dispatchEvent("user_register_successed", $this->_user, $data);

                return true;
            } catch (\Exception $e) {
                $this->setError($e->getMessage());
                return false;
            }
        });
    }


    public function login($account, $password)
    {
        $field = filter_var($account, FILTER_VALIDATE_EMAIL) ? 'email' : (preg_match('/^1\d{10}$/', $account) ? 'mobile' : 'username');
        $user = User::where($field, $account)->first();
        if (!$user) {
            $this->setError('Account is incorrect');
            return false;
        }

        if ($user->status != 'normal') {
            $this->setError('Account is locked');
            return false;
        }

        if ($user->loginfailure >= 10 && time() - $user->loginfailuretime < 86400) {
            $this->setError('Please try again after 1 day');
            return false;
        }

        if ($user->password != $this->getEncryptPassword($password, $user->salt)) {
            $user->increment('loginfailure');
            $user->loginfailuretime = time();
            $user->save();
            $this->setError('Password is incorrect');
            return false;
        }

        return $this->direct($user->id);
    }

    public function logout()
    {
        if (!$this->_logined) {
            $this->setError('You are not logged in');
            return false;
        }
        $this->_logined = false;
        (new Token)->where('token',$this->_token)->delete();
        $this->dispatchEvent("user_logout_successed", $this->_user);
        return true;
    }

    public function changepwd($newpassword, $oldpassword = '', $ignoreoldpassword = false)
    {
        if (!$this->_logined) {
            $this->setError('You are not logged in');
            return false;
        }

        $isPasswordCorrect = $this->_user->password == $this->getEncryptPassword($oldpassword, $this->_user->salt) || $ignoreoldpassword;

        if ($isPasswordCorrect) {
            return Db::transaction(function () use ($newpassword) {
                try {
                    $salt = Random::alnum();
                    $newpassword = $this->getEncryptPassword($newpassword, $salt);
                    $this->_user->update(['loginfailure' => 0, 'password' => $newpassword, 'salt' => $salt]);

                    Token::where('token', $this->_token)->delete();
                    $this->dispatchEvent("user_changepwd_successed", $this->_user);

                    return true;
                } catch (\Exception $e) {
                    $this->setError($e->getMessage());
                    return false;
                }
            });
        } else {
            $this->setError('Password is incorrect');
            return false;
        }
    }

    public function direct($user_id)
    {
        $user = User::find($user_id);
        if ($user) {
            return Db::transaction(function () use ($user) {
                try {
                    $ip = request()->getRealIp();
                    $time = time();

                    if ($user->logintime < strtotime('today')) {
                        $user->successions = $user->logintime < strtotime('yesterday') ? 1 : $user->successions + 1;
                        $user->maxsuccessions = max($user->successions, $user->maxsuccessions);
                    }

                    $user->prevtime = $user->logintime;
                    $user->loginip = $ip;
                    $user->logintime = $time;
                    $user->loginfailure = 0;

                    $user->save();

                    $this->_user = $user;

                    $this->_token = Random::uuid();

                    Cache::set($this->_token, $user->id, $this->keeptime);

                    $this->_logined = true;

                    $this->dispatchEvent("user_login_successed", $this->_user);

                    return true;
                } catch (\Exception $e) {
                    $this->setError($e->getMessage());
                    return false;
                }
            });
        } else {
            return false;
        }
    }

    public function isLogin()
    {
        return $this->_logined;
    }

    public function getToken()
    {
        return $this->_token;
    }

    public function getUserinfo()
    {
        $data = $this->_user->toArray();
        $allowFields = $this->getAllowFields();
        $userinfo = array_intersect_key($data, array_flip($allowFields));
//        $userinfo = array_merge($userinfo, Token::where('token',$this->_token)->find());
        $userinfo = array_merge($userinfo, ['token'=>$this->_token]);
        return $userinfo;
    }


    public function getRequestUri()
    {
        return $this->requestUri;
    }

    public function setRequestUri($uri)
    {
        $this->requestUri = $uri;
    }

    public function getAllowFields()
    {
        return $this->allowFields;
    }

    public function setAllowFields($fields)
    {
        $this->allowFields = $fields;
    }

    public function delete($user_id)
    {
        $user = User::find($user_id);
        if (!$user) {
            return false;
        }
        Db::beginTransaction();
        try {
            User::destroy($user_id);
            Token::clear($user_id);

            $this->dispatchEvent("user_delete_successed", $user);
            Db::commit();
        } catch (\Exception $e) {
            Db::rollBack();
            $this->setError($e->getMessage());
            return false;
        }
        return true;
    }

    public function getEncryptPassword($password, $salt = '')
    {
        return md5(md5($password) . $salt);
    }

    public function match($arr = [])
    {
        $request = request();
        $arr = is_array($arr) ? $arr : explode(',', $arr);
        if (!$arr) {
            return false;
        }
        $arr = array_map('strtolower', $arr);
        if (in_array(strtolower($request->action()), $arr) || in_array('*', $arr)) {
            return true;
        }

        return false;
    }

    public function keeptime($keeptime = 0)
    {
        $this->keeptime = $keeptime;
    }

    public function render(&$datalist, $fields = [], $fieldkey = 'user_id', $renderkey = 'userinfo')
    {
        $fields = !$fields ? ['id', 'nickname', 'level', 'avatar'] : (is_array($fields) ? $fields : explode(',', $fields));
        $ids = [];
        foreach ($datalist as $item) {
            if (!isset($item[$fieldkey])) {
                continue;
            }
            $ids[] = $item[$fieldkey];
        }
        $list = [];
        if ($ids) {
            if (!in_array('id', $fields)) {
                $fields[] = 'id';
            }
            $ids = array_unique($ids);
            $selectlist = User::whereIn('id', $ids)->get($fields);
            foreach ($selectlist as $user) {
                $list[$user->id] = $user;
            }
        }
        foreach ($datalist as &$item) {
            $item[$renderkey] = $list[$item[$fieldkey]] ?? null;
        }
        return $datalist;
    }

    public function setError($error)
    {
        $this->_error = $error;
        return $this;
    }

    public function getError()
    {
        return $this->_error ?: '';
    }

    protected function dispatchEvent($event, ...$args)
    {
        // Implement event dispatch logic suitable for Webman, if needed
    }
}
