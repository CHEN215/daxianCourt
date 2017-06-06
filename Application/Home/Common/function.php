<?php

/**
 * 过滤html标签，再截取字符串
 * @param unknown $str
 * @param unknown $start
 * @param unknown $len
 */
function strip_tags_substr($str, $start, $len)
{
    return mb_substr(strip_tags($str), $start, $len,'UTF-8');
}