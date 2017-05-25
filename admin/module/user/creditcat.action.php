<?php
/**
*信用管理
*/
class creditcatAction extends adminBaseAction
{
	public function __init(){
		$this->debug = false;
		 $this->db=M('public:common');
		
	}
	/**
	 *
	 * @access public
	 * @return html
	 */
	public function init(){
		$action=sget('action','s');
		if($action=='grid'){ //获取列表
			$this->_grid();exit;
		}
		$this->display('creditcat.list.html');
	}

	 /**
	  * 分数审核
	  */
	public function check($num,$id=0){
	 	//获取总分
        $sum=$this->db->model('credit_cat')->select('sum(catgrade)')->where("status=1")->getone();
        //获取确定id的分数
        if($id){
            $one=$this->db->model('credit_cat')->select('catgrade')->where("id = $id")->getone();
        }else{
            $one=0;
        }
        $all=$num+$sum-$one;
        //判断分数
        if($all<=100){
           return true;
        }else{
           return false;
        }
	 }

	 /**
	  * 总分Ajax显示
	  */         
    public function sumgrade(){
	     $where="status=1";
	     $msg="";		
		 $sum=$this->db->model('credit_cat')->select("sum(catgrade) as sum")->where($where)->getOne();
		 $sum+=0;	
	     $msg="[合计]总分:【".($sum)."】分";
	     return $msg;
	     }
         
	/**
	 *
	 * @access private
	 * @return html
	 */
	private function _grid(){
		$page = sget("pageIndex",'i',0); //页码
		$size = sget("pageSize",'i',20); //每页数
		$sortField = sget("sortField",'s','input_time'); //排序字段
		$sortOrder = sget("sortOrder",'s','desc'); //排序       
		$status =sget('status','s');
		$keyword=sget('keyword','s');
        $where="1";
		if(!empty($keyword)){
			$fids = implode(',',M('user:credit_cat')->getIdsByName($keyword));
			$where.=" and id in ($fids) ";
		}
                
        $list=$this->db->model('credit_cat')->where($where)
                ->page($page+1,$size)
                ->order("$sortField $sortOrder")
                ->getPage();
		foreach($list['data'] as $k=>$v){
			$list['data'][$k]['input_time']=$v['input_time']>1000 ? date("Y-m-d H:i:s",$v['input_time']) : '-';
			$list['data'][$k]['update_time']=$v['update_time']>1000 ? date("Y-m-d H:i:s",$v['update_time']) : '-';
			$list['data'][$k]['update_admin']=empty($v['update_admin'])?'-':$v['update_admin'];
		}
         
        $msg=$this->sumgrade();
		$result=array('total'=>$list['count'],'data'=>$list['data'],'msg'=>$msg);
		$this->json_output($result);
	}

	/**
	 * 保存(新/编辑)信息
	 * @access public
	 */
	public function ajaxSave(){
		$this->is_ajax=true; //指定为Ajax输出
		$action = sget('action','s');
		$data = sdata(); //传递的参数

		$id = $data['id'];
               
		if(empty($data)) $this->error('错误的请求');

		if($action =='edit'){
			$this->db=M('public:common')->model('credit_cat');
			if(M('user:credit_cat')->getPidByModel($data['catname'],$data['catgrade'],$id)) $this->error('相关分类已存在');

               	if(!$this->check($data['catgrade'],$id)){
                            $this->error('分数超过100分');
                        }


			$result = $this->db->where("id=$id")->update($data+array('update_time'=>CORE_TIME, 'update_admin'=>$_SESSION['name'],));
     
		}else{

			if(M('user:credit_cat')->getPidByModel($data['catname'],$data['catgrade'],$id)) $this->error('相关分类已存在');
				$this->db=M('public:common')->model('credit_cat');
			
                                
                        if(!$this->check($data['catgrade'])){
                            $this->error('分数超过100分');
                        }
                     
			$result = $this->db->add($data+array('input_time'=>CORE_TIME, 'input_admin'=>$_SESSION['name'],));
			
		}
		if(!$result) $this->error('操作失败');
		$this->success('操作成功');
	}

	/**
	 * 删除产品数据
	 * @access public
	 */
	public function remove(){
		$this->is_ajax=true; //指定为Ajax输出
		$ids=sget('ids','s');
		if(empty($ids)){
			$this->error('操作有误');
		}	
		$ids=explode(',', $ids);
		foreach ($ids as $k => $v) {
			  if(M('user:credit_lab')->getColById($v)){
			 	$list[$v]=M('user:credit_lab')->getColById($v);
			 	continue;
			 }else{
				$result=$this->db->model('credit_cat')->where("id = ($v)")->delete();
				
			}
		}
		if($list) $this->json_output(array('err'=>0,'result'=>$list));
		 if($result){
			// $cache=cache::startMemcache();
			// $cache->delete('product');
			$this->success('操作成功');
		}else{
			$this->error('删除操作失败');
		}
	}
	/**
     * 产品信息编辑
     * @access public
    */
	public function edit(){
		$id=sget('id','i');
		$this->db=M('public:common')->model('credit_cat');
		$data = $this->db->where('id='.$id)->getRow();
		$this->assign('data',$data);
		$this->assign('doact','check');
		$this->display('creditcat.edit.html');
    }

	 
	//ajax获取状态，改变保存
	public function changeSave(){
		$this->is_ajax=true; //指定为Ajax输出
		$changeid =  sget('changeid','i',0);
		$this->db=M('public:common')->model('credit_cat');
		$status = $this->db->select('status')->wherePk($changeid)->getOne() == 1  ? 2 : 1;
		$grade=$this->db->select('catgrade')
						->where("id=$changeid")
						->getOne();
		//获取总分
        $sum=$this->db->model('credit_cat')->select('sum(catgrade)')->where("status=1")->getone();

		if($status==1){
			//不正常变为正常
			if(($sum+$grade)>100) $this->error('分数超过100分');
		}

		$res = $this->db->wherePk($changeid)->update(array('update_time'=>CORE_TIME, 'update_admin'=>$_SESSION['name'],'status'=>$status,));

		if($res){
		    // $cache=cache::startMemcache();
		    // $cache->delete('product');
		    $this->success('操作成功');
		}else{
			showtrace();
			$this->db->rollback();
			$this->error('操作失败');
		}

	}
}
