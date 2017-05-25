<?php
/**
*收付款管理控制器
*/
class collectionAction extends adminBaseAction
{
	public function __init(){
		$this->db=M('public:common')->model('collection');
		$this->assign('pay_method',L('pay_method'));		 	//付款方式
		$this->assign('invoice_status',L('invoice_status'));    //开票状态
		$this->assign('company_account',L('company_account'));  //交易公司账户
		//财务选择付款是否完成时的语言包
		$cps = L('collection_p_status');
		unset ($cps[1]);
		$this->assign('collection_p_status',$cps);  //交易公司账户

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
		}
		$this->assign('order_sn',sget('order_sn','s'));
		$this->assign('type','1');
		$this->assign('collection_status',L('gatheringt_status'));    //订单收款状态
		$this->assign('page_title','销售收款明细');
		$this->display('collection.list.html');
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

		$this->assign('collection_status',L('payment_status'));  //订单付款状态
		$this->assign('page_title','采购付款明细');
		$this->display('collection.list.html');
	}

		/**
	 * Ajax获取列表内容
	 * @access private
	 * @return html
	 */
	private function _grid(){
		$_SESSION["collection_token"]=md5(rand(1,999));			//付款表单验证token

		$page = sget("pageIndex",'i',0); //页码
		$size = sget("pageSize",'i',20); //每页数
		$sortField = sget("sortField",'s','input_time'); //排序字段
		$sortOrder = sget("sortOrder",'s','desc'); //排序
		//搜索条件
		$where ="  1 and o_id > 0";
		$order_sn=sget('order_sn','s');
		if($order_sn)  $where.=" and `order_sn` = '$order_sn' ";
		$type = sget('type','i');//1销售,2采购
		if ($type == 1) {
			$where .=" and `order_type`=1 ";
		}elseif($type ==2){
			$where .="  and `order_type`=2 ";
		}
		$o_id=sget('oid','i',0);
		if($o_id !=0)  $where.=" and `o_id` =".$o_id;
		//交易日期
		$sTime = sget("sTime",'s','payment_time'); //搜索时间类型
		$where.= getTimeFilter($sTime); //时间筛选
		//交易方式
		$pay_method = sget("pay_method",'s','');
		if($pay_method!='') $where.=" and `pay_method` = '$pay_method' ";
		//收付款审核状态
		$collection_status = sget("collection_status",'s','');
		if($collection_status!='') $where.=" and `collection_status` = '$collection_status' ";

		//付款主题
		$title = sget("title",'s','');
		if($title!='') $where.=" and `title` = '$title' ";

		//交易公司类型
		// $company_account = sget("company_account",'s','');
		// if($company_account!='') $where.=" and `account` = '$company_account' ";

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

		// p($where);die;
		$list=$this->db->where($where)
					->select("c.*,a.name")
					->from('collection c')
					->join('admin as a','a.admin_id=c.customer_manager')
					->page($page+1,$size)
					->order("$sortField $sortOrder".', payment_time DESC')
					->getPage();
			// $list=$this->db->where($where)
			// 		->page($page+1,$size)
			// 		->order("$sortField $sortOrder".', payment_time DESC')
			// 		->getPage();
		foreach($list['data'] as $k=>$v){
			$list['data'][$k]['input_time']=$v['input_time']>1000 ? date("Y-m-d H:i:s",$v['input_time']) : '-';
			$list['data'][$k]['update_time']=$v['update_time']>1000 ? date("Y-m-d H:i:s",$v['update_time']) : '-';
			$list['data'][$k]['payment_time']=$v['payment_time']>1000 ? date("Y-m-d H:i:s",$v['payment_time']) : '-';
			//收付款主题
			$list['data'][$k]['title'] = L('company_account')[$list['data'][$k]['title']];

			$list['data'][$k]['c_name']=M('user:customer')->getColByName($value=$v['c_id'],$col='c_name',$condition='c_id');
			//开票状态
			$list['data'][$k]['invoice_status']=M('product:order')->getColByName($value=$v['o_id'],$col='invoice_status',$condition='o_id');
			// $list['data'][$k]['is_new_collection']=M('product:order')->getColByName($value=$v['o_id'],$col='is_new_collection',$condition='o_id');

			//每笔订单 收付款明细的审核状态
			$arr = M('product:collection')->getLastInfo($name='o_id',$value=$v['o_id']);
			$red_status = $this->db->where('collection_status =1 and o_id='.$arr[0]['o_id'])->getAll();
			$list['data'][$k]['red_status']=empty($red_status)?0:1;
			//附件下载
			empty($v['accessory'])?:$list['data'][$k]['accessory']=FILE_URL.'/upload/'.$v['accessory'];
		}

		$msg="";
		if($list['count']>0){
			$sum=$this->db->select("sum(total_price) as tsum, sum(collected_price) as csum, sum(uncollected_price) as usum")->where($where)->getRow();
			if ($type == 1) {
				$msg="[合计]总额:【".price_format($sum['tsum'])."】已收款:【".$sum['csum']."】未收款:【".$sum['usum']."】";
			}elseif($type ==2){
				$msg="[合计]总额:【".price_format($sum['tsum'])."】已付款:【".$sum['csum']."】未付款:【".$sum['usum']."】";
			}
		}
		$result=array('total'=>$list['count'],'data'=>$list['data'],'msg'=>$msg);
		$this->json_output($result);
	}

	/**
	* 付款收款信息
	* @access public
	*/
	public function transactionInfo(){
		$o_id=sget('o_id','i',0);
		$type=sget('order_type','s');//type=1为销售订单，type=2为采购订单

		if(empty($o_id)) $this->error('信息错误');
		$data      = M('product:order')->getAllByName($value=$o_id,$condition='o_id');
		$c_info    = M('user:customer')->getCinfoById($data[0][c_id]);//获取公司所有信息
		// showtrace();
		//p($c_info);die;
		$user_name = M('rbac:adm')->getUserInfoById($data[0][customer_manager]);//获取前台添加的业务员名字
		$username  = $user_name['name'];

		//订单中没有业务员id就传input_admin过去
		if (empty($username)) {
			$this->assign('input_admin',$data[0][input_admin]);
		}else{
			$this->assign('input_admin',$username);
		}
		//传递表头信息
		$this->assign('p_method',$data[0]['pay_method']);
		$this->assign('order_name',$data[0]['order_name']);
		$this->assign('c_name',$c_info['c_name']);
		$this->assign('c_id',$data[0]['c_id']);
		$this->assign('type',$type);
		$this->assign('o_id',$o_id);
		$this->assign('price',$data[0]['total_price']);
		$this->assign('order_sn',$data[0]['order_sn']);
		$this->assign('finance_type',$data[0]['finance']);
		//获取是不是财务审核
		$finance=sget('finance','i');
		$handling_charge =  $this->db->model('collection')->select("IFNULL(SUM(handling_charge),0) AS handling_charge")->where("o_id='".$data[0]['o_id']."'")->getOne();
		if($handling_charge==0)$handling_charge='';
		$this->assign('handling_charge',$handling_charge);
		//获取已付款金额
		$collected_price =  $this->db->model('collection')->select("IFNULL(SUM(collected_price),0) AS collected_price")->where("o_id='".$data[0][o_id]."'")->getOne();


			if ($finance ==1 ) {
				//获取要审核的收付款的id，传送出信息
				$id = sget('id','i',0);
				$this->assign('finance',$finance);
				$this->assign('id',$id);
				$res = M('product:collection')->where('id='.$id)->getRow();

				// p($res);die;
				// p($res);
				$team_capital = M('rbac:adm')->getThisMonthTemaCapitalByCustomer($res['customer_manager']);
				if($res){
					$un_price = $res['total_price']-($collected_price-$res['collected_price']);
					$this->assign('c_price',$res['collected_price']);
					$this->assign('u_price',$un_price);
					$this->assign('remark',$res['remark']);//备注
					$this->assign('team_capital',$team_capital);
				}
			}else{
				if($type == 2){
					$roleid = $this->db->model('adm_role_user as `user`')
									   ->select('role.pid')
									   ->leftjoin('adm_role as role','role.id = `user`.role_id')
									   ->where("`user_id` = ".$data[0]['customer_manager'])
									   ->getOne();
					if($roleid == 22){
						//有战队，要考虑配资情况
						$team_capital = M('rbac:adm')->getThisMonthTemaCapitalByCustomer($data[0]['customer_manager']);
						if(empty($team_capital)){
							$this->error('您所属战队配资未设置，请找主管设置指标');
						}
						$this->assign('team_capital',$team_capital);
						//检查特批列表中，是否存在相同订单特批申请未处理
						//检查特批列表中，是否存在相同业务员特批申请未处理
						$team_approve_same_oid = M('product:teamApprove')->getTeamApproveResByOid($data[0]['o_id']);
						if($team_approve_same_oid){
							//相同订单特批申请未处理
							$this->error('您该订单因超战队配资额度，已提交特批处理，待特批处理后，才可点击付款按钮');
						}
						$team_approve_same_admin = M('product:teamApprove')->getTeamApproveResByCustomerManager($data[0]['customer_manager']);
						if($team_approve_same_admin){
							//相同业务员特批申请未处理
							$this->error('您有特批申请待处理，待处理后，才可点击付款按钮');
						}
					}else{
						//无战队，属于其他情况，指标要设，但是不考虑超出指标到特批这一环节
						$team_capital = M('rbac:adm')->getThisMonthTemaCapitalBySpecialTeamId();
						if(empty($team_capital)){
							$this->error('您所属战队配资未设置，请找主管设置指标');
						}
						$this->assign('team_capital',$team_capital);
					}
				}
				//获取最后一条收付款信息
				$res = M('product:collection')->getLastInfo($name='o_id',$value=$data[0][o_id]);
				// p($data);die;
				//$res 结果集慎用 ----yezhongbao
				if($res){
					$uncollected_price = $data[0]['total_price'] -  $collected_price;
					$this->assign('total_price',$data[0]['total_price']);
					$this->assign('uncollected_price',$uncollected_price);
					$this->assign('remark',$res[0]['remark']);//备注
				}
			}
			$this->display('collection.add.html');

	}

	/**
	* 检查收付款状态
	*/
	public function chkCollecteprice(){
		$this->is_ajax=true; //指定为Ajax输出
		$data=sdata();
		if(empty($data)) $this->error('信息错误');
		$status = $this->db->model('collection')->select('collection_status')->where("o_id=".$data['o_id'])->order('id desc')->getOne();
		$has_price = $this->db->model('collection')->select("sum(collected_price) as has_price")->where("o_id=".$data['o_id'])->getOne();
		//$status不等于2表示没有提交申请或者没有审核，此两种状态都可以提交
		if($data['finance']){
			if($status==2){
				$this->error('重复审核');
			}else{
				$this->success('没有重复审核');
			}
		}else{
			if ((float)$data['total_price']<(float)$has_price) {
			//总金额小于已付款金额
				$this->error('提交数据有误');
			}
			if($status==1){
				$this->error('重复提交');
			}else{
				$this->success('没有重复提交');
			}
			if($status==2){
				$this->error('重复审核');
			}else{
				$this->success('没有重复审核');
			}
		}

	}

	/**
	* 保存付款收款信息
	*/
	public function ajaxSave(){
		$data=sdata();
		// p($data);die;
		$o_id = sget('o_id','i',0);  // 订单号
		if ($data['collection_token'] != $_SESSION['collection_token']) {
			$this->error("非法提交数据");
			unset($_SESSION['collection_token']);
		}else{
			unset($_SESSION['collection_token']);
		}
		//根据o_id获取订单中的业务员id,即customer_manager
		$customer_manager = $this->db->model('order')->select('customer_manager')->where('o_id='.$data['o_id'])->getOne();
		$data['customer_manager']=$customer_manager;
		$data['payment_time']=strtotime($data['payment_time']);

		//保存收付款相关信息
		if(empty($data['uncollected_price'])){
			//$this->db->model('order')->where('o_id='.$data['o_id'])->update('total_price ='.$data['total_price'].',invoice_status=1');//修改订单总金额取消了
			$m = $data['total_price']-$data['collected_price'];  // $data['collected_price'] 申请金额
			if ($m<0 && $data['finance_type']==1 &&$data['finance'] ==1) {
				$data['collected_price']=$data['total_price'];
				$data['handling_charge']=-$m;
				$data['uncollected_price']=0;
			}
		}else{
			$m = $data['uncollected_price']-$data['collected_price'];
			if ($m<0 && $data['finance_type']==1 &&$data['finance'] ==1) {
				$data['collected_price']=$data['uncollected_price'];
				$data['handling_charge']=-$m;
				$data['uncollected_price']=0;
			}
		}
// p($data);die;
		$this->db->startTrans();//开启事务

			if($data['finance'] ==1){
				//获取审核耗时。财务审核耗时由当前时间减去销售申请时间
				$spend_time = CORE_TIME-$this->db->model('collection')->select('input_time')->where('id='.$data['id'])->getOne();
				//供应链金融订单
				if($data['finance_type']==1){
					if(!$this->db->model('order')->where('o_id='.$data['o_id'])->update(array('collection_status'=>$data['collection_status'],'update_time'=>CORE_TIME))) $this->error("更新订单交易状态失败");
					if($data['handling_charge']==''){
						$data['uncollected_price']=$m;
						$h_charge=$this->db->model('collection')->select("sum(handling_charge)")->where("o_id='".$data['o_id']."'")->getOne();
						if (!empty($h_charge)) {
							$t_price=$data['total_price']+$h_charge;
							$Received_payment=$data['total_price']-$data['uncollected_price']+$h_charge;
						}else{
							$t_price=$data['total_price'];
							$Received_payment=$data['total_price']-$data['uncollected_price'];
						}
					}else{
						$data['uncollected_price']=0;
						$t_price=$data['total_price']+$data['handling_charge'];
						$Received_payment=$t_price;
					}
					if(!M('order:orderLog')->addLog($data['o_id'],$data['collection_status'],2,$spend_time,$t_price,$Received_payment,$data['uncollected_price'])) $this->error("更新可视化失败");
					$id = $data['id'];
					unset($data['id']);
					$data['collection_status'] = 2;
					//更新收付款信息
					if(!$re=$this->db->model('collection')->where('id='.$id)->update($data+array('update_time'=>CORE_TIME, 'update_admin'=>$_SESSION['username']))) $this->error("交易失败6");

				}else{

					//非供应链金融订单
					if($m>0){
						if(!$this->db->model('order')->where('o_id='.$data['o_id'])->update(array('collection_status'=>2,'update_time'=>CORE_TIME))) $this->error("更新订单交易状态失败");
						if(!M('order:orderLog')->addLog($data['o_id'],2,2,$spend_time,$data['total_price'],$data['total_price']-$m,$m)) $this->error("更新可视化失败");
					}

					if($m==0){
						if(!M('order:orderLog')->addLog($data['o_id'],3,2,$spend_time,$data['total_price'],$data['total_price'],0)) $this->error("更新可视化失败");
						if(!$this->db->model('order')->where('o_id='.$data['o_id'])->update(array('collection_status'=>3,'payd_time'=>$data['payment_time'],'update_time'=>CORE_TIME))) $this->error("更新订单交易状态失败");
						$cid = $this->db->model('order')->select('c_id')->where('o_id='.$data['o_id'])->getOne();
						$this->db->model('customer')->where("c_id = $cid")->update(array('last_sale'=>$data['payment_time'],'last_no_sale'=>$data['payment_time']));
					}

					$data['uncollected_price'] = $m;
					$data['collection_status'] = 2;
					$id = $data['id'];
					unset($data['id']);
					//更新收付款信息
					if(!$re=$this->db->model('collection')->where('id='.$id)->update($data+array('update_time'=>CORE_TIME, 'update_admin'=>$_SESSION['username']))) $this->error("交易失败1");
				}
				//添加company_account_log账户明细信息,默认设计账户类型就是账户id
				$add_data['account_id']=$data['account'];
				$add_data['money']=$data['collected_price']+$data['handling_charge'];//加上手续费，如果有的话
				$add_data['remark']=$data['remark'];
				$add_data['type']=$data['order_type']==1?1:2;//1入账，2出账
				$add_data['order_id']=$data['o_id'];
				$add_data['order_type']=$data['order_type'];

				if(!$this->db->model('company_account_log')->add($add_data+array('input_time'=>CORE_TIME, 'input_admin'=>$_SESSION['username']))) $this->error("交易失败2");

				//修改account账户信息，1是销售，收款

				if($data['order_type']==1){   // 销售收款，收款需要加上手续费
					if(!$this->db->model('company_account')->where('id='.$data['account'])->update("`sum`=sum+".($data['collected_price']+$data['handling_charge']).",`update_time`=".CORE_TIME.",`update_admin`='".$_SESSION['username']."'")) $this->error("交易失败3");
				}else{      // 采购付款
					$money = $this->db->model('company_account')->where('id='.$data['account'])->select('sum')->getOne();
					if ($data['collected_price']>$money) {
						$this->error('余额不足');
					}else{
						if(!$this->db->model('company_account')->where('id='.$data['account'])->update("`sum`=sum-".$data['collected_price'].",`update_time`=".CORE_TIME.",`update_admin`='".$_SESSION['username']."'")) $this->error("交易失败4");
					}
					//处理多笔及单笔付款发送短信（仅针对采购）
					if(intval($data['order_type']) ==  2){
						// if($m > 0){
							$ext = '，现已付款'.($data['total_price']-$data['uncollected_price']).'元，请您查收。';
						// }else{
						// 	$ext = '现在已付款完成,请注意查看';
						// }
						M('order:orderLog')->sendMsg($data['o_id'],$data['order_type'],$ext);
					}
				}
				//处理信用额度------S
				//销售财务记账增加额度,采购财务记账减去额度,
				if($data['order_type']==1){
                    M('user:customer')->updateCreditLimit($data['o_id'],'+',$data['collected_price']) OR $this->error('财务收款记账可用额度更新失败');
				}else{
                    M('user:customer')->updateCreditLimit($data['o_id'],'-',$data['collected_price']) OR $this->error('财务付款记账可用额度更新失败');
				}
				//处理信用额度------E
				
				//处理战队配资销售来款------S
				// 销售收款,记账后，将该笔收款金额添加到对应业务员所在战队
				if($data['order_type']==1){
					$team_capital = M('rbac:adm')->getThisMonthTemaCapitalByCustomer($data['customer_manager']);
					// p($team_capital);
					if(!$team_capital) $this->error('处理失败，该订单业务员所在战队配资未设置，请找销售总监设置战队配资指标');
					$sale_capital = M('user:teamCapital')->comeMoney($team_capital,$data['collected_price']);//销售战队新增战队配资
					if(!$sale_capital) $this->error('财务收款时，销售战队配资失败');
					//新增战队配资变动日志----S
					$team_capital_now = M('rbac:adm')->getThisMonthTemaCapitalByCustomer($data['customer_manager']);
					$remarks = "财务收款，增加销售战队额度";
					M('user:teamCapital')->addLog($data['o_id'],$team_capital['team_id'],'sale_come',$team_capital['available_money'],$team_capital_now['available_money'],1,$data['collected_price'],$remarks);
					//新增战队配资变动日志----E
				}
        		//处理战队配资销售来款------E
			}else{
				if($data['handling_charge']==''){
					$data['uncollected_price']=$m;
				}else{
					$data['uncollected_price']=0;
					$data['handling_charge']='';
				}
				// p($data);die;
				if($data['order_type']==2 && $data['team_id'] != 1){
					$err = array();
					//判断2种情况 1 ：特殊战队 扣额度，但是不过特批这一关 ，2 一般战队，扣额度，过审批
					//一般战队处理
						$team_capital = M('rbac:adm')->getThisMonthTemaCapitalByCustomer($data['customer_manager']);
						//查询战队配资额度，与申请付款额度，差值如果为负，则提交到特批列表，并提醒业务员
						if($team_capital['available_money'] - $data['collected_price'] < 0){
							//先添加到collection表 然后添加到特批表p2p_team_capital_approve，在提醒业务员在特批表中
							//$data['uncollected_price'] = $m;
							$data['o_id'] = '-'.$data['o_id'];//如果到特批，将订单号改成-的存到collection表，其他表继续保存正常的o_id
							if(!$re=$this->db->model('collection')->add($data+array('input_time'=>CORE_TIME,'input_admin'=>$_SESSION['username']))) $this->error("交易失败");
							$coll_id=$this->db->model('collection')->getLastID();
							$approve_data = array('coll_id'=>$coll_id,'o_id'=>trim($data['o_id'],'-'),'customer_manager'=>$data['customer_manager'],'apply_time'=>CORE_TIME,'apply_admin'=>$_SESSION['username'],'team_name'=>$data['team'],'team_total_money'=>$data['team_total_money'],'status'=>0);
							$res=$this->db->model('team_capital_approve')->add($approve_data);
							// p($data);die;
							if(!$res){
								$this->error("添加特批表数据失败");
							}else{
								$err['msg'] = '该次申请因战队配资不足，申请已转到领导特批列表';
							}
							//新增战队配资变动日志----S
							$remarks = "付款申请，额度不足，转到领导特批列表";
							M('user:teamCapital')->addLog(trim($data['o_id'],'-'),$team_capital['team_id'],'un_buy_pay',$team_capital['available_money'],$team_capital['available_money'],1,0,$remarks);
							//新增战队配资变动日志----E
						}else{
							//余额充足情况下，扣除额度，然后添加到collection表
							$buy_capital = M('user:teamCapital')->goMoney($team_capital,$data['collected_price']);//采购战队付款后扣除资金
							if(!$buy_capital) $this->error("采购战队付款时更新配资失败");
							//新增战队配资变动日志----S
							$team_capital_now = M('rbac:adm')->getThisMonthTemaCapitalByCustomer($data['customer_manager']);
							$remarks = "付款申请，削减采购战队额度";
							M('user:teamCapital')->addLog($data['o_id'],$team_capital['team_id'],'buy_pay',$team_capital['available_money'],$team_capital_now['available_money'],1,$data['collected_price'],$remarks);
							//新增战队配资变动日志----E
							//$data['uncollected_price'] = $m;
							if(!$re=$this->db->model('collection')->add($data+array('input_time'=>CORE_TIME,'input_admin'=>$_SESSION['username']))) $this->error("交易失败");
						}
					}elseif($data['order_type']==2 && $data['team_id'] == 1){
						//特殊战队，team_id=1，付款申请是非战队人员提交的
						$team_capital = M('rbac:adm')->getThisMonthTemaCapitalBySpecialTeamId();
						$buy_capital = M('user:teamCapital')->goMoney($team_capital,$data['collected_price']);//采购战队付款后扣除资金
						if(!$buy_capital) $this->error("非战队业务员付款时更新配资失败");
						//新增战队配资变动日志----S
						$team_capital_now = M('rbac:adm')->getThisMonthTemaCapitalByCustomer($data['customer_manager']);
						$remarks = "付款申请，削减采购战队额度";
						M('user:teamCapital')->addLog($data['o_id'],$team_capital['team_id'],'buy_pay',$team_capital['available_money'],$team_capital_now['available_money'],1,$data['collected_price'],$remarks);
						//新增战队配资变动日志----E
						//$data['uncollected_price'] = $m;
						if(!$re=$this->db->model('collection')->add($data+array('input_time'=>CORE_TIME,'input_admin'=>$_SESSION['username']))) $this->error("交易失败");
					}elseif($data['order_type']==1){
						//$data['uncollected_price'] = $m;
						if(!$re=$this->db->model('collection')->add($data+array('input_time'=>CORE_TIME,'input_admin'=>$_SESSION['username']))) $this->error("交易失败");
					}
			}
		if($this->db->commit()){
			if(!empty($err)){
				$this->json_output(array('err'=>2,'msg'=>$err['msg']));
			}else{
				$this->success('操作成功');
			}
		}else{
			$this->db->rollback();
			$this->error('保存失败：'.$this->db->getDbError());
		}

	}

	/**
	 * [get_up_handling_charge description]
	 * @Author   xianghui
	 * @DateTime 2017-04-26T14:58:46+0800
	 * @return   [type]                   [description]
	 */
	public function get_up_handling_charge($arr,$id){
		if(empty($arr)) $this->error('错误的操作');
		foreach ($arr as $key => $value) {
			if ($value['id']<$id) {
				return $value['handling_charge'];
			}
		}
	}
	/**
	 * 充红
	 * @access private
	 */
	public function changeRed(){
		$this->is_ajax=true; //指定为Ajax输出
		$data = sdata(); //获取UI传递的参数
		if(empty($data)) $this->error('错误的操作');
// 		$arr = M('product:collection')->getLastInfo($name='o_id',$value=$data['oid']);
		$arr = $this->db->model('collection')->where("id='".$data['id']."'")->getRow();
		// p($arr);exit;
		//获取上一次的手续费金额
		// $charge_id_arr = M('product:collection')->select('handling_charge,id')->where("order_sn='".$data['o_sn']."'")->order('id desc')->getAll();
		// $up_handling_charge=$this->get_up_handling_charge($charge_id_arr,$data['id']);

		//本次更新手续费=总手续费-本次红冲手续费
		// $red_handling_charge=$charge_id_arr[0]['handling_charge']-($arr['handling_charge']-$up_handling_charge);
		$arr2 = array(
			'id'=>'',
			'order_name'=>'退款',
// 			'order_sn'=>'更正'.$data['o_sn'],
			'order_sn'=>$data['o_sn'],
// 			'collected_price'=>'0',
// 			'uncollected_price'=>$arr[0]['uncollected_price']+$data['c_price'],
// 			'refund_amount'=>$data['c_price'],
			'total_price'=>-$arr['total_price'],
			'collected_price'=>-$arr['collected_price'],
			'uncollected_price'=>-$arr['uncollected_price'],
			'handling_charge'=>-$arr['handling_charge'],
			'refund_amount'=>$data['c_price'],
			'update_time'=>CORE_TIME,
			'update_admin'=>$_SESSION['name'],
			'collection_status'=>2,
			);
			//退款可能变化的值 [pay_method] => 4   [payment_time] => 0     [account] => 1
//  		$update=array_merge($arr[0],$arr2);
		$update=array_merge($arr,$arr2);
		//判断是不是第一笔收付款记录
		// $times = count($this->db->model('collection')->select('id')->where("o_id='".$data['oid']."'")->getCol());
		//查出已收付款金额
		$has_price=$this->db->model('collection')->select("sum(collected_price)")->where("o_id='".$data['oid']."'")->getOne();
		$this->db->startTrans();//开启事务
			try {
				if(!$this->db->model('collection')->add($update) )throw new Exception("新增退款失败");
				if(!$this->db->model('collection')->wherePK($data['id'])->update( array('collection_status'=>3)) )throw new Exception("修改退款状态失败");
				//根据撤销付款金额与总金额的大小，判断订单付款状态
				if($data['c_price']==$has_price){
					//红冲金额等于已收付款金额，订单待收付款
					$arrtmp=array('collection_status'=>1,);
				}elseif($data['c_price']<$has_price){
					//红冲金额小于已收付款金额，订单部分收付款
					$arrtmp=array('collection_status'=>2,);
				}else{
					//红冲金额大于已收付款金额，数据肯定错了
					$this->error("数据错误，请联系管理员");
				}
				if(!$this->db->model('order')->wherePK($data['oid'])->update($arrtmp+array('update_time'=>CORE_TIME,'payd_time'=>'',)) )throw new Exception("修改订单表退款状态失败");
				//更新红冲后可视化状态
				if($arr['handling_charge']==0){
					//获取该订单有关的所有手续费之和
					$h_charge=$this->db->model('collection')->select("sum(handling_charge)")->where("o_id='".$data['oid']."'")->getOne();
					//如果红冲的不是有手续费那一笔，该笔总额为货款总额，所以总额得加上手续费
					$t_price=$arr['total_price']+$h_charge;	
					$col_price=$has_price-$data['c_price']+$h_charge;
					$un_price=$arr['total_price']-$has_price+$data['c_price'];
				}else{
					//如果红冲的是有手续费那一笔，总额不需调动
					$t_price=$arr['total_price'];
					$col_price=$has_price-$data['c_price'];
					$un_price=$t_price-$has_price+$data['c_price'];	
				}
				if(!M('order:orderLog')->addLog($data['oid'],$arrtmp['collection_status'],2,0,$t_price,$col_price,$un_price)) $this->error("更新可视化失败");

				//以下增加没有同步账户和资金流水的bug 20160825
				//添加account_log账户明细信息,默认设计账户类型就是账户id
				$add_data['account_id']=$arr['account'];
				//减去红冲的手续费，如果有的话
				$add_data['money']=-$arr['collected_price']-$arr['handling_charge'];
				$add_data['remark']=$arr['remark'];
				$add_data['type']=$arr['order_type']==1?1:2;
				$add_data['order_id']=$arr['o_id'];
				$add_data['order_type']=$arr['order_type'];
				$this->db->model('company_account_log')->add($add_data+array('input_time'=>CORE_TIME, 'input_admin'=>$_SESSION['username']));
				//修改account账户信息，1是销售收款
				if($arr['order_type']==1){
					//销售减去手续费update("`sum`=sum+".($data['collected_price']+$data['handling_charge']).",
					$this->db->model('company_account')->where('id='.$arr['account'])->update("`sum`=sum-".($arr['collected_price']+$arr['handling_charge']).",`update_time`=".CORE_TIME.",`update_admin`='".$_SESSION['username']."'");
				}else{
					$money = $this->db->model('company_account')->where('id='.$arr['account'])->select('sum')->getOne();
					$this->db->model('company_account')->where('id='.$arr['account'])->update("`sum`=sum+".$arr['collected_price'].",`update_time`=".CORE_TIME.",`update_admin`='".$_SESSION['username']."'");
				}
                //处理信用额度------S
				//销售财务红冲扣减额度,采购财务红冲增加额度,
				if($arr['order_type']==1){
                    M('user:customer')->updateCreditLimit($arr['o_id'],'-',$arr['collected_price']) OR $this->error('财务收款红冲可用额度更新失败');
				}else{
                    M('user:customer')->updateCreditLimit($arr['o_id'],'+',$arr['collected_price']) OR $this->error('财务付款红冲可用额度更新失败');
				}
				//处理信用额度------E   

				//战队配资--红冲处理------S
				//红冲后将对应的金额回退（采购付款）/扣除（销售收款）到相应的业务员所在战队中
				$team_capital = M('rbac:adm')->getThisMonthTemaCapitalByCustomer($arr['customer_manager']);
				if(!$team_capital) $this->error('该订单业务员所在战队配资未设置，请找销售总监设置战队配资指标');
				if($arr['order_type']==1){
					$sale_capital = M('user:teamCapital')->goMoney($team_capital,$arr['collected_price']);//销售战队红冲后扣除资金
					if(!$sale_capital) $this->error('财务收款红冲时，销售战队配资失败');
					//新增战队配资变动日志----S
					$team_capital_now = M('rbac:adm')->getThisMonthTemaCapitalByCustomer($arr['customer_manager']);
					$remarks = "财务收款红冲，削减销售战队额度";
					M('user:teamCapital')->addLog($arr['o_id'],$team_capital['team_id'],'sale_red',$team_capital['available_money'],$team_capital_now['available_money'],1,$arr['collected_price'],$remarks);
					//新增战队配资变动日志----E
				}else{
					$buy_capital = M('user:teamCapital')->comeMoney($team_capital,$arr['collected_price']);//销售战队红冲后扣除资金
					if(!$buy_capital) $this->error('财务付款红冲时，采购战队配资失败');
					//新增战队配资变动日志----S
					$team_capital_now = M('rbac:adm')->getThisMonthTemaCapitalByCustomer($arr['customer_manager']);
					$remarks = "财务付款红冲，增加采购战队额度";
					M('user:teamCapital')->addLog($arr['o_id'],$team_capital['team_id'],'buy_red',$team_capital['available_money'],$team_capital_now['available_money'],1,$arr['collected_price'],$remarks);
					//新增战队配资变动日志----E
				}
				//战队配资--红冲处理------E

			} catch (Exception $e) {
				$this->db->rollback();
				$this->error($e->getMessage());
			}
		$this->db->commit();
		$this->success('操作成功');

	}
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
			$cache->delete('factory');
			$this->success('操作成功');
		}else{
			$this->error('数据处理失败');
		}
	}

	/**
	 * 下载附件
	 * @access private
	 */
	public function downloadAdjunct(){
		$this->is_ajax=true; //指定为Ajax输出
		$data = sdata(); //获取UI传递的参数
		if(empty($data)) $this->error('错误的操作');

		header("Content-type:text/html;charset=utf-8");
		//用以解决中文不能显示出来的问题
		$file_name=iconv("utf-8","gb2312",$data['accessory']);//16/06/16/57629c0249c65.doc
		$file_sub_path=FILE_URL.'/upload/'; //static.svnonline.com/upload/

		$file_path=$file_sub_path.$file_name;
		//首先要判断给定的文件存在与否
		if(!file_exists($file_path)){
			echo "没有该文件";
			return ;
		}
		$fp=fopen($file_path,"r");
		$file_size=filesize($file_path);//22

		//下载文件需要用到的头
		Header("Content-type: application/octet-stream");
		Header("Accept-Ranges: bytes");
		Header("Accept-Length:".$file_size);
		Header("Content-Disposition: attachment; filename=".$file_name);
		$buffer=1024;
		$file_count=0;
		//向浏览器返回数据
		while(!feof($fp) && $file_count<$file_size){
		$file_con=fread($fp,$buffer);
		$file_count+=$buffer;
		echo $file_con;
		}
		fclose($fp);

	}

	/**
	 * 异步请求账户余额，显示
	 */
	public function changeaccount(){
		$this->is_ajax=true; //指定为Ajax输出
		$id = sdata(); //获取UI传递的参数
		$data['sum']=$this->db->model('company_account')->where('id='.$id)->select('sum')->getOne();
		if ($data<1) {
			$data['err']='0';
		}
		json_output($data);
	}

	/**
	 * 东方付通退款确认
	 */
	public function refundOk(){
		$this->is_ajax=true; //指定为Ajax输出
		$data = sdata(); //获取UI传递的参数
		if(empty($data)) $this->error('错误的操作');
		$arr = $this->db->model('collection')->where("id='".$data['id']."'")->getRow();
		$this->db->startTrans();//开启事务
		try {
			if(!$this->db->model('collection')->wherePK($data['id'])->update( array('refund_amount'=>$data['total_price'])) )throw new Exception("修改退款金额失败");
// 	        //根据撤销付款金额与总金额的大小，判断订单付款状态
// 	        $arrtmp=array('collection_status'=>1,);
// 	        if(!$this->db->model('order')->wherePK($data['oid'])->update($arrtmp+array('update_time'=>CORE_TIME,)) )throw new Exception("修改订单表退款状态失败");
			//以下增加没有同步账户和资金流水的bug 20160825
			//添加account_log账户明细信息,默认设计账户类型就是账户id
			$arr['account']=1;
			$add_data['account_id']=$arr['account'];
			$add_data['money']=-$arr['collected_price'];
			$add_data['remark']=$arr['remark'];
			$add_data['type']=$arr['order_type']==1?1:2;
			$add_data['order_id']=$arr['o_id'];
			$add_data['order_type']=$arr['order_type'];
			$this->db->model('company_account_log')->add($add_data+array('input_time'=>CORE_TIME, 'input_admin'=>$_SESSION['username']));
			//修改account账户信息，1是销售，收款
			if($arr['order_type']==1){
				$this->db->model('company_account')->where('id='.$arr['account'])->update("`sum`=sum-".$arr['collected_price'].",`update_time`=".CORE_TIME.",`update_admin`='".$_SESSION['username']."'");
			}else{
				$money = $this->db->model('company_account')->where('id='.$arr['account'])->select('sum')->getOne();
				$this->db->model('company_account')->where('id='.$arr['account'])->update("`sum`=sum+".$arr['collected_price'].",`update_time`=".CORE_TIME.",`update_admin`='".$_SESSION['username']."'");
			}
		} catch (Exception $e) {
			$this->db->rollback();
			$this->error($e->getMessage());
		}

		if($this->db->commit()){
		   $this->success('操作成功');
		}else{
			$this->db->rollback();
			$this->error('生成失败:'.$this->db->getDbError());
		}
	}
}