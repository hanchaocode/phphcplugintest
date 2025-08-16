<?php
// +----------------------------------------------------------------------
// | saiadmin [ saiadmin快速开发框架 ]
// +----------------------------------------------------------------------
// | Author: your name
// +----------------------------------------------------------------------
namespace plugin\xypm\app\logic;


use plugin\saiadmin\basic\BaseLogic;
use plugin\xypm\app\model\admin\Config;


/**
 *配置管理逻辑层
 */
class ConfigLogic extends BaseLogic
{

    /**
     * 构造函数
     */
    public function __construct()
    {
        $this->model = new Config();

    }



    /**
     * 读取分类分组列表
     * @return array
     */
    public  function getGroupList()
    {
        $group = $this->model->group('group')->column('group');
        $groupList = [];
        foreach ($group as $v) {
            $groupList[$v] = ($v);
        }

        return $groupList;
    }

    /**
     * 读取配置类型
     * @return array
     */
    public static function getTypeList()
    {
        $typeList = [
            'string'   => ('String'),
            'text'     => ('Text'),
            'editor'   => ('Editor'),
            'number'   => ('Number'),
            'date'     => ('Date'),
            'time'     => ('Time'),
            'datetime' => ('Datetime'),
            'select'   => ('Select'),
            'selects'  => ('Selects'),
            'image'    => ('Image'),
            'images'   => ('Images'),
            'file'     => ('File'),
            'files'    => ('Files'),
            'switch'   => ('Switch'),
            'checkbox' => ('Checkbox'),
            'radio'    => ('Radio'),
            'array'    => ('Array'),
            'custom'   => ('Custom'),
        ];
        return $typeList;
    }

    public static function getRegexList()
    {
        $regexList = [
            'required' => '必选',
            'digits'   => '数字',
            'letters'  => '字母',
            'date'     => '日期',
            'time'     => '时间',
            'email'    => '邮箱',
            'url'      => '网址',
            'qq'       => 'QQ号',
            'IDcard'   => '身份证',
            'tel'      => '座机电话',
            'mobile'   => '手机号',
            'zipcode'  => '邮编',
            'chinese'  => '中文',
            'username' => '用户名',
            'password' => '密码'
        ];
        return $regexList;
    }
}
