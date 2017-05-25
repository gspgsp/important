<?php
/**
*信用管理
*/
class creditlabAction extends adminBaseAction
{
	public function __init(){
		$this->debug = false;
		$this->doact = sget('do','s');
		$this->ischecked = sget('ischecked','s');
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
		//获取有效分类列表
    	$category=$this->db->model('credit_cat')->where('status=1')->getAll();
    	$cat_name=array();
    	foreach($category as $k=>$v){
    		$cat_name[$v['id']]=$v['catname'];
    	}
        $this->assign('cat_name',$cat_name);
		$this->display('creditlab.list.html');
	}

	

	 /**
	  * 分数审核
	  */
	public function check($num,$c_id,$id=0){
		//获取该分类的分数
		$cat_grade=$this->db->model('credit_cat')->select('catgrade')->where("id = $c_id and status=1")->getone();
		//获取该分类下有效的指标分数的和
        $sum=$this->db->model('credit_lab')->select('sum(lab_grade)')->where("status=1 and cat_id=$c_id")->getone();

        if($id){
            $one=$this->db->model('credit_lab')->select('lab_grade')->where("id = $id and cat_id=$c_id and status=1")->getone();
        }else{
            $one=0;
        }
         $all=$num+$sum-$one;

        if($all<=$cat_grade){
           return true;
        }else{
           return false;
        }
	 }
      
	 /**
	  *分数提醒
	  * 
	  */
    public function sumgrade(){
     	$action = sget("action",'s');
     	if($action=='edit'){
     		$where="status=1";
            $msg="";
			$sum=$this->db->model('credit_cat')->select("sum(catgrade) as sum")->where($where)->getOne();
			$msg="[合计]总分:【".($sum)."】分";
            return $msg;
        }else{        	
        	$c_id=$_POST['id'];
        	$cat_grade=$this->db->model('credit_cat')->select('catgrade')->where("id = $c_id and status=1")->getone();
        	$sum=$this->db->model('credit_lab')->select('sum(lab_grade)')->where("status=1 and cat_id=$c_id")->getone();
        	$sum+=0;
        	$msg="[分类总分]:【".($cat_grade)."】分，该分类下指标已有【".($sum)."】分";
        	$arr=array(
        			'msg'=>$msg,
        		);
            echo json_encode($arr);
        }
             
    }



	/**
	 *
	 * @access private
	 * @return html
	 */
	private function _grid(){
		$page = sget("pageIndex",'i',0); //页码
		$size = sget("pageSize",'i',20); //每页数
		$sortField = sget("sortField",'s','cat.id'); //排序字段
		$sortOrder = sget("sortOrder",'s','desc'); //排序
       	//$status =sget('key_type','s');
		$keyword=sget('keyword','s');         
		
        $where="cat.status=1 ";
        if(!empty($keyword)){
			$fids = implode(',',M('user:credit_lab')->getIdsByName($keyword));
			$where.=" and lab.id in ($fids) ";
		}

        $list=$this->db->model('credit_lab as lab')
                        ->join('credit_cat as cat','lab.cat_id=cat.id')
                        ->select('lab.*,cat.catname,cat.catgrade')
                        ->where($where)
                        ->order("$sortField $sortOrder")
						->getPage();

		foreach($list['data'] as $k=>$v){
			$list['data'][$k]['input_time']=$v['input_time']>1000 ? date("Y-m-d H:i:s",$v['input_time']) : '-';
			$list['data'][$k]['update_time']=$v['update_time']>1000 ? date("Y-m-d H:i:s",$v['update_time']) : '-';
			$list['data'][$k]['update_admin']=empty($v['update_admin'])?'-':$v['update_admin'];
			$list['data'][$k]['catname']=$v['catname'].' 【'.$v['catgrade'].'分】';
		}
		$result=array('total'=>$list['count'],'data'=>$list['data']);
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
		if(!$this->check($data['lab_grade'],$data['cat_id'],$id)) $this->error('超过分类分数');
		if(M('user:credit_lab')->getPidByModel($data['lab_name'],$data['lab_grade'],$id)) $this->error('相关指标已存在');
		if($action =='edit'){
			$this->db=M('public:common')->model('credit_lab');			
			if(M('user:credit_lab')->getPidByModel($data['lab_name'],$data['lab_grade'],$id)) $this->error('相关指标已存在');
			$result = $this->db->where("id=$id")->update($data+array('update_time'=>CORE_TIME, 'update_admin'=>$_SESSION['name'],));
		}else{
			$model = sget('model','s');
			$this->db=M('public:common')->model('credit_lab');                      
			$result = $this->db->add($data+array('input_time'=>CORE_TIME, 'input_admin'=>$_SESSION['name'],));
			
		}
		if(!$result) $this->error('操作失败');
		$this->success('操作成功');
	}

	/**
	 * 删除
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
			$result=$this->db->model('credit_lab')->where("id = ($v)")->delete();			
		}
		// $list='';
		// p($result);
		// showTrace();
		// exit;
		//if($list) $this->json_output(array('err'=>0,'result'=>$list));
		 if($result){
			// $cache=cache::startMemcache();
			// $cache->delete('product');
			$this->success('操作成功');
		}else{
			$this->error('删除操作失败');
		}
	}

	
	/**
     * 信息编辑
     * @access public
    */
	public function edit(){
        $id=sget('id','i');
        $cat_id=sget('cat_id','i');
        $data = $this->db->where('id='.$id)->getRow();
        $list=$this->db->model('credit_lab as lab')
                        ->join('credit_cat as cat','lab.cat_id=cat.id')
                        ->select('lab.*,cat.catname,cat.catgrade')
                        ->where("lab.id=$id and cat.id=$cat_id")
                        ->order("input_time desc")
                        ->getRow();

        $category=$this->db->model('credit_cat')->where("status=1")->getAll();
        //p($category);exit;
        $cat_name=array();
        foreach($category as $k=>$v){
                $cat_name[$v['id']]=$v['catname'];
        }


    	$sum=$this->db->model('credit_lab')->select('sum(lab_grade)')->where("status=1 and cat_id=$cat_id")->getone();
    	$sum+=0;
                    	

        //p($cat_name);
        $this->assign('sum',$sum);
        $this->assign('cat_name',$cat_name);
		$this->assign('data',$list);
                //p($list);
		$this->assign('doact','check');
		$this->display('creditlab.edit.html');
    }

    /**
	 * 保存行内编辑工厂数据
	 * @access public
	 * @return html
	 */
	public function save(){
		$this->is_ajax=true; //指定为Ajax输出
		$data = sdata(); //获取UI传递的参数
		if(empty($data)){
			$this->error('错误的操作');
		}
		$sql=array();
		foreach($data as $v){
			$_id=$v['id'];
			if($_id>0){
				$update=array(
					'update_time'=>CORE_TIME,
					'update_admin'=>$_SESSION['name'],
					'status'=>$v['status'],
				);
				$result=$this->db->wherePk($_id)->update($update);
			}
		}
		if($result){
			$this->success('操作成功');
		}else{
			$this->error('数据处理失败');
		}
	}
	//ajax获取状态，改变保存
	public function changeSave(){
		$this->is_ajax=true; //指定为Ajax输出


		$changeid =  sget('changeid','i',0);
		//$check_status =  sget('status','i',0);
		$this->db=M('public:common')->model('credit_lab');
		$status = $this->db->select('status')->wherePk($changeid)->getOne() == 1  ? 2 : 1;
		//if($check_status == 3)   $status=1;
		//$f_id = $this->db->select('f_id')->wherePk($changeid)->getOne();//获取厂家编号
		// $this->db->startTrans();
		$res = $this->db->wherePk($changeid)->update(array('update_time'=>CORE_TIME, 'update_admin'=>$_SESSION['name'],'status'=>$status,));
		//$factoryModel=M('product:factory')->where("fid={$f_id}")->update(array('status'=>$status==1?1:2,));//审核通过 厂家由锁定变为正常
		if($res){
		    // $cache=cache::startMemcache();
		    // $cache->delete('product');
		    $this->success('操作成功');
			//$this->success('操作成功');
		}else{
			showtrace();
			$this->db->rollback();
			$this->error('操作失败');
		}

	}
        
}
