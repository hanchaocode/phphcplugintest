<?php

namespace plugin\xypm\app\model\api;




class Article extends BaseNormalModel
{

    // 表名
    protected $name = 'xypm_article';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'integer';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';

    // 追加属性
    protected $append = [
        'status_text',
    ];


    public static function getDetail($id)
    {

        $detail = (new self)->where('id', $id)->find();

        if (!$detail || $detail->status == 'hidden') {
            return null;
        }
        
        return $detail;
    }
    

    public function getImageAttr($value, $data)
    {
        if (!empty($value)) return cdnurl($value, true);

    }
    

    public function getStatusList()
    {
        return ['normal' => trans('Normal',[],'article'),  'hidden' => trans('Hidden',[],'article')];
    }
    

    public function getContentAttr($value, $data)
    {
        $content = $data['content'];
        $pattern = '/style="(.*?)"/i';
        $replacement = '';
        $content = preg_replace($pattern, $replacement, $content);
        $content = str_replace("<img src=\"/uploads", "<img src=\"" . cdnurl("/uploads", true), $content);
        $content = str_replace("<video src=\"/uploads", "<video src=\"" . cdnurl("/uploads", true), $content);
        $content = str_replace("<img src=\"/assets", "<img src=\"" . cdnurl("/assets", true), $content);
        $content = str_replace("<video src=\"/assets", "<video src=\"" . cdnurl("/assets", true), $content);
        return $content;
    }

    public function getStatusTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['status']) ? $data['status'] : '');
        $list = $this->getStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }



}
