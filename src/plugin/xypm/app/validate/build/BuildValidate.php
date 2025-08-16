<?php
// +----------------------------------------------------------------------
// | saiadmin [ saiadmin快速开发框架 ]
// +----------------------------------------------------------------------
// | Author: your name
// +----------------------------------------------------------------------
namespace plugin\saisms\app\validate;

use think\Validate;

/**
 * 验证器
 */
class BuildValidate extends Validate
{
    /**
     * 定义验证规则
     */
    protected $rule =   [
        'name' => 'require',
        'mobile' => 'require',
    ];

    /**
     * 定义错误信息
     */
    protected $message  =   [
        'name' => '名称必须填写',

    ];

    /**
     * 定义场景
     */
    protected $scene = [
        'save' => [
            'gateway',
            'mobile',
        ],
        'update' => [
            'gateway',
            'mobile',
        ],
    ];

}
