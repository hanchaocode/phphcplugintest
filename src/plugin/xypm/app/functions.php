<?php
/**
 * Here is your custom functions.
 */
use plugin\saiadmin\utils\Arr;


if (!function_exists('letter_avatar')) {
    /**
     * 首字母头像
     * @param $text
     * @return string
     */
    function letter_avatar($text)
    {
        $total = unpack('L', hash('adler32', $text, true))[1];
        $hue = $total % 360;
        list($r, $g, $b) = hsv2rgb($hue / 360, 0.3, 0.9);

        $bg = "rgb({$r},{$g},{$b})";
        $color = "#ffffff";
        $first = mb_strtoupper(mb_substr($text, 0, 1));
        $src = base64_encode('<svg xmlns="http://www.w3.org/2000/svg" version="1.1" height="100" width="100"><rect fill="' . $bg . '" x="0" y="0" width="100" height="100"></rect><text x="50" y="50" font-size="50" text-copy="fast" fill="' . $color . '" text-anchor="middle" text-rights="admin" dominant-baseline="central">' . $first . '</text></svg>');
        $value = 'data:image/svg+xml;base64,' . $src;
        return $value;
    }
}

/**
 * 过滤掉字符串中的 sql 关键字
 */
if (!function_exists('xypmFilterSql')) {

}

/**
 * 生成订单号
 * @return string
 */
if (!function_exists('xypmCreateOrderNo')) {
    function xypmCreateOrderNo()
    {
        return date('Ymd') . substr(implode('', array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
    }
}

//if (!function_exists('cdnurl')) {
//
//    /**
//     * 获取上传资源的CDN的地址
//     * @param string  $url    资源相对地址
//     * @param boolean $domain 是否显示域名 或者直接传入域名
//     * @return string
//     */
//    function cdnurl($url, $domain = false)
//    {
//        $regex = "/^((?:[a-z]+:)?\/\/|data:image\/)(.*)/i";
//        $cdnurl = \think\Config::get('upload.cdnurl');
//        if (is_bool($domain) || stripos($cdnurl, '/') === 0) {
//            $url = preg_match($regex, $url) || ($cdnurl && stripos($url, $cdnurl) === 0) ? $url : $cdnurl . $url;
//        }
//        if ($domain && !preg_match($regex, $url)) {
//            $domain = is_bool($domain) ? request()->domain() : $domain;
//            $url = $domain . $url;
//        }
//        return $url;
//    }
//}

if (!function_exists('cdnurl')) {
    /**
     * 获取CDN地址
     * @param string|null $url 资源路径（可选）
     * @param bool $withDomain 是否包含域名（当$url存在时有效）
     * @return string
     */
    function cdnurl(string $url = null, bool $withDomain = true)
    {
        $logic = new plugin\saiadmin\app\logic\system\SystemConfigLogic();
        $uploadConfig = $logic->getGroup('upload_config');

        // 获取当前存储模式的域名
        $type = Arr::getConfigValue($uploadConfig, 'upload_mode');
        switch ($type) {
            case 1: $domain = Arr::getConfigValue($uploadConfig, 'local_domain'); break;
            case 2: $domain = Arr::getConfigValue($uploadConfig, 'oss_domain'); break;
            case 3: $domain = Arr::getConfigValue($uploadConfig, 'qiniu_domain'); break;
            case 4: $domain = Arr::getConfigValue($uploadConfig, 'cos_domain'); break;
            case 5: $domain = Arr::getConfigValue($uploadConfig, 's3_domain'); break;
            default: $domain = Arr::getConfigValue($uploadConfig, 'local_domain');
        }

        // 如果没有传url，直接返回域名
        if (is_null($url)) {
            return $domain;
        }

        // 如果已经是完整URL，直接返回
        if (preg_match("/^((?:[a-z]+:)?\/\/|data:image\/)/i", $url)) {
            return $url;
        }

        // 返回组合后的URL或纯路径
        return $withDomain ? $domain . $url : $url;
    }
}



/**
 * 生成订单号
 * @return string
 */
if (!function_exists('xypmCreateOrderNo')) {
    function xypmCreateOrderNo()
    {
        return date('Ymd') . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
    }
}

if (!function_exists('randomAlnum')) {
    function randomAlnum($length = 16) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomIndex = mt_rand(0, $charactersLength - 1);
            $randomString .= $characters[$randomIndex];
        }
        return $randomString;
    }

}