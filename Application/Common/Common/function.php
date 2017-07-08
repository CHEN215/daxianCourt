<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/8
 * Time: 22:33
 */
function arr2tree($cates, $pid = 0)
{
    $_cates = array();
    foreach ($cates as $k => $v) {
        if ($v['pid'] == $pid) {
            $v['child'] = arr2tree($cates,$v['id']);
            $_cates[] = $v;
            unset($cates[$k]);
        }
    }
    return $_cates;
}