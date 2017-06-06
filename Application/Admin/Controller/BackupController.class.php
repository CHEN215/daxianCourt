<?php
namespace Admin\Controller;

use Think\Db;
use Org\OT\Database;
use Think\Model;

class BackupController extends CommonController{

    /**
     * 数据库备份/还原列表
     * 
     */
    public function index(){
      

        $rel = M()->query('show table status');
        $rel = array_map('array_change_key_case', $rel);
        
        $this->tables = $rel;
        
        $this->display();
    }

    /**
     * 优化表
     */
    public function optimize(){
        $tables = $_POST['table'];
        if($tables) {
            $Db   = M();
            if(is_array($tables))$tables = implode('`,`', $tables);
                $list = $Db->query("OPTIMIZE TABLE `{$tables}`");
                if($list){
                    $this->result->success='ture';
                    $this->result->msg='数据表优化成功';
            } else {
                $this->result->msg = '数据表优化出错请重试！';
            }
        } else {
            $this->result->msg = '请指定要优化的表！';
        }
        $this->ajaxReturn($this->result);
    }

    /**
     * 修复表
     */
    public function repair()
    {
        $tables = $_POST['table'];
        if ($tables) {
            $Db = D();
            if (is_array($tables))
                $tables = implode('`,`', $tables);
            $list = $Db->query("REPAIR TABLE `{$tables}`");
            
            if ($list) {
                $this->result->success = true;
                $this->result->msg = '数据表修复完成！';
            } else {
                $this->result->msg = '数据表修复出错请重试！';
            }
        } else {
            $this->result->msg = '请指定要修复的表！';
        }
        $this->ajaxReturn($this->result);
    }

    /**
     * 备份数据库
     * 
     * @param String $tables      表名
     * 
     * @param Integer $id        表ID
     * 
     * @param Integer $start     起始行数
     */
    public function export()
    {
       
        $tables = I('post.table');
        $id = I('get.id', null);
        $start = I('get.start', null);
        if (IS_POST && ! empty($tables) && is_array($tables)) { // 初始化
                                                             // 读取备份配置
                                                             // 压缩备份文件需要PHP环境支持gzopen,gzwrite函数
            $config = array(
               // 'path' => realpath('./Data/bak/') . DIRECTORY_SEPARATOR,
                'path' => ROOT . '/Data/bak/' . DIRECTORY_SEPARATOR,
                'part'     => '2048000',
                'compress' => 0,
                'level'    => 9,
            );

            //检查是否有正在执行的任务
            $lock = "{$config['path']}backup.lock";
            
            if(is_file($lock)){
                $this->result->msg = '检测到有一个备份任务正在执行，请稍后再试！';
                $this->ajaxReturn($this->result);
            } else {
                //创建锁文件
                file_put_contents($lock, NOW_TIME);
            }

            //检查备份目录是否可写
            if(!is_writable($config['path'])){
                $this->result->msg='备份目录不存在或不可写，请检查后重试！';
                $this->ajaxReturn($this->result);
            }
            session('backup_config', $config);

            //生成备份文件信息
            $file = array(
                'name' => date('Ymd-His', NOW_TIME),
                'part' => 1,
            );
            session('backup_file', $file);

            //缓存要备份的表
            session('backup_tables', $tables);

            //创建备份文件
            $Database = new Database($file, $config);
            if(false !== $Database->create()){
                $tab = array('id' => 0, 'start' => 0);
                $this->result->success = true;
                $this->result->tables = $tables;
                $this->result->tab = $tab;
                $this->ajaxReturn($this->result);
            } else {
                $this->result->msg='初始化失败，备份文件创建失败！';
                $this->ajaxReturn($this->result);
            }
        } elseif (IS_GET && is_numeric($id) && is_numeric($start)) { //备份数据
            $tables = session('backup_tables');
            //备份指定表
            $Database = new Database(session('backup_file'), session('backup_config'));
            $start  = $Database->backup($tables[$id], $start);
            if(false === $start){ //出错
                $this->result->msg='备份出错！' && $this->ajaxReturn($this->result);
            } elseif (0 === $start) { //下一表
                if(isset($tables[++$id])){
                    $tab = array('id' => $id, 'start' => 0);
                    $this->result->success = true;
                    $this->result->tab = $tab;
                    $this->ajaxReturn($this->result);
                } else { //备份完成，清空缓存
                    unlink(session('backup_config.path') . 'backup.lock');
                    session('backup_tables', null);
                    session('backup_file', null);
                    session('backup_config', null);
                    $this->result->msg='备份完成！';
                    $this->result->success= true;
                    $this->ajaxReturn($this->result);
                }
            } else {
                $tab  = array('id' => $id, 'start' => $start[0]);
                $this->result->tab = $tab;
                $this->result->success= true;
                $this->ajaxReturn($this->result);
            }
        } else { // 出错
            $this->result->msg = '参数错误！';
            $this->ajaxReturn($this->result);
        }
    }

    /**
     * 数据恢复
     */
    public function dataImport()
    {
       
        // 列出备份文件列表
        $path = realpath('./Data/bak/');
        $flag = \FilesystemIterator::KEY_AS_FILENAME;
        $glob = new \FilesystemIterator($path, $flag);
        
        $list = array();
        foreach ($glob as $name => $file) {
            if (preg_match('/^\d{8,8}-\d{6,6}-\d+\.sql(?:\.gz)?$/', $name)) {
                $name = sscanf($name, '%4s%2s%2s-%2s%2s%2s-%d');
                
                $date = "{$name[0]}-{$name[1]}-{$name[2]}";
                $time = "{$name[3]}:{$name[4]}:{$name[5]}";
                $part = $name[6];
                
                if (isset($list["{$date} {$time}"])) {
                    $info = $list["{$date} {$time}"];
                    $info['part'] = max($info['part'], $part);
                    $info['size'] = $info['size'] + $file->getSize();
                } else {
                    $info['part'] = $part;
                    $info['size'] = $file->getSize();
                }
                $extension = strtoupper(pathinfo($file->getFilename(), PATHINFO_EXTENSION));
                $info['compress'] = ($extension === 'SQL') ? '-' : $extension;
                $info['time'] = "{$date} {$time}";
                $info['name'] = "{$name[0]}{$name[1]}{$name[2]}-{$name[3]}{$name[4]}{$name[5]}";
                $list["{$date} {$time}"] = $info;
            }
        }
       // var_dump($list);exit;
        $this->backups = $list;
       //var_dump($list);exit;
        $this->display();
    }

    /**
     * 删除备份文件
     */
    public function del(){
        $name = I('post.name',0,'trim');
        if($name){
            $name  = $name . '-*.sql*';
            $path  = realpath('./Data/bak/') . DIRECTORY_SEPARATOR . $name;
            
            array_map("unlink", glob($path));
            if(count(glob($path))){
                $this->result->msg = '备份文件删除失败，请检查权限！';
            } else {
                $this->result->msg = '备份文件删除成功！';
                $this->result->success=true;
            }
        } else {
            $this->result->msg = '参数错误！';
        }
      $this->ajaxReturn($this->result);

    
    }

    /**
     * 还原数据库
     */
    public function import(){
        $name = I('post.name','','trim');
        $part = I('post.part',null);
        $start = I('post.start',null);
        if($name!='' && is_null($part) && is_null($start)){ //初始化
            //获取备份文件信息
            $name  = $name . '-*.sql*';
            $path  = realpath('./Data/bak/') . DIRECTORY_SEPARATOR . $name;
            $files = glob($path);
            $list  = array();
            foreach($files as $name){
                $basename = basename($name);
                $match    = sscanf($basename, '%4s%2s%2s-%2s%2s%2s-%d');
                $gz       = preg_match('/^\d{8,8}-\d{6,6}-\d+\.sql.gz$/', $basename);
                $list[$match[6]] = array($match[6], $name, $gz);
            }
            ksort($list);
    
            //检测文件正确性
            $last = end($list);
            if(count($list) === $last[0]){
                session('backup_list', $list); //缓存备份列表
                $this->result->msg = '初始化完成！';
                $this->result->part = 1;
                $this->result->start = 0;
                $this->result->success = true;
                
            } else {
                $this->result->msg = '备份文件可能已经损坏，请检查！';
            }
           
             
        } elseif(is_numeric($part) && is_numeric($start)) {
            $list  = session('backup_list');
            $db = new \Org\OT\Database($list[$part], array(
                'path'     => realpath('./Data/bak/') . DIRECTORY_SEPARATOR,
                'compress' => $list[$part][2]));
    
            $start = $db->import($start);
    
            if(false === $start){
                $this->result->msg = '还原数据出错！';
                 
            } elseif(0 === $start) { //下一卷
                if(isset($list[++$part])){
                   
                    $this->result->msg = "正在还原...#{$part}";
                    $this->result->part = $part;
                    $this->result->start = 0;
                    $this->result->success = true;
    
                } else {
                    session('backup_list', null);
                     
                     
                    $this->result->msg = "还原完成！";
                   
                    $this->result->success = true;
                }
            } else {
                if($start[1]){
                    $rate = floor(100 * ($start[0] / $start[1]));
                    
                    $this->result->msg = "正在还原...#{$part} ({$rate}%)";
                    $this->result->part = $part;
                    $this->result->start = $start[0];
                    $this->result->rate = $rate;
                    $this->result->success = true;
                } else {
                    $this->result->gz = 1;
                    $this->result->msg = "正在还原...#{$part}";
                    $this->result->success = true;
                    
                }
            }
             
        } else {
            $this->result->msg =  '参数错误！';
    
        }
        $this->ajaxReturn($this->result);
    }
     
    function test(){
//        $i =  M()->execute(file_get_contents('D:/wamp/www-alias/TongJiJu/sql.sql'));
//         var_dump($i);
echo 111;
    }
}
?>