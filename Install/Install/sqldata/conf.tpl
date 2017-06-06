<?php
return array(
	//'配置项'=>'配置值'
    //数据库配置信息
    'DB_TYPE'   => '[DB_TYPE]', // 数据库类型
    'DB_HOST'   => '[DB_HOST]', // 服务器地址
    'DB_NAME'   => '[DB_NAME]', // 数据库名
    'DB_USER'   => '[DB_USER]', // 用户名
    'DB_PWD'    => '[DB_PWD]', // 密码
    'DB_PORT'   => [DB_PORT], // 端口
    'DB_PREFIX' => '[DB_PREFIX]', // 数据库表前缀
    'DB_CHARSET'=> 'utf8', // 字符集
    'AUTH_SUPERADMIN' => '[AUTH_SUPERADMIN]',//超级管理员
    
    // wechat 配置
    'WECHAT' => array(
        'appid' => '',
        'secret' => '',
        //'token' => '',//用不上
    )
);