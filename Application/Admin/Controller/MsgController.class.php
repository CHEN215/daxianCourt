<?php
namespace Admin\Controller;
use Think\Controller;
class MsgController extends CommonController {
    /**
     *
     */
    public function reply(){
        if(!IS_AJAX){
            $id = I('get.id',0,'intval');
            if($id==0){
                $this->error('留言不存在:(');
            }
            $this->id = $id;
            $this->display('add');
        }else{
            $data['name'] = I('post.name','','trim');
            $data['comment'] = I('post.comment','','trim');
            $data['pid'] = I('post.pid',0,'intval');
            $data['type'] = 1;
            $data['time'] = time();
            if($data['pid']==0 || $data['name']=='' || $data['comment']==''){
                $this->result->msg = '请正确填写！';
                $this->ajaxReturn($this->result);
            }
            if (M('Comments')->add($data)) {
                M('Comments')->save(array('id' => $data['pid'], 'status' => 1));
              $this->result->success = true;  
            }
            $this->ajaxReturn($this->result);
        }
    }

    /**
     * 留言列表
     */
    public function myList(){
        $pagesize = 8;
        $Msg = M('Comments c'); // 实例化User对象
        $count  = $Msg->where('type=0')->count();// 查询满足要求的总记录数
        $page = I('get.page', 1, 'intval');
        $msgs = $Msg->join('__COMMENTS__  r on r.pid = c.id', 'left')
            ->field('c.*,r.name as rname,r.time as rtime,r.comment as rcomment')
            ->where('c.type=0')
            ->order('status,rtime desc,time desc')
            ->limit(($page-1)*$pagesize ,$pagesize)
            ->select();
        if (!IS_AJAX) {
            $this->count = ceil($count/$pagesize);
            $this->msgs = $msgs;
            $this->display('list'); // 输出模板
        }else{
            $str = '';
            foreach ($msgs as $key => $msg){
                $str .= '<tr>';
                $str .= '    <td>'.($key+1).'</td>';
                $str .= '    <td>'.$msg['name'].'</td>';
                $str .= '    <td>'.$msg['comment'].'</td>';
                $str .= '    <td>'.date('Y-m-d H:i:s',$msg['time']).'</td>';
                $str .= '    <td>'.'邮箱：'.$msg['email'].'<br/>'.'手机：'.$msg['phone'].'</td>';$str .= ' <td>'.$msg['rname'].'</td>';
                $str .= '    <td>'.$msg['rcomment'].'</td>';
                $str .= '    <td>'.date('Y-m-d H:i:s',$msg['rtime']).'</td>';

                if ($msg['status']){
                    $str .= '   <td><span><i class="layui-icon" style="font-size: 30px; color: #1E9FFF;">&#xe618;</i></span></td>';
                }else{
                    $str .= '    <td><a href="javascript:;"  data-id="'.$msg['id'].'" class="layui-btn layui-btn-mini edit_btn">回复</a></td>';
                }
                $str .= '    <td><a href="javascript:;" data-id="'.$msg['id'].'" class="layui-btn layui-btn-danger layui-btn-mini del_btn">删除</a></td>';
                $str .= '</tr>';
            }
            $this->result->msg = $str;
            $this->result->success = true;
            $this->ajaxReturn($this->result);

        }
    }
    
    public function delete(){
        $id = I('get.id',0,'intval');
        $this->result->msg = '删除失败！';
        $_rel = M('Comments')->delete($id);
        if($_rel > 0){
            M('Comments')->where(array('pid'=>$id))->delete();
            $this->result->success = true;
        }
        $this->ajaxReturn($this->result);
    }
}