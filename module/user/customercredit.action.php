<?php
/**
*信用管理
*/
class customercreditAction extends adminBaseAction
{
	public function __init(){
		$this->debug = false;
		$this->doact = sget('do','s');
		$this->db=M('public:common');	
	}
	/**
	 *
	 * @access public
	 * @return html
	 */
	public function init(){
		$action=sget('action','s');
		$page = sget("pageIndex",'i',0); //页码
		$size = sget("pageSize",'i',20); //每页数
		if($action=='grid'){ //获取列表
			$this->_grid();exit;
		}
        //连表查询，获取有效的指标       
        $list=$this->db->model('credit_lab as lab')
                        ->join('credit_cat as cat','lab.cat_id=cat.id')
                        ->select('lab.id as lab_id,lab.lab_desc as lab_desc,lab.lab_name,lab.cat_id,cat.catname,lab.lab_grade')
                        ->where('lab.status=1 and cat.status=1')
                        ->order("lab.cat_id desc")
						->getAll();
		//联表查询，获取分类下有指标的分类
		$catlist=$this->db->model('credit_cat as cat')
                        ->join('credit_lab as lab','lab.cat_id=cat.id')
                         ->select('cat.id,cat.catname') 
                        ->where('lab.status=1 and cat.status=1')
                        ->getAll();
            //二维数组去掉重复值
            function a_array_unique($array){
               $out = array();
               foreach ($array as $key=>$value){
                   if (!in_array($value, $out)){
                       $out[$key] = $value;
                   }
               }
               return $out;
            }
                
             $catlist=a_array_unique($catlist);
 
        $this->assign('catlist',$catlist);
		$this->assign('list',$list);
		$this->display('customercredit.list.html');
	}

	/**
	 * 判断是否评价
	 */
	public function checkCustomer(){
		$id=$_POST['id'];
		$name=$_POST['name'];
		//获取字段
		$c_name=$this->db->model('customer_credit_comment')
              			->select('strname')
              			->where("customer_id={$id}")
              			->getAll();

		if(!empty($c_name)){
			$this->error('评价过了该公司，如需再评价，请修改');	
		}			
	}

	/**
	 * 动态生成表格项
	 */
	public function someset(){
		$data=sget("ids",'s');
		//联表获取有效的指标
		 $list=$this->db->model('credit_lab as lab')
                        ->join('credit_cat as cat','lab.cat_id=cat.id')
                        ->select('lab.id as lab_id,lab.lab_name,lab.cat_id,cat.catname')
                        ->where('lab.status=1 and cat.status=1')
                        ->order("lab.cat_id desc")
						->getAll();
		$this->json_output($list);
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
		//状态
		$status =sget('status','s');
		//客户等级
		$level = sget('level','s');
		//关键词
		$keyword=sget('keyword','s');
		$key_type=sget('key_type','s');
		$key_type=ltrim($key_type,'lab_id');

		$where="1";
		if(!empty($level)){
			if($level=='a'){
				$where.=' and credit_sum>80';
			}
			if($level=='b'){
				$where.=' and credit_sum<=80 and credit_sum>70';
			}
			if($level=='c'){
				$where.=' and credit_sum<=70 and credit_sum>50';
			}else{
				$where.=' and credit_sum<=50';
			}
		}
		//if(!empty($status)) $where.=" and `status` = '$status' ";	
		if(!empty($keyword)){
				// if($key_type=='f_name'){
				// 	$fids = implode(',',M('product:factory')->getIdsByName($keyword));
				// 	$where.=" and f_id in ('$fids') ";
				// }else{
						
					//$where.=" and $key_type like '%$keyword%' ";
				// }
			}
		//获取有效的分类名字与指标名字
		$list=$this->db->model('credit_lab as lab')
                        ->join('credit_cat as cat','lab.cat_id=cat.id')
                        ->select('lab.id as lab_id,lab.lab_name,lab.cat_id,cat.catname')
                        ->where('lab.status=1 and cat.status=1')
                        ->order("lab.cat_id desc")
						->getAll();
		$select='';
		$select.='customer_id,input_time,input_admin,update_time,update_admin,credit_sum,';
		foreach($list as $row){
			$select.="max(case strname when 'field{$row['lab_id']}' then grade else 0 end ) field{$row['lab_id']},";
		}
		$select=rtrim($select,',');
            
        $list=$this->db->model('customer_credit_comment')
        		->where($where)
        		->select($select)
                ->page($page+1,$size)
                ->order("$sortField $sortOrder")
                ->group('customer_id')
                ->getPage();

         $tmp=array();
		foreach($list['data'] as $k=>$v){
			$list['data'][$k]['input_time']=$v['input_time']>1000 ? date("Y-m-d H:i:s",$v['input_time']) : '-';
			$list['data'][$k]['update_time']=$v['update_time']>1000 ? date("Y-m-d H:i:s",$v['update_time']) : '-';
			$list['data'][$k]['update_admin']=empty($v['update_admin'])?'-':$v['update_admin'];
                    //$list['data'][$k]['lab_grade']=empty($v['lab_grade'])?'-':json_decode($v['lab_grade'],true);
                    // $ab=json_decode($v['lab_grade'],true);
                    // $ab['id']=$v['id'];
                    // $tmp[]=empty($v['lab_grade'])?'-':$ab;
              $c_name=$this->db->model('customer')
              			->select('c_name')
              			->where("c_id={$v['customer_id']}")
              			->getOne();
            $list['data'][$k]['c_name']=empty($c_name)?'-':$c_name;	
            if($v['credit_sum']>80){
            	$level='A';
            }else{
            	if($v['credit_sum']>70){
            		$level="B";
            	}else{
            		if($v['credit_sum']>50){
            			$level="C";
            		}else{
            			$level="D";
            		}
            	}
            }
            $list['data'][$k]['level']=$level;		
		}
              
		$result=array('total'=>$list['count'],'data'=>$list['data']);
		$this->json_output($result);
	}
	
	/**
	 * 保存(新/编辑)客户信用信息
	 * @access public
	 */
	public function ajaxSave(){
// 		$this->is_ajax=true; //指定为Ajax输出
        $data=$_POST;    
		$action = sget('action','s');
		if(empty($data)) $this->error('错误的请求');		
		if($action =='edit'){
			$this->db=M('public:common')->model('customer_credit_comment');
			$tmp=array();
			$result=array();
			foreach($data as $key=>$value){
				$tmp['remark']=$data['remark'];
				$tmp['credit_sum']=$data['credit_sum'];

				if(substr($key,0,5)=='field'){
					$data[$key]+=0;
					$tmp['grade']=$data[$key];
					$data['customer_id']+=0;
					$customer_id=$data['customer_id'];
					$result[]=$this->db->where("customer_id=$customer_id and strname='$key'")
									->update($tmp+array('update_time'=>CORE_TIME, 
									'update_admin'=>$_SESSION['name'],));
				}
			}
		}else{
                $this->db=M('public:common')->model('customer_credit_comment');
                $customer_id=$data['com_id'];
                unset($data['com_id']);
                foreach($data as $key=>$row){
                	$data1['customer_id']=$customer_id;
                	$data1['strname']=$key;
                	$data1['grade']=$row;
                	$data1['remark']=$data['remark'];
                	$data1['credit_sum']=$data['credit_sum'];
                	if(substr($key,0,5)=='field'){
                		$result[] = $this->db->add($data1+array('input_time'=>CORE_TIME, 'input_admin'=>$_SESSION['name'],));
                	}                	
                }

		}
		if(!$result) $this->error('操作失败');
		$this->db->model('customer')
					->where("c_id=$customer_id")
				->update(array(
							'credit_status'=>2,
					));
		$this->success('操作成功');
	}

	/**
	 * 删除客户信用数据
	 * @access public
	 */
	public function remove(){
		$this->is_ajax=true; //指定为Ajax输出
		$ids=sget('ids','s');
		if(empty($ids)){
			$this->error('操作有误');
		}	
		$ids=explode(',', $ids);
		$result=array();
		foreach ($ids as $k => $v) {
			// if(M('product:purchase')->getColById($v)){
			// 	$list[$v]=M('product:purchase')->getColById($v);
			// 	continue;
			// }else{
			
			$result[]=$this->db->model('customer_credit_comment')->where("customer_id=$v")->delete();
			$this->db->model('customer')
					->where("c_id=$v")
				->update(array(
							'credit_status'=>1,
					));	
			
		}
		if($list) $this->json_output(array('err'=>0,'result'=>$list));
		if($result){
			$this->success('操作成功');
		}else{
			$this->error('删除操作失败');
		}
	}

	/**
     * 客户信用信息编辑
     * @access public
    */
	public function edit(){
		$id=sget('id','i');
		$c_name=sget('c_name','s');
		//获取有指标的分类
		$catlist=$this->db->model('credit_cat as cat')
                                ->join('credit_lab as lab','lab.cat_id=cat.id')
                                 ->select('cat.id,cat.catname') 
                                ->where('lab.status=1 and cat.status=1')
                                ->getAll();
        //二维数组去掉重复值
        function a_array_unique($array){
           $out = array();
           foreach ($array as $key=>$value) {
               if (!in_array($value, $out)){
                   $out[$key] = $value;
               }
           }
           return $out;
        }
                
         $catlist=a_array_unique($catlist);
         //获取有效的指标
    	 $list=$this->db->model('credit_lab as lab')
                ->join('credit_cat as cat','lab.cat_id=cat.id')
                ->select('lab.id as lab_id,lab.lab_name,lab.cat_id,lab.lab_desc,lab.lab_grade,cat.catname')
                ->where('lab.status=1 and cat.status=1')
                ->order("lab.cat_id desc")
				->getAll();

		//拼接sql语句
		$select='';
		$select.='customer_id,input_time,input_admin,update_time,update_admin,remark,';
		foreach($list as $key=>$row){
			$select.="max(case strname when 'field{$row['lab_id']}' then grade else 0 end ) field{$row['lab_id']},";
			$str='lab_id2';
			$list[$key][$str]='field'.$row['lab_id'];
		}
		
		$select=rtrim($select,',');		

		$where="1";
		$where.=" and customer_id=$id";
    	$cuslist=$this->db->model('customer_credit_comment')
    		->select($select)
    		->where($where)
            ->group('customer_id')
            ->getRow(); 

		$this->assign('catlist',$catlist);                
		$this->assign('list',$list);
		$this->assign('c_name',$c_name);
		$this->assign('cuslist',$cuslist);
		$this->assign('remark',$cuslist['remark']);
		$this->display('customercredit.edit.html');
    }

  


	/**
	 * 用来查询选择公司
	 * @return [type] [description]
	 */
	public function query(){
	    //获取列表数据
	    $action=sget('action');
	    $doact=sget('do','s');
	    if($action=='grid'){
	        $page = sget("pageIndex",'i',0); //页码
	        $size = sget("pageSize",'i',20); //每页数
	        $sortField = sget("sortField",'s','c_id'); //排序字段
	        $sortOrder = sget("sortOrder",'s','desc'); //排序

	        $this->db=M('public:common')->model('customer');
	        //搜索条件
	        $where=" 1 ";
	        //状态
	        $status = sget("status",'s','');
	        // $status='2';//只显示正常
	        if($status!='') $where.=" and `status` = '$status' ";
	        //关键词
	        $keyword=sget('keyword','s');
	        if(!empty($keyword)){
	            $where.=" and `c_name` like '%$keyword%' ";
	        }
	        $list=$this->db->where($where)
	        ->page($page+1,$size)
	        ->order("$sortField $sortOrder")
	        ->getPage();
	        foreach($list['data'] as $k=>$v){
	            $list['data'][$k]['input_time']=$v['input_time']>1000 ? date("Y-m-d H:i:s",$v['input_time']) : '-';
	            $list['data'][$k]['update_time']=$v['update_time']>1000 ? date("Y-m-d H:i:s",$v['update_time']) : '-';
                    $list['data'][$k]['update_admin']=empty($v['update_admin'])?'-':$v['update_admin'];
	            $list['data'][$k]['status']=L('cus_cop_status')[$v['status']];
	             $list['data'][$k]['credit_status']=$v['credit_status']==1?'未评价':'已评价';
	        }
	        $result=array('total'=>$list['count'],'data'=>$list['data'],'msg'=>'');
	        $this->json_output($result);
	    }
	    $this->assign('doact',$doact);
//	    $this->assign('status','正常');
	    $this->assign('cus_cop_status',L('cus_cop_status')); //公司状态
//	    $this->assign('page_title','厂家管理');
	    $this->display('customer_query.list.html');
	}
        
}