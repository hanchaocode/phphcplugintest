<?php

use plugin\saiadmin\app\middleware\SystemLog;
use plugin\xypm\app\middleware\CheckAuth;
use plugin\xypm\app\middleware\CheckLogin;

return [
    '' => [
        CheckLogin::class,
        CheckAuth::class,
        SystemLog::class,
    ]
];
