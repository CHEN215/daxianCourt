<?php
date_default_timezone_set('Asia/Shanghai');
define('ROOT', __DIR__.'/');

include ROOT.'ThinkPHP/Common/functions.php';
include ROOT.'ThinkPHP/Library/Think/Db/Driver.class.php';
include ROOT.'ThinkPHP/Library/Think/Db/Driver/Mysql.class.php';
include ROOT.'ThinkPHP/Library/Org/OT/MyDatabase.class.php';
include ROOT.'ThinkPHP/Library/Think/Db.class.php';
$config = include ROOT.'Application/Common/Conf/config.php';

use Think\Db\Driver;
use Think\Db\Driver\Mysql;
use Org\OT\MyDatabase;

$dbconfig = array(
    'username' => $config['DB_USER'],
    'password' => $config['DB_PWD'],
    'database' => $config['DB_NAME'], // 密码
    'hostport' => $config['DB_PORT'], // 端口
    'charset' => $config['DB_CHARSET'],
    'prefix' => $config['DB_PREFIX'],
    'type' => $config['DB_TYPE'], // 数据库类型
    'hostname' => $config['DB_HOST']
);

$db = new Mysql();
$db->connect($dbconfig);
$tables = $db->getTables($config['DB_NAME']);

$config = array(
    'path' =>  ROOT.'/Data/bak/' . DIRECTORY_SEPARATOR,
    'part' => '2048000',
    'compress' => 0,
    'level' => 9
);

// 检查是否有正在执行的任务
$lock = "{$config['path']}backup.lock";

header("Content-Type:text/html;charset=UTF-8");
// 检查备份目录是否可写
if (!is_writable($config['path'])) {
    echo '备份目录不存在或不可写，请检查后重试！';exit;
}

if (is_file($lock)) {
    echo '检测到有一个备份任务正在执行，请稍后再试！';
    exit;
} else {
    // 创建锁文件
    file_put_contents($lock, time());
}


// 生成备份文件信息
$file = array(
    'name' => date('Ymd-His', time()),
    'part' => 1
);

// 创建备份文件
$Database = new MyDatabase($file, $config,$db);
if (false !== $Database->create()) {
    $start = 0;
    //备份指定表
    foreach ($tables as $table){
        
        $start = $Database->backup($table, $start);
        if(false === $start){ //出错
           echo '备份出错！';
           //todo 删除锁定文件，和备忘文件
           unlink($lock);
           unlink($file);
           exit;
        } elseif (0 !== $start) { //下一表
            while ($start != 0){
                 $start = $Database->backup($table, $start);
            }
        }
    }
    unlink($lock);
    echo '备份成功';exit;
} else {
   echo '初始化失败，备份文件创建失败！';
   exit;
}



