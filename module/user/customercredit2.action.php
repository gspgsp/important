<?php
/**
*信用管理
*/
class customercredit2Action extends adminBaseAction
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
		$page = sget("pageIndex",'i',0); //页码
		$size = sget("pageSize",'i',20); //每页数
		if($action=='grid'){ //获取列表
			$this->_grid();exit;
		}
        //获取有效的指标       
        $list=$this->db->model('credit')
        				->where('status=1')
						->getAll();	                
           
        $list=$this->tree($list);
        foreach($list as $k=>$row){
			$id=$row['id'];
			if($row['pid']==0){
				$bools=$this->db->model('credit')
					->select("count(*)")
					->where("pid=$id and status=1")
					->getOne();
					// p($bools);
					// showTrace();
					// exit;
				if(empty($bools)){
					unset($list[$k]);
				}
			}

		}
			// 				Array
			// (
			//     [0] => Array
			//         (
			//             [id] => 4
			//             [name] => 哈利波特
			//             [pid] => 0
			//             [grade] => 50
			//             [remark] => 大法师打发斯蒂芬
			//             [status] => 1
			//             [input_time] => 1474441909
			//             [input_admin] => admin
			//             [update_time] => 1474523376
			//             [update_admin] => admin
			//             [level] => 0
			//             [html] => 
			//         )



            // echo '------------';
            //  var_dump($list);
            // p($list);
            // showTrace();
            // exit;
		$this->assign('list',$list);
		$this->display('customercredit2.list.html');
	}

	/**
	 * 判断是否评价
	 */
	public function checkCustomer(){
		$id=$_POST['id'];
		$name=$_POST['name'];
		//获取字段
		$c_name=$this->db->model('customer_credit_comment2')
              			->select('str_id')
              			->where("customer_id={$id}")
              			->getAll();

		if(!empty($c_name)){
			$this->error('评价过了该公司，如需再评价，请修改');	
		}			
	}

	/**
	 * 生成二叉树
	 */
	public function tree($list,$pid=0,$level=0,$html='&nbsp;'){
				static $tree = array();
					foreach($list as $v){
						if($v['pid'] == $pid){
						    if($v['status']==1){
						    	$v['level'] = $level;
						     	$v['html'] = str_repeat($html,$level*7);
						    	$tree[] = $v;
						    	$this->tree($list,$v['id'],$level+1);
						    }						    
						} 
					}
				return $tree;
			}

	/**
	 * 动态生成表格项
	 */
	public function someset(){
		$data=sget("ids",'s');
		//联表获取有效的指标
		 $list=$this->db->model('credit')
						->getAll();
		// p($list);
			
			$list=$this->tree($list);
			$tmp=array();
			foreach($list as $k=>$row){
				if($row['pid']>0){				
					$tmp[]=$row;					
				}
			}
				
			// var_dump($list);
			// var_dump($tmp);
			// exit;

		$this->json_output($tmp);
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
        	$where1="`c_name` like '%$keyword%' ";
        	$id_arr=$this->db->model('customer')
        			->where($where1)
        			->getCol();
	       	$id_arr=implode(',',$id_arr);
	       	$where.=" and customer_id in ($id_arr)";
			}

		//联表获取有效的指标
		 $list=$this->db->model('credit')
						->getAll();
		// p($list);
			
			$list=$this->tree($list);
			$tmp=array();
			foreach($list as $k=>$row){
				if($row['pid']>0){				
					$tmp[]=$row;					
				}
			}

		$select='';
		$select.='customer_id,input_time,input_admin,update_time,update_admin,';
		foreach($tmp as $row){
			$select.="max(case str_id when '{$row['id']}' then grade else 0 end ) str_id{$row['id']},";
			$select.="max(case str_id when '{$row['id']}' then credit_sum else 0 end ) credit_sum,";
		}
		$select=rtrim($select,',');
        $list=$this->db->model('customer_credit_comment2')
        		->where($where)
        		->select($select)
                ->page($page+1,$size)
                ->order("$sortField $sortOrder")
                ->group('customer_id')
                ->getPage();
                // var_dump($list);
                // showTrace();
                // exit;
                // 
                // array(2) {
				//   ["count"]=>
				//   string(1) "1"
				//   ["data"]=>
				//   array(1) {
				//     [0]=>
				//     array(11) {
				//       ["customer_id"]=>
				//       string(4) "9649"
				//       ["input_time"]=>
				//       string(10) "1474524850"
				//       ["input_admin"]=>
				//       string(5) "admin"
				//       ["update_time"]=>
				//       string(1) "0"
				//       ["update_admin"]=>
				//       string(0) ""
				//       ["credit_sum"]=>
				//       string(1) "5"
				//       ["str_id8"]=>
				//       string(1) "1"
				//       ["str_id6"]=>
				//       string(1) "1"
				//       ["str_id10"]=>
				//       string(1) "1"
				//       ["str_id11"]=>
				//       string(1) "1"
				//       ["str_id12"]=>
				//       string(1) "1"
				//     }
				//         [0]=>
				    // array(7) {
				    //   ["customer_id"]=>
				    //   string(4) "9647"
				    //   ["input_time"]=>
				    //   string(10) "1474534409"
				    //   ["input_admin"]=>
				    //   string(5) "admin"
				    //   ["update_time"]=>
				    //   string(10) "1474534546"
				    //   ["update_admin"]=>
				    //   string(5) "admin"
				    //   ["credit_sum"]=>
				    //   string(1) "3"
				    //   ["str_id10"]=>
				    //   string(1) "2"
				    // }
				//   }
				// }
         $tmp=array();
         
		foreach($list['data'] as $k=>$v){
			$list['data'][$k]['input_time']=$v['input_time']>1000 ? date("Y-m-d H:i:s",$v['input_time']) : '-';
			$list['data'][$k]['update_time']=$v['update_time']>1000 ? date("Y-m-d H:i:s",$v['update_time']) : '-';
			$list['data'][$k]['update_admin']=empty($v['update_admin'])?'-':$v['update_admin'];
			// 	$gsum=0;
			// 	foreach($v as $k1=>$v2){
			// 		if(substr($k1,0,6)=='str_id'){
   //          			$gsum+=$v2;
   //          		}
			// 	}
			// $list['data'][$k]['credit_sum']=$gsum;            	
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
 		$this->is_ajax=true; //指定为Ajax输出
        $data=$_POST;    
		$action = sget('action','s');
		if(empty($data)) $this->error('错误的请求');		
		if($action =='edit'){
			$this->db=M('public:common')->model('customer_credit_comment2');
			// var_dump($data);
			// array(9) {
			//   ["catname"]=>
			//   string(36) "西安瑞和化工塑料有限公司"
			//   ["credit_sum"]=>
			//   string(2) "12"
			//   ["customer_id"]=>
			//   string(4) "9647"
			//   ["str_id8"]=>
			//   string(1) "1"
			//   ["str_id6"]=>
			//   string(1) "3"
			//   ["str_id10"]=>
			//   string(1) "1"
			//   ["str_id11"]=>
			//   string(1) "5"
			//   ["str_id12"]=>
			//   string(1) "2"
			//   ["remark"]=>
			//   string(15) "阿斯顿发生"
			// }
			// showTrace();
			// exit;
			$tmp=array();
			$result=array();
			foreach($data as $key=>$value){
				$tmp['remark']=$data['remark'];
				$tmp['credit_sum']=$data['credit_sum'];

				if(substr($key,0,6)=='str_id'){
					$data[$key]+=0;
					$tmp['grade']=$data[$key];
					$grade=$tmp['grade'];
					$data['customer_id']+=0;
					$customer_id=$data['customer_id'];
					$key=ltrim($key,'str_id');
					// $sql="insert into p2p_customer_credit_comment2(customer_id,str_id,grade,credit_sum,remark,input_time,input_admin) values($customer_id,$key,$grade,$tmp['credit_sum'],$tmp['remark'],CORE_TIME,$_SESSION['name']) on duplicate key update "
					$bools=$this->db->where("customer_id=$customer_id and str_id=$key")->getRow();
					if(!$bools){
							$tmp['str_id']=$key;
						$tmp['customer_id']=$customer_id;
						$this->db->add($tmp+array('input_time'=>CORE_TIME, 'input_admin'=>$_SESSION['name'],));
						// p('--------');
						// var_dump($bools);
						// showTrace();
						// exit;
					}
					unset($tmp['str_id']);
					$result[]=$this->db->where("customer_id=$customer_id and str_id=$key")
									->update($tmp+array('update_time'=>CORE_TIME, 
									'update_admin'=>$_SESSION['name'],));

					// if(!$result){
					// 	$tmp['str_id']=$key;
					// 	$tmp['customer_id']=$customer_id;
					// 	$this->db->add($tmp+array('input_time'=>CORE_TIME, 'input_admin'=>$_SESSION['name'],));
					// 	// var_dump($result);
					// 		p('--------');
					// 	showTrace();
					// 	exit;
					// }
									
				}

			}
									// p('----------');
									// var_dump($result);
									// showTrace();
									// exit;
		}else{
                $this->db=M('public:common')->model('customer_credit_comment2');
				// array(8) {
				// ["com_id"]=>
				// string(4) "9649"
				// ["credit_sum"]=>
				// string(1) "5"
				// ["str_id8"]=>
				// string(1) "1"
				// ["str_id6"]=>
				// string(1) "1"
				// ["str_id10"]=>
				// string(1) "1"
				// ["str_id11"]=>
				// string(1) "1"
				// ["str_id12"]=>
				// string(1) "1"
				// ["remark"]=>
				// string(12) "大是大非"
				// }
    //             var_dump($data);
    //             showTrace();
    //             exit;
                $customer_id=$data['com_id'];
                unset($data['com_id']);
                foreach($data as $key=>$row){
                	$data1['customer_id']=$customer_id;
                	$data1['str_id']=ltrim($key,'str_id');
                	$data1['grade']=$row;
                	$data1['remark']=$data['remark'];
                	$data1['credit_sum']=$data['credit_sum'];
                	if(substr($key,0,6)=='str_id'){
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
			
			$result[]=$this->db->model('customer_credit_comment2')->where("customer_id=$v")->delete();
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
	
		//p($id);
		//获取有指标的分类
		// $catlist=$this->db->model('credit_cat as cat')
  //                               ->join('credit_lab as lab','lab.cat_id=cat.id')
  //                                ->select('cat.id,cat.catname') 
  //                               ->where('lab.status=1 and cat.status=1')
  //                               ->getAll();
  //       //二维数组去掉重复值
  //       function a_array_unique($array){
  //          $out = array();
  //          foreach ($array as $key=>$value) {
  //              if (!in_array($value, $out)){
  //                  $out[$key] = $value;
  //              }
  //          }
  //          return $out;
  //       }
                
  //        $catlist=a_array_unique($catlist);
        //连表查询，获取有效的指标       
        $list=$this->db->model('credit')
						->getAll();
		// p($list);
			function tree($list,$pid=0,$level=0,$html='&nbsp;'){
				static $tree = array();
					foreach($list as $v){
						if($v['pid'] == $pid){
						    if($v['status']==1){
						    	$v['level'] = $level;
						     	$v['html'] = str_repeat($html,$level*7);
						    	$tree[] = $v;
						    	tree($list,$v['id'],$level+1);
						    }						    
						} 
					}
				return $tree;
			}
                
           
            $list=tree($list);
            foreach($list as $k=>$row){
				$id=$row['id'];
				$list[$k]['str_id']='str_id'.$id;
				if($row['pid']==0){
					$bools=$this->db->model('credit')
						->select("count(*)")
						->where("pid=$id and status=1")
						->getOne();
						// p($bools);
						// showTrace();
						// exit;
					if(empty($bools)){
						unset($list[$k]);
					}
				}

			}

		//拼接sql语句
		$select='';
		$select.='customer_id,input_time,input_admin,update_time,update_admin,remark,';
		foreach($list as $key=>$row){
			$select.="max(case str_id when '{$row['id']}' then grade else 0 end ) str_id{$row['id']},";
			//$str='lab_id2';
			//$list[$key][$str]='field'.$row['lab_id'];
		}
		
		$select=rtrim($select,',');		
		$id=sget('id','i');
		$c_name=sget('c_name','s');
		$where="1";
		$where.=" and customer_id=$id";
    	$cuslist=$this->db->model('customer_credit_comment2')
    		->select($select)
    		->where($where)
            ->group('customer_id')
            ->getRow(); 

            // p($id);
            // p($c_name);
            //p($list);
            //[7] => Array
	        // (
	        //     [id] => 12
	        //     [name] => 孔雀翎
	        //     [pid] => 9
	        //     [grade] => 2
	        //     [remark] => 考核结果看
	        //     [status] => 1
	        //     [input_time] => 1474466225
	        //     [input_admin] => admin
	        //     [update_time] => 0
	        //     [update_admin] => 
	        //     [level] => 1
	        //     [html] =>  
	        //      [str_id] => str_id4      
	        // )
            //p($cuslist);
            //rray
			// (
			//     [customer_id] => 9647
			//     [input_time] => 1474526682
			//     [input_admin] => admin
			//     [update_time] => 0
			//     [update_admin] => 
			//     [remark] => 阿斯顿发生
			//     [str_id4] => 0
			//     [str_id8] => 1
			//     [str_id5] => 0
			//     [str_id6] => 3
			//     [str_id9] => 0
			//     [str_id10] => 3
			//     [str_id11] => 5
			//     [str_id12] => 2
			// )
            // showTrace();
            // exit;
		//$this->assign('catlist',$catlist);
		$url=FILE_URL.'/upload/16/09/14/57d9141f2aa2b.jpg';
		$this->assign('url',$url);               
		$this->assign('list',$list);
		$this->assign('c_name',$c_name);
		$this->assign('cuslist',$cuslist);
		$this->assign('remark',$cuslist['remark']);
		$this->display('customercredit2.edit.html');
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