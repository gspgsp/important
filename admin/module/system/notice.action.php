<?php
/**
 * 内容管理
 */
class noticeAction extends adminBaseAction {
    public function __init(){
        $this->debug = false;
        $this->doact = sget('do','s');
        $this->db=M('public:common')->model('notice');
        $this->assign('action',ROUTE_A); //当前方法init
        $cache=cache::startMemcache();
        $cache->delete('article_index');//清除首页资讯缓存

    }
/**
 * [init description]
 * @Author   xianghui
 * @DateTime 2017-01-10T15:13:21+0800
 * @return   [type]                   [description]
 */
    public function init(){
        //获取列表数据
        $action=sget('action','s');
        if($action=='grid'){ //获取列表
            $this->_grid();exit;
        }
        // elseif($action=='info'){ //获取添加页面详情
        //     $this->_info();exit;
        // }elseif($action=='submit'){ //提交内容
        //     $this->_submit();exit;
        // }elseif($action=='remove'){ //删除
        //     $this->_remove();exit;
        // }elseif($action=='save'){ //编辑行内数据
        //     $action=sget('action','s');
        //     $this->_save();exit;
        // }

        $this->assign('page_title','公告管理');
        $this->display('notice.list.html');
    }

    /**
     * Ajax获取列表内容
     * @access private
     * @return html
     */
    private function _grid(){
        $page = sget("pageIndex",'i',0); //页码
        $size = sget("pageSize",'i',20); //每页数
        $sortField = sget("sortField",'s','input_time'); //排序字段
        $sortOrder = sget("sortOrder",'s','desc'); //排序

        //搜索条件
        $where=" 1 ";

        //可用状态
        $status=sget('status',0);
        if($status>0){
            $where.=' and status='.($status-1);
        }
        //是否已读
        $is_read=sget('is_read',0);
        if($is_read>0){
            $where.=' and is_read='.($is_read-1);
        }

        //关键词
        $key_type=sget('key_type','s','title');
        $keyword=sget('keyword','s');
        if(!empty($keyword)){
            if($key_type=='title'){
                $where.=" and $key_type like '%$keyword%' ";
            }else{
                $where.=" and $key_type='$keyword' ";
            }
        }

        $list=$this->db->where($where)
                    ->page($page+1,$size)
                    ->order("$sortField $sortOrder")
                    ->getPage();
        foreach($list['data'] as $k=>$v){
            $list['data'][$k]['input_time']=$v['input_time']>1000 ? date("Y-m-d H:i:s",$v['input_time']) : '-';
            $list['data'][$k]['update_time']=$v['update_time']>1000 ? date("Y-m-d H:i:s",$v['update_time']) : '-';
        }
        $result=array('total'=>$list['count'],'data'=>$list['data']);
        $this->json_output($result);
    }

    /**
     * Ajax获取详情
     * @access private
     * @return html
     */
    public function info(){
        $this->is_ajax=true;
        $this->assign('mini_list',0);

        $id=sget('id','i');
        if($id>0){
            $info=$this->db->wherePk($id)->getRow();
            if(empty($info)){
                $this->error('错误的数据请求');
            }
            $info['input_time']=$info['input_time']>1000 ? date("Y-m-d H:i:s",$info['input_time']) : '-';
            $info['update_time']=$info['update_time']>1000 ? date("Y-m-d H:i:s",$info['update_time']) : '-';
        }else{
            $info=array('status'=>sget('status','i'), 'is_read'=>sget('is_read','i'));
        }
        $this->assign('info',sstripslashes($info));

        $this->assign('page_title',$this->title);
        $this->display('notice.info.html');
    }

    /**
     * Ajax提交详情数据
     * @access private
     * @return html
     */
    public function submit() {
        $this->is_ajax=true;
        $_info=sget('info','a');
        $_id=sget('id','i');
        $_info['status'] = $_info['status']==0?1:0;
        if(strlen($_info['title'])<3){
            $this->error('请输入标题');
        }
        //是否存在
        $where="  1 ";
        $_info['input_admin']=$_SESSION['name'];
        $_data=saddslashes($_info);
        //更新或新增商品数据
        if($_id>0){
            $_data['update_time']=CORE_TIME;
            $msg=$this->db->wherePk($_id)->update($_data);
        }else{
            $_data['input_time']=$_data['update_time']=CORE_TIME;
            $msg=$this->db->add($_data);
        }
        if(!$msg){
            $this->error("操作失败");
        }else{
            $this->success("操作成功");
        }
    }

    /**
     * Ajax删除节点s
     * @access private
     */
    public function remove(){
        $this->is_ajax=true; //指定为Ajax输出
        $ids=sget('ids','s');
        if(empty($ids)){
            $this->error('操作有误');
        }
        $result=$this->db->where("id in ($ids)")->delete();
        if($result){
            $this->success('操作成功');
        }else{
            $this->error('数据处理失败');
        }
    }

    /**
     * 编辑已存在的数据
     * @access public
     * @return html
     */
    public function save(){
        $this->is_ajax=true; //指定为Ajax输出
        $data = sdata(); //获取UI传递的参数
        $sql=array();
        if(empty($data)){
            $this->error('操作数据为空');
        }
        foreach($data as $k=>$v){
            $_data=array(
                'title'=>$v['title'],
                'content'=>$v['content'],
                'status'=>(int)$v['status'],
                'is_read'=>(int)$v['is_read'],
                'update_time'=>CORE_TIME,
                'update_admin'=>$_SESSION['name'],
            );
            $sql[]=$this->db->wherePk($v['id'])->updateSql($_data);
        }

        if(empty($sql)){
            $this->error('操作数据为空');
        }
        $result=$this->db->commitTrans($sql);
        if($result){
            $this->success('操作成功');
        }else{
            $this->error('数据处理失败');
        }
    }

    /**
     * 上传文件
     * @access public
     * @return html
     */
    public function upload(){
        $this->is_ajax=true; //指定为Ajax输出
        set_time_limit(0);
        //$savePath = "fileContract";
        $result=A('public:upload')->upload($savePath);

        $from=sget('from','s'); //来源

        if(empty($result['err'])){
            $file = $result['file'];
            $att = array(
                    'name'=> $file[0]['name'],
                    'file_url'=> $file[0]['savename'],
                    'file_exts'=> $file[0]['type'],
                    'file_size'=> $file[0]['size'],
                    'input_time'=> CORE_TIME,
                );
            if(!empty($att['name'])){
                M('system:attachment')->addAtt($att);
                $attId = M('system:attachment')->getLastID();
            }
            if($from=='kind'){
                $this->json_output(array('error'=>0,'message'=>'','url'=>C('TEMP_REPLACE.__UPLOAD__').'/'.$file[0]['savename']));
                //$this->json_output(array('error'=>0,'message'=>'','attId'=>$attId,'url'=>APP_URL.'/upload/'.$result['file']));
            }else{
                //$this->success($result['file']);
                $this->json_output(array('error'=>0,'attId'=>$attId,'url'=>$file[0]['savename'],'name'=>$file[0]['name']));
            }
        }else{
            if($from=='kind'){
                $this->json_output(array('error'=>1,'message'=>$result['err'],'url'=>''));
            }else{
                $this->error($result['err']);
            }
        }
    }

    /**
     * [delFile 删除文件]
     * @Author   xianghui
     * @DateTime 2017-02-09T11:15:48+0800
     * @return   [type]                   [description]
     */
    public function delFile(){
        $this->is_ajax=true; //指定为Ajax输出
        $url = sdata(); //获取UI传递的参数
        if($url){
            $file = dirname(__FILE__)."/./../../../static/upload/".$url;
            if (unlink($file)) {
                $this->success('删除成功');
            }else{
                $this->error('删除失败');
            }
        }
    }
}

?>
