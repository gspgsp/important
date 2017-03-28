<?php
/**
*开票管理控制器
*/
class billingAction extends adminBaseAction
{
	public function __init(){
		$this->db=M('public:common')->model('billing');
		$this->assign('bile_type',L('bile_type'));		 	 			//票据类型
		$this->assign('invoice_one_status',L('invoice_one_status'));    //开票状态
		$this->assign('billing_type',L('billing_type'));    			//开票类型
		$this->assign('company_account',L('company_account')); 			//交易公司账户
	}

	/**
	 *
	 * @access public
	 * @return html
	 */
	public function init(){
		//获取列表数据
		$action=sget('action');
		if($action=='grid'){ //获取列表
			$this->_grid();exit;
		}elseif($action=='billingLog'){
			$this->_billingLog();exit;
		}
		$this->assign('type','1');
		$this->assign('page_title','销售开票明细');
		$this->display('billing.list.html');
	}
	/**
	 *
	 * @access public
	 * @return html
	 */
	public function itin(){
		//获取列表数据
		$action=sget('action');
		if($action=='grid'){ //获取列表
			$this->_grid();exit;
		}
		$this->assign('type','2');
		$this->assign('page_title','采购开票明细');
		$this->display('billing.list.html');
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
		$where = 1;
		$type = sget('type','i');
		if ($type == 1) {
			$where .=" and `billing_type`= 1 ";
		}elseif($type ==2){
			$where .=" and `billing_type`= 2 ";
		}
		//tap项的传值搜索
		$oid = sget('oid','i',0);
		if($oid!=0) $where.=" and `o_id` = '$oid' ";
		//交易日期
		$sTime = sget("sTime",'s','payment_time'); //搜索时间类型
		$where.= getTimeFilter($sTime); //时间筛选
		//票据类型
		$bile_type = sget("bile_type",'s','');
		if($bile_type!='') $where.=" and `bile_type` = '$bile_type' ";

		//合同主题
		$order_name = sget("order_name",'s','');
		if($order_name!='') $where.=" and `order_name` = '$order_name' ";
		//开票状态
		$invoice_status = sget("invoice_one_status",'s','');
		if($invoice_status!='') $where.=" and `invoice_status` = '$invoice_status' ";

		//关键词搜索
		$key_type=sget('key_type','s','order_sn');
		$keyword=sget('keyword','s');
		if(!empty($keyword)){
			switch ($key_type) {
				case 'order_sn':
					$newword = "更正".$keyword;
					$where.=" and `order_sn` = '$keyword' or `order_sn` = '$newword'";
					break;
				case 'c_name':
					$c_ids = M('user:customer')->getInfoByCname($key_type,$keyword);
					$str_cids = implode(',',array_values($c_ids));
					$where.=" and `c_id` in ($str_cids)";
					break;
				case 'admin':
					$customer_manager = M('rbac:adm')->getAdmin_Id($keyword);
					$where.=" and `customer_manager` = $customer_manager";
					break;
				default:
					$where.=" and `$key_type`  = '$keyword' ";
					break;
			}
		}

		//筛选领导级别
		if($_SESSION['adminid'] != 1 && $_SESSION['adminid'] > 0){
			$sons = M('rbac:rbac')->getSons($_SESSION['adminid']);  //领导
			$where .= " and `customer_manager` in ($sons) ";
		}

		$list=$this->db->where($where)
					->page($page+1,$size)
					->order("$sortField $sortOrder")
					->getPage();
		foreach($list['data'] as $k=>$v){
			$list['data'][$k]['input_time']=$v['input_time']>1000 ? date("Y-m-d H:i:s",$v['input_time']) : '-';
			$list['data'][$k]['update_time']=$v['update_time']>1000 ? date("Y-m-d H:i:s",$v['update_time']) : '-';
			$list['data'][$k]['payment_time']=$v['payment_time']>1000 ? date("Y-m-d H:i:s",$v['payment_time']) : '-';
			//开票业务员
			$list['data'][$k]['input_admin']=M('rbac:adm')->getUserByCol($v['customer_manager']);
			//开票主题
			$list['data'][$k]['order_name'] = L('company_account')[$list['data'][$k]['order_name']];
			$list['data'][$k]['c_name']=M('user:customer')->getColByName($value=$v['c_id'],$col='c_name',$condition='c_id');

			empty($v['accessory'])?:$list['data'][$k]['accessory']=FILE_URL.'/upload/'.$v['accessory'];
			//每笔订单 收付款明细的审核状态
			$arr = M('product:billing')->getLastInfo($name='o_id',$value=$v['o_id']);
			$red_status = $this->db->where('invoice_status =1 and o_id='.$arr[0]['o_id'])->getAll();
			$list['data'][$k]['red_status']=empty($red_status)?0:1;

		}
		$msg="";
		if($list['count']>0){
			$sum=$this->db->select("sum(total_price) as tsum, sum(billing_price) as bsum, sum(unbilling_price) as usum")->where($where)->getRow();
			$msg="[合计]总额:【".price_format($sum['tsum'])."】已开票:【".$sum['bsum']."】未开票:【".$sum['usum']."】";
		}
		$result=array('total'=>$list['count'],'data'=>$list['data'],'msg'=>$msg);
		$this->json_output($result);
	}

	//检查开票申请满足条件
	public function chkaddInvoice(){
		if(!$_POST) $this->error('操作失败');
		$o_id = spost('o_id','s');
		$order_type = spost('order_type','s');
		$row_tmp['invoice_status'] = $this->db->model('order')->select("invoice_status")->where("o_id={$o_id}")->getOne();
		//如果不是超管
		if($_SESSION['adminid'] !=1){
		    if($order_type=='1'){//销售
	    	    //判断当前单子是否全部收款
	    	    $ifqbsk = $this->db->model('order')->select("count(0)")->where("o_id={$o_id} AND collection_status=3")->getOne();
	    	    //获取关联的订单号
	    	    $content_id = M('product:order')->getAssociationID($o_id);
	    	    //如果付款备注标有“破损”，销售不能开票
	    	    $msg_arr = $this->db->model('collection')->select('remark')->where("o_id={$o_id}")->getAll();
	    	    if(!empty($msg_arr)){
	    	    	foreach ($msg_arr as $value) {
	    	    		$arr[]=$value['remark'];
	    	    	}
	    	    }
	    	    $resu = preg_match("/破损/",implode($arr));
	    	    $roleid = M('rbac:rbac')->model('adm_role_user')->select('role_id')->where("`user_id` = {$_SESSION['adminid']}")->getOne();
	    	    //筛选财务,$res=1就属于财务
	    	    $res = in_array($roleid, array('30','26','27'))?1:0;
				if($resu==1 && $res==0){
					$v['msg']='该单存在破损，开票请联系财务处理！';
				}
    	        if($content_id>0){
        	        //通过获取到的关联订单号=>来获取关联订单的开票状态是否全部开票
        	        $ifqbkp = $this->db->model('order')->select("count(0)")->where("o_id={$content_id}  AND invoice_status=3")->getOne();
        	        if($ifqbsk!=1 || $ifqbkp!=1){
        	            $v['msg']='当前订单没有全部收款或关联订单没有全部开票!';
        	        }
    	        }else{
    	            $v['msg']='当前订单没有关联订单信息!';
    	        }

                //还需要判断开票表p2p_billing
                //1.没有开票记录待开票
                if($row_tmp['invoice_status']==1){
    	            $i = 0;
    	            $bfkps = $this->db->model('billing')->where("o_id={$o_id} AND invoice_status<>3")->getAll();
    	            $nom = count($bfkps);
    	            foreach ($bfkps as $k){
    	                if($k['invoice_status']<>2){
    	                    $v['msg']='已提交未确认!';
    	                    break;
    	                }else{
    	                    $i = $i +1;
    	                }
    	            }
    	            if($i==$nom){
    	                // $v['msg']=1;
    	            }
    	        }
    	        //2.部分开票
    	        if($row_tmp['invoice_status']==2){
    	            $i = 0;
    	            $bfkps = $this->db->model('billing')->where("o_id={$o_id} AND invoice_status<>3")->getAll();
    	            foreach ($bfkps as $k){
    	                if($k['invoice_status']<>2){
    	                    $v['msg']='部分开票有未完成!';
    	                    break;
    	                }else{
    	                    $i = $i +1;
    	                }
    	            }
    	            if($i!=0&&($i==count($bfkps))){
    	            }
    	        }
    	        //3.全部开票
    	       // p($row_tmp['invoice_status']);
    	        //die;
    	        if($row_tmp['invoice_status']==3){
    	            $v['msg']='已全部开票,开票已完成!';
    	        }

    	        if(empty($v['msg'])){
    	           $this->success('操作成功');
    	        }else{
    	            $this->error($v['msg']);
    	        }
		    }else{
		        $this->success('操作成功');
		    }
		}else{
			$bfkp = $this->db->model('billing')->where("o_id={$o_id} AND invoice_status=1")->getOne();
			if ($bfkp) {
				$this->error('等待审核中');
			}else{
				$this->success('操作成功');
			}

		}

	}

	//开票
	public function transactionInfo(){
		$o_id=sget('o_id','i',0);
		if(empty($o_id)) $this->error('信息错误');
		$this->type=sget('order_type','s');//type=1为销售订单，type=2为采购订单
		$this->is_head=sget('is_head','i',0);
		$id = sget('id','i',0);
		$this->assign('id',$id);
		$this->assign('o_id',$o_id);
		$finance=sget('finance','i');
		$this->assign('finance',$finance);

		//开票申请与审核时添加最后收付款的时间
		$res = M('product:collection')->getLastInfo($name='o_id',$value="$o_id");
		$this->assign('payment_time',date("Y-m-d H:i:s",$res[0]['payment_time']));
		if ($finance ==1 ) {
			//开票审核
			$headData=$this->db->model('billing')
				->wherePk($id)
				->select('rise as c_name,total_price,billing_price,unbilling_price,order_sn,order_name,c_id')
				->getRow();
			$this->assign('headData',$headData);

		}else{
			//开票申请
			//未付款金额
			if($this->type==1){
				$modelName='sale_log';
			}else{
				$modelName='purchase_log';
			}

			$b_data=$this->db->model("$modelName")->where("o_id=$o_id")->getAll();
			if($b_data){
				foreach ($b_data as $k => $v) {
					$un_pay+= $v['unit_price']*$v['b_number'];
				}
			}
			$this->un_pay = round($un_pay,2);
			$headData=$this->db->from('order o')
				->join('customer c','o.c_id=c.c_id')
				->where("o.o_id=$o_id")
				->select('c.c_name,o.total_price,o.order_name,o.o_id,o.c_id,o.order_sn')
				->getRow();
			$this->assign('headData',$headData);

		}
		//公司开票资料

		$this->companyInfo=M("user:customer_billing")->getCinfoById($headData['c_id']);
		// $invoice_account=desDecrypt($this->companyInfo['invoice_account']);
		$invoice_account=$this->companyInfo['invoice_account'];

		$this->assign('invoice_account',$invoice_account);

		$this->display('billing.add.html');

	}

	public function ajaxSave(){
		$data = sdata();
		$type = sget('do','i');//区分销售采购订单
		//获取未开完票的订单明细的个数$nus
		if($type==1){//销售
			$nus = $this->db->model('sale_log')->where("o_id={$data['o_id']} and b_number !=0")->select("count('id')")->getOne();
		}else{
			$nus = $this->db->model('purchase_log')->where("o_id={$data['o_id']} and b_number !=0")->select("count(id)")->getOne();
		}

		$detail = $data['detail'];
		unset($data['detail']);
		$data['payment_time']=strtotime($data['payment_time']);
		$this->db->startTrans();
		if($data['finance']==1){
			//获取财务审核开票的时间
			$spend_time = CORE_TIME-$this->db->model('billing')->select('input_time')->where('id='.$data['id'])->getOne();
			//财务审核开票信息
			$_data = array(
				'update_time'=>CORE_TIME,
				'update_admin'=>$_SESSION['username'],
				'invoice_status'=>2,
				'payment_time'=>CORE_TIME,
				'unbilling_price'=>'-='.$data['billing_price'],
				);
				//销售开票号去重
				// if ($type==1) {
				// 	$res = M('product:billing')->curUnique('invoice_sn',$data['invoice_sn']);
				// 	if(!$res) $this->error("发票号重复,请更换！");
				// }
				if(!$this->db->model('billing')->where("id={$data['id']}")->update($_data+$data)) $this->error("开票审核更新表头失败");
				foreach ($detail as $v) {
					if(!$this->db->model('billing_log')->where("id={$v['id']}")->update(array('update_time'=>CORE_TIME,'status'=>2,))) $this->error("开票明细更新失败");
					if($type==1){//销售
						$b_number=$this->db->model('sale_log')->where("id={$v['l_id']}")->select('b_number')->getOne();
						if($v['b_number']>$b_number) $this->error("开票数量不能大于未开票数量");
						if(!$this->db->model('sale_log')->where("id={$v['l_id']}")->update(array('b_number'=>'-='.$v['b_number'],))) $this->error("销售明细更新失败");


					}else{
						$b_number=$this->db->model('purchase_log')->where("id={$v['l_id']}")->select('b_number')->getOne();
						if($v['b_number']>$b_number) $this->error("开票数量不能大于未开票数量");
						if(!$this->db->model('purchase_log')->where("id={$v['l_id']}")->update(array('b_number'=>'-='.$v['b_number'],))) $this->error("采购明细更新失败");

					}
				}
				if($this->db->commit()){
					if($this->db->model('billing_log')->where("status=1 and parent_id={$data['id']}")->getRow()){
						if(!$this->db->model('billing_log')->where("status=1 and parent_id={$data['id']}")->delete()) $this->error("开票明细删除失败");
					}
					//2是部分开票，3是全部开票
					if ($nus-count($detail)==0) {
						$istatus = ($b_number-$v['b_number'] == 0) ? 3 : 2;
					}else{
						$istatus = 2;
					}

    				$unBillingPrice = $data['unbilling_price']-$data['billing_price'];
					if(!M('order:orderLog')->addLog($data['o_id'],$istatus,3,$spend_time,$data['total_price'],$data['total_price']-$unBillingPrice,$unBillingPrice)) $this->error("更新可视化失败");

					$this->db->model('order')->wherePk($data['o_id'])->update(array("invoice_status"=>$istatus,"update_time"=>CORE_TIME,"update_admin"=>$_SESSION['username']));
					$this->success('操作成功');
				}else{
					$this->db->rollback();
					$this->error('保存失败：'.$this->db->getDbError());
				}
		}else{
			//业务员提交申请开票
			$data['input_time']=CORE_TIME;
			$data['input_admin']=$_SESSION['username'];
			$data['order_name']	=$data['title'];
			//根据o_id获取订单中的业务员id,即customer_manager
			$customer_manager = $this->db->model('order')->select('customer_manager')->where('o_id='.$data['o_id'])->getOne();
			$data['customer_manager']=$customer_manager;

			//根据c_id获取开票资料中的邮寄地址
			$express_address = $this->db->model('customer_billing')->select('ems_address')->where('c_id='.$data['c_id'])->getOne();
			$data['express_address']=$express_address;

			//判断生成开票号,审核时才有
			$date=date("Ymd").str_pad(mt_rand(0, 100), 3, '0', STR_PAD_LEFT);
			$data['billing_type']==1?($data['billing_sn']= 'sk'.$date):($data['billing_sn']= 'pk'.$date);
				if(!$this->db->model('billing')->add($data)) $this->error("开票申请表头添加失败");
				$parent_id=$this->db->getLastID();
				foreach ($detail as $key => $value) {
					if($value['b_number']>$value['un_number']) $this->error("开票数量不能大于未开票数量");
					$log_data=array(
						'parent_id'=>$parent_id,
						'l_id'=>$value['id'],
						'p_id'=>$value['p_id'],
						'number'=>$value['number'],
						'b_number'=>$value['b_number'],
						'type'=>$value['type'],
						'model'=>$value['model'],
						'f_name'=>$value['f_name'],
						'unit_price'=>$value['unit_price'],
						'input_time'=>CORE_TIME,
						'input_admin'=>$_SESSION['username'],
					);
					if(!$this->db->model('billing_log')->add($log_data)) $this->error("开票明细添加失败");
				}
				if($this->db->commit()){
					$this->success('操作成功');
				}else{
					$this->db->rollback();
					$this->error('保存失败：'.$this->db->getDbError());
				}
		}
	}


	protected function _billingLog(){
		$page = sget("pageIndex",'i',0); //页码
		$size = sget("pageSize",'i',20); //每页数
		$sortField = sget("sortField",'s','id'); //排序字段
		$sortOrder = sget("sortOrder",'s','desc'); //排序
		$type=sget('type','i',0);

		if($is_head=sget('is_head','i',0)){
			$o_id=sget('o_id','i',0);

			if($type==1){
				//销售明细
				$list=M('product:saleLog')->getLogListByOid($o_id,$page,$size);
			}else{
				//采购明细
				$list=M('product:purchaseLog')->getLogListByOid($o_id,$page,$size);
			}
		}else{
			$id=sget('id','i',0);
			if($type==1){
				$listModel=$this->db->from('billing_log b')
					->join('sale_log l','b.l_id=l.id')
					->join('store st','l.store_id=st.id')
					->select('b.*,l.b_number as u_number,l.lot_num,st.store_name');
			}else{
				$listModel=$this->db->from('billing_log b')
					->join('purchase_log l','b.l_id=l.id')
					->select('b.*,l.b_number as u_number');
			}
			$list=$listModel->where("b.parent_id=$id")->page($page,$size)->getPage();
		}
		foreach ($list['data'] as &$value) {
			$value['sum']=price_format(round(floatval($value['b_number']*$value['unit_price']),2));
			//$value['sum']=floatval($value['b_number']*$value['unit_price']);
			if($is_head){
				$value['un_number']=$value['b_number'];
				$value['type']=L("product_type")[$value['type']];
			}else{
				$value['un_number']=$value['u_number'];
			}
		}
		$result=array('total'=>$list['count'],'data'=>$list['data'],'msg'=>'');
		$this->json_output($result);

	}

	public function billingLog(){
		$page = sget("pageIndex",'i',0); //页码
		$size = sget("pageSize",'i',20); //每页数
		$sortField = sget("sortField",'s','id'); //排序字段
		$sortOrder = sget("sortOrder",'s','desc'); //排序
		$type=sget('type','i',0);
		if($is_head=sget('is_head','i',0)){
			$o_id=sget('o_id','i',0);
			if($type==1){
				//销售明细
				$list=M('product:saleLog')->getLogListByOid2($o_id,$page,$size);
			}else{
				//采购明细
				$list=M('product:purchaseLog')->getLogListByOid2($o_id,$page,$size);
			}

		}else{
			$id=sget('id','i',0);
			if($type==1){
				$listModel=$this->db->from('billing_log b')
					->leftjoin('sale_log l','b.l_id=l.id')
					->leftjoin('store st','l.store_id=st.id')
					->select('b.*,l.b_number as u_number,l.lot_num,st.store_name');
			}else{
				$listModel=$this->db->from('billing_log b')
					->join('purchase_log l','b.l_id=l.id')
					->select('b.*,l.b_number as u_number');
			}
			$list=$listModel->where("l.b_number!=0 and b.parent_id=".$id)->page($page,$size)->getPage();
		}
		// showTRace();
		// p($list);
		foreach ($list['data'] as &$value) {
			$value['sum']=price_format(round(floatval($value['b_number']*$value['unit_price']),2));
			$value['exact']=$value['sum'];
			if($is_head){
				$value['un_number']=$value['b_number'];
				$value['type']=L("product_type")[$value['type']];

			}else{
				//财务要求的产品分类
	//聚乙烯：HDPE、LDPE、LLDPE、EVA，聚丙烯：均聚PP、共聚PP，塑料ABS：ABS、MABS，塑料PC:PC,聚苯乙烯:HIPS、GPPS
				$value['type']=$value['type'];
				$t = $value['type'];
				if($t=='HDPE'||$t=='LDPE'||$t=='LLDPE'||$t=='EVA'){
					$value['n_type'] =1;
				}elseif($t=='均聚PP'||$t=='共聚PP'){
					$value['n_type'] =2;
				}elseif($t=='ABS'||$t=='MABS'){
					$value['n_type'] =3;
				}elseif($t=='PC'){
					$value['n_type'] =4;
				}elseif($t=='HIPS'||$t=='GPPS'){
					$value['n_type'] =5;
				}else{
					$value['n_type'] =6;
				}
				$value['f_type']=L("finance_p_type")[$value['n_type']];
				$value['un_number']=$value['u_number'];
			}
		}
		$result=array('total'=>$list['count'],'data'=>$list['data'],'msg'=>'');
		$this->json_output($result);

	}
	//红字更正
	public function changeRed(){
		$this->is_ajax=true;
		$id=sget('id','i','0');
		$billingModel=M("product:billing");
		$orderModel=M("product:order");
		$billingLogModel=M('product:billingLog');
		$purchaseLogModel=M('product:purchaseLog');
		$saleLogModel=M('product:saleLog');
		if(!$data=$billingModel->where("id=$id")->getRow()) $this->error('信息不存在');

		$list=$billingLogModel->where("parent_id=$id")->getAll();
		unset($data['id']);
		$data['billing_sn']="更正".$data['billing_sn'];
		$data['input_time']=CORE_TIME;
		$data['input_admin']=$_SESSION['username'];
		$data['update_time']='';
		$data['update_admin']='';
		$data['invoice_status']=3;
		$data['unbilling_price']=$data['unbilling_price']+$data['billing_price'];

		$billingModel->startTrans();
		try {
			$billingModel->where("id=$id")->update(array("invoice_status"=>3,"update_time"=>CORE_TIME,"update_admin"=>$_SESSION['username']));
			$priarr = $billingModel->where("id=$id")->select('billing_price,unbilling_price')->getRow();
			$billingModel->add($data);
			foreach ($list as $key => $value) {
				unset($value['id']);
				$value['status']=3;
				$value['input_time']=CORE_TIME;
				$value['input_admin']=$_SESSION['username'];
				$value['update_time']='';
				$value['update_admin']='';
				$billingLogModel->add($value);
				if($data['billing_type']==1){
					$saleLogModel->where("id={$value['l_id']}")->update("b_number=b_number+{$value['b_number']}");
					$billingNumber+=(int)$saleLogModel->where("id={$value['l_id']}")->select("number-b_number")->getOne();
					$pri+=(int)$saleLogModel->where("id={$value['l_id']}")->select("(number-b_number)*unit_price")->getOne();
				}else{
					$purchaseLogModel->where("id={$value['l_id']}")->update("b_number=b_number+{$value['b_number']}");
					$billingNumber+=(int)$purchaseLogModel->where("id={$value['l_id']}")->select("number-b_number")->getOne();
					$pri+=(int)$purchaseLogModel->where("id={$value['l_id']}")->select("(number-b_number)*unit_price")->getOne();
				}
			}
			if($billingNumber==0){
				$invoice_status=1;
			}else{
				$invoice_status=2;
			}
			$orderModel->where("o_id={$data['o_id']}")->update(array("invoice_status"=>$invoice_status,"update_time"=>CORE_TIME,"update_admin"=>$_SESSION['username']));

			//新增可视化节点
			M('order:orderLog')->addLog($data['o_id'],$invoice_status,3,0,$data['total_price'],$pri,$data['total_price']-$pri);

		} catch (Exception $e) {
			$billingModel->rollback();
			$this->error($e->getMessage());
		}
		$billingModel->commit();
		$this->success('操作成功');

	}


	// public function info(){
	// 	$id=sget('id','i',0);

	// 	$headData=$this->db->model('billing')
	// 		->wherePk($id)
	// 		->select('rise as c_name,total_price,billing_price,unbilling_price,order_sn,order_name,tax_price,bile_type,remark,invoice_sn')
	// 		->getRow();
	// 	$this->assign('headData',$headData);
	// 	$this->assign('id',$id);
	// 	$this->display('billing.info');
	// }

	// public function infoList(){
	// 	$id=sget('id','i',0);
	// 	$list=$this->db->from('billing_log')->where("parent_id=$id")->getAll();
	// 	foreach ($list as &$value) {
	// 		$value['sum']=floatval($value['b_number']*$value['unit_price']);
	// 	}
	// 	$result=array('data'=>$list);
	// 	$this->json_output($result);
	// }


	/**
	 * 保存行内编辑数据
	 * @access public
	 * @return html
	 */
	public function save(){
		$this->is_ajax=true; //指定为Ajax输出
		$data = sdata(); //获取UI传递的参数
		if(empty($data)) $this->error('错误的操作');
		$sql=array();
		foreach($data as $v){
			$_id=$v['id'];
			if($_id>0){
				$update=array(
					'payment_time'=>strtotime($v['payment_time']),
					'input_time'  =>strtotime($v['input_time']),
					'update_time' =>CORE_TIME,
					'update_admin'=>$_SESSION['name'],
					'remark'      =>$v['remark'],
					'paying_info' =>$v['paying_info'],
            		'receipt_info'=>$v['receipt_info'],
				);
				$sql[]=$this->db->wherePk($_id)->updateSql(saddslashes($update));
			}
		}
		if(empty($sql)){
			$this->error('操作数据为空');
		}
		$result=$this->db->commitTrans($sql);
		if($result){
			$cache=cache::startMemcache();
			$cache->delete('billing');
			$this->success('操作成功');
		}else{
			$this->error('数据处理失败');
		}
	}

	/**
	 *撤销开票时,返还订单明细数量
	 */
	public function returnNumber($_ids,$type){
		//p($_ids);p($type);die;
		foreach ($_ids as $v) {
			if ($type ==1) {
				$this->db->model('sale_log')->where('id='.$v['sale_log_id'])->update("`billing_number`=billing_number-".$v['number']);
			}else{
				$this->db->model('purchase_log')->where('id='.$v['purchase_log_id'])->update("`billing_number`=billing_number-".$v['number']);
			}
		}
	}

	// /**
	//  * 处理数量
	//  * @access private
	//  */
	// public function changeNumber(){
	// 	$this->is_ajax=true; //指定为Ajax输出
	// 	$data = sdata(); //获取UI传递的参数
	// 	$index = sget('index','i');
	// 	if(empty($data)) $this->error('错误的操作');
	// 	//for循环遍历
	// 	$unit_price =0;
	// 	for($i=0;$i<count($data);$i++){
	// 		$unit_price = ($data[$index]['unit_price']);
	// 		continue;
	// 	}
	// 	$result=array('unit_price'=>$unit_price);
	// 	$this->json_output($result);
	// 	//$this->assign('unit_price',$unit_price);
	// 	//p($unit_price);

	//}

	/**
	 * 发票号去重
	 */
	public function curUnique(){
		$data = trim($_POST['data']);
		if(empty($data)) $this->error('请填写发票号');
		$res = M('product:billing')->curUnique('invoice_sn',$data);
		if(!$res) $this->error("发票号重复,请更换！");
	}

}