<?php

namespace plugin\xypm\app\model\api;


use think\model\concern\SoftDelete;
use plugin\saiadmin\basic\BaseNormalModel;

class Notice extends BaseNormalModel
{

    use SoftDelete;

    // 表名
    protected $name = 'xypm_notice';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'integer';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = 'deletetime';

    // 追加属性
    protected $append = [
        'status_text'
    ];

    /**
     * 详情
     */
    public static function getDetail($id)
    {
        $detail = (new self)->where('id', $id)->find();
        if (!$detail || $detail->status == 'hidden') {
            return null;
        }
        return $detail;
    }

    /**
     * 列表
     */
    public static function getList($params)
    {
        extract($params);
        $where = [
            'status' => 'normal'
        ];

        $order = 'weigh desc,id desc';

        if (isset($ids) && $ids !== '') {
            $idsArray = explode(',', $ids);
            $where['id'] = ['in', $idsArray];
        }

        if (isset($search) && $search !== '') {
            $where['title'] = ['like', "%$search%"];
        }

        $notice = self::where($where)->order($order);

        if (isset($page)) {
            $notice = $notice->paginate();
        } else {
            if(isset($limit)){
                $notice = $notice->limit($limit)->select();
            }else{
                $notice = $notice->select();
            }
        }

        return $notice;
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



    public static function onBeforeInsert($model)
    {
        $info = getCurrentInfo();
        $info && $model->setAttr('weigh', $info['id']);
    }
    
    public function getStatusList()
    {
        return ['normal' => trans('Status normal',[],'notice'), 'hidden' => trans('Status hidden',[],'notice')];
    }


    public function getStatusTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['status']) ? $data['status'] : '');
        $list = $this->getStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }


}