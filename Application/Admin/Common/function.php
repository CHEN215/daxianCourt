<?php

function sort_cates($cates, $pid = 0, $str = "根节点")
{
    static $_cates = array();
    foreach ($cates as $k => $v) {
        if ($v['pid'] == $pid) {
            $v['parent'] = $str; // 添加父节点 名
            $_cates[] = $v;
            unset($cates[$k]);
            array_merge($_cates, sort_cates($cates, $v['id'], $v['name']));
        }
    }
    return $_cates;
}

/**
 * 格式化多级分类，格式如下
 * 父栏目
 * ---子栏目
 * ---子栏目
 * 父栏目
 * ---子栏目
 * ...
 * 
 * @param array $cates            
 * @param string $falg            
 */
function sty_cates($cates = array(),$pid = 0, $falg = '---')
{
    $arr = array();
    foreach ($cates as $k => $v) {
        if($v['pid']==$pid){
            if($pid != 0) $v['name'] = $falg.$v['name'];
            $arr[] = $v;
            unset($cates[$k]);
            $arr = array_merge($arr,sty_cates($cates,$v['id']));
        }
    }
    return $arr;
}