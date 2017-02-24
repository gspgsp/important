<?php
/** 
 * 管理员角色管理
 */
class creditAction extends adminBaseAction {
	public function __init(){
		$this->debug = false;
		$this->action = sget('action','');
		$this->db=M('public:common');
	}
	
	/**
	 * 角色列表
	 * @access public 
	 * @return html
	 */
	public function init(){
		//获取列表数据
		$this->db->model('credit');
		$data=$this->db->order('pid asc')->getAll();

		foreach($data as $k=>$v){
			$data[$k]['input_time']=$v['input_time']>1000 ? date("Y-m-d H:i:s",$v['input_time']) : '-';
			$data[$k]['update_time']=$v['update_time']>1000 ? date("Y-m-d H:i:s",$v['update_time']) : '-';
		}
		$this->assign('data',json_encode($data));		
		$this->assign('page_title','信用管理');
		$this->display('credit.list.html');
	}

	/**
	 * Ajax新增角色
	 * @access public 
	 */
	public function add(){
		$this->is_ajax=true; //指定为Ajax输出
		$this->info(0);	
	}

	/**
	 * Ajax编辑角色
	 * @access public 
	 */
	public function edit(){
		$this->is_ajax=true; //指定为Ajax输出
		$id=sget('id','i',0);
		if($id<0){
			$this->error('信息有误');	
		}
		$this->info($id);	
	}

	/**
	 * Ajax编辑角色
	 * @access public 
	 */
	public function remove(){
		$this->is_ajax=true; //指定为Ajax输出
		$id=sget('ids','i',0);
		if($id<0){
			$this->error('信息有误');	
		}		
		if(M('user:credit')->getColById($id)){
			$list[]=M('user:credit')->getColById($id);
		}else{
			$this->db->model('credit')->deletePk($id);
			$this->success('删除处理成功');			
		}
		if($list) $this->json_output(array('err'=>0,'result'=>$list));
		
	}

	/**
	 * 角色处理
	 * @access private 
	 * @return html
	 */
	private function info($id=0){
		$data = sdata(); //获取UI传递的参数
		$role_id=(int)$data['role_id'];
		$data['pid']=(int)$data['pid'];
		unset($data['role_id']);
		$this->db->model('credit');
		if($role_id>0 && $role_id==$id){
			if($data['pid']==$id) $this->error('自己不能是自己的子集');
			$pid=$data['pid'];
			$id_arr=$this->db->where("pid=$id")->getCol();
			if(in_array($pid,$id_arr)) $this->error('父集不能是自己的子集');
			if($data['pid']<=0){
				if(!$this->check($data['grade'],$id)) $this->error('超过100分了');
			}else{
				if(!$this->check($data['grade'],$id,$data['pid'])) $this->error('超过该分类限制分数了');
			}
			$data['update_time']=CORE_TIME;
			$data['update_admin']=$_SESSION['name'];
			$this->db->wherePk($id)
					->update($data);	
		}elseif($role_id==0){//pid
			$data['input_time']=CORE_TIME;
			$data['input_admin']=$_SESSION['name'];
			if($data['pid']<=0){
				if(!$this->check($data['grade'],$id)) $this->error('超过100分了');
			}else{
				if(!$this->check($data['grade'],$id,$data['pid'])) $this->error('超过该分类限制分数了');
			}
			$this->db->add($data);
		}
		$this->success('信息处理成功');
	}

	/**
	  * dalei分数审核
	  */
	public function check($num,$id=0,$pid=0){
	 	//获取总分
        $sum=$this->db->model('credit')->select('sum(grade)')->where("pid=$pid and status=1")->getOne();
        //获取确定id的分数
        if($id){
            $one=$this->db->model('credit')->select('grade')->where("id = $id")->getOne();
        }else{
            $one=0;
        }
        //判断是否是添加
        if($one==$num){
        	$all=$sum+$one;
        }else{
        	$all=$num+$sum-$one;
        } 
        //获取总分或者该分类的分数
        if($pid){
        	$ab=$this->db->model('credit')->select('sum(grade)')->where("id=$pid")->getOne();
        }else{
        	$ab=100;
        }
        //判断分数
        if($all<=$ab){
           return true;
        }else{
           return false;
        }
	 }


	//ajax获取状态，改变保存
	public function changeSave(){
		$this->is_ajax=true; //指定为Ajax输出
		$changeid =  sget('changeid','i',0);
		$this->db->model('credit');
		$status = $this->db->select('status')->wherePk($changeid)->getOne() == 1  ? 2 : 1;
		//showTrace();
		//获取该id的分数
		$grade=$this->db->select('grade')
						->where("id=$changeid")
						->getOne();		

        //获取pid
        $pid=$this->db->select('pid')->wherePk($changeid)->getOne();

		if($status==1){
			//不正常变为正常
			if(!$pid){//pid为0
				//获取总分
        		$sum=$this->db->select('sum(grade)')->where("status=1 and pid=0")->getone();
        		if(($sum+$grade)>100) $this->error('分数超过100分');
			}else{
				//获取总分
        		$sum=$this->db->select('sum(grade)')->where("status=1 and pid=$pid")->getone();
        		//获取该id所属的分数
				$all=$this->db->select('grade')
						->where("id=$pid")
						->getOne();
        		if(($sum+$grade)>$all) $this->error("分数超过该分类【{$all}分】");
			}
			
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

	public function sumGrade(){
		$this->db->model('credit');
		$sumgrade=$this->db->select('sum(grade)')
							->where('pid=0 and status=1')
							->getOne();
		if(empty($sumgrade)){
			$sumgrade=0;
		}
		$msg="[总分]:【".($sumgrade)."】分";
        	$arr=array(
        			'msg'=>$msg,
        		);
        echo json_encode($arr);
	}
}
?>
