<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Model;
class DailyController extends CommonController{
    /**
     * 用户日志
     */
    public function myList(){
        $this->size = 7;
        $this->total = ceil(M('Log')->count()/$this->size);
        if(!IS_AJAX){
            $this->logs = M('Log as l')->field('l.*,u.role,u.part,u.username')
                                       ->join('__USER__ as u on u.id = l.user_id')
                                       ->limit(0,$this->size)
                                       ->order('time desc')->select();
            $this->display('list');
        }else{
            $page = I('get.page',1,'intval');
            $size = ($page-1)*$this->size;
            $logs = M('Log as l')->field('l.*,u.role,u.part,u.username')
                                ->join('__USER__ as u on u.id = l.user_id')
                                ->limit($size,$this->size)
                                ->order('time desc')->select();
            foreach ($logs as $log){
                $content.='<tr><td>'.$log['user_id'].'</td>
									<td>'.$log['username'].'</td>
									<td>'.$log['role'].'</td>
									<td>'.$log['part'].'</td>
									<td>'.date('Y-m-d H:i:s',$log['time']).'</td>
									<td>'.$log['ip'].'</td>
								</tr>';
            }
            $this->result->msg = $content;
            $this->result->success = true;
            $this->ajaxReturn($this->result);
        }
    }
    
    public function delete(){
        $model = new Model();
        $rel = $model->execute('truncate table __LOG__');
        if($rel!==false){
            $this->result->success = true;
        }
        $this->ajaxReturn($this->result);
    }
}