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
		$this->assign('company_account',L('company_account'));  //交易公司账户order_type

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
		$this->assign('type','1');
		$this->assign('order_sn',sget('order_sn','s'));
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
		$page = sget("pageIndex",'i',0); //页码
		$size = sget("pageSize",'i',20); //每页数
		$sortField = sget("sortField",'s','input_time'); //排序字段
		$sortOrder = sget("sortOrder",'s','desc'); //排序
		//搜索条件
		$where ="  1 ";
		$type = sget('type','i');//1销售,2采购
		if ($type == 1) {
			$where .=" and `order_type`=1 ";
		}elseif($type ==2){
			$where .="  and `order_type`=2 ";
		}
		$o_id=sget('oid','i',0);
		if($o_id !=0)  $where.=" and `o_id` =".$o_id;
		$order_sn=sget('order_sn','s');
		if($order_sn)  $where.=" and `order_sn` = '$order_sn' ";
		//交易日期
		$sTime = sget("sTime",'s','payment_time'); //搜索时间类型
		$where.= getTimeFilter($sTime); //时间筛选
		//交易方式
		$pay_method = sget("pay_method",'s','');
		if($pay_method!='') $where.=" and `pay_method` = '$pay_method' ";
		//收付款审核状态
		$collection_status = sget("collection_status",'s','');
		if($collection_status!='') $where.=" and `collection_status` = '$collection_status' ";
		//开票状态
		// $invoice_status = sget("invoice_status",'s','');
		// if($invoice_status!='') $where.=" and `invoice_status` = '$invoice_status' ";
		//交易公司类型
		$company_account = sget("company_account",'s','');
		if($company_account!='') $where.=" and `account` = '$company_account' ";

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
					->select("c.*,a.name")
					->from('collection c')
					->join('admin as a','a.admin_id=c.customer_manager')
					->page($page+1,$size)
					->order("$sortField $sortOrder".', payment_time DESC')
					->getPage();
				// $list=$this->db->where($where)
				// 	->page($page+1,$size)
				// 	->order("$sortField $sortOrder".', payment_time DESC')
				// 	->getPage();
		foreach($list['data'] as $k=>$v){
			if($v['o_id'] < 0){$list['data'][$k]['o_id'] = $v['o_id']*(-1);}
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
			}elseif($type ==2) {
				$msg="[合计]总额:【".price_format($sum['tsum'])."】已付款:【".$sum['csum']."】未付款:【".$sum['usum']."】";
			}
		}
		$result=array('total'=>$list['count'],'data'=>$list['data'],'msg'=>$msg);
		$this->json_output($result);
	}

	/**
	 * 删除
	 */
	public function delCollection(){
		$id = sget('id','i');
		$type = sget('type','i');
		if(empty($id) || empty($type)){
			$this->error('操作有误');
		}
		//收款申请删除，战队配资不处理
		if($type == 1){
			$c_status = $this->db->model('collection')->select('collection_status')->where('id='.$id)->getOne();
			if ($c_status == 1) {
				$result=$this->db->where("id=".$id)->delete();
				if($result){
					$this->success('删除成功');
				}else{
					$this->error('删除失败');
				}
			}
		}
		//付款申请删除，战队配资不处理
		$res = $this->db->model('collection')->select('*')->where('id='.$id)->getRow();
		//战队配资考虑，删除付款申请后，相应的战队配资额度要回退，
		$team_capital = M('rbac:adm')->getThisMonthTemaCapitalByCustomer($res['customer_manager']);
		if(empty($team_capital)){
			$this->error('删除失败,您所在战队本月战队配资指标未设置，请先找总监设置好指标');
		}
		$this->db->startTrans(); //开启事务
		if ($res['collection_status'] == 1) {
			$role_id = M('rbac:adm')->getCustomerManagerTeamId($res['customer_manager']);
			$collection_res = $this->db->model('team_capital_approve')->select('*')->where('coll_id='.$id)->getRow();
			// p($collection_res);die;
			if(($role_id != 1 && $collection_res['status'] == 1) || ($role_id != 1 && !$collection_res) || $role_id == 1 ){
				//回退到相应战队
				$buy_capital = M('user:teamCapital')->comeMoney($team_capital,$res['collected_price']);//采购战队回退资金
				if(!$buy_capital) $this->error("采购战队付款时更新配资失败");
				//新增战队配资变动日志----S
				$team_capital_now = M('rbac:adm')->getThisMonthTemaCapitalByCustomer($res['customer_manager']);
				$remarks = "付款申请删除，增加采购战队额度";
				M('user:teamCapital')->addLog($res['o_id'],$team_capital['team_id'],'buy_pay_del',$team_capital['available_money'],$team_capital_now['available_money'],1,$res['collected_price'],$remarks);
				//新增战队配资变动日志----E
			}
			if($collection_res) $this->db->model('team_capital_approve')->where("coll_id=".$collection_res['coll_id'])->delete();
			$result=$this->db->model('collection')->where("id=".$id)->delete();
		}
		if($this->db->commit()){
			$this->success('操作成功');
		}else{
			$this->db->rollback();
			$this->error('操作失败');
		}
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

		//p($c_info);die;
		$user_name = M('rbac:adm')->getUserInfoById($data[0][admin_id]);//获取前台添加的业务员名字
		$username  = $user_name['name'];

		//订单中没有业务员id就传input_admin过去
		if (empty($username)) {
			$this->assign('input_admin',$data[0][input_admin]);
		}else{
			$this->assign('input_admin',$username);
		}
		//传递表头信息
		$this->assign('p_method',$data[0][pay_method]);
		$this->assign('order_name',$data[0][order_name]);
		$this->assign('c_name',$c_info[c_name]);
		$this->assign('c_id',$data[0][c_id]);
		$this->assign('type',$type);
		$this->assign('o_id',$o_id);
		$this->assign('price',$data[0]['total_price']);
		$this->assign('order_sn',$data[0][order_sn]);

		//获取是不是财务审核
		$finance=sget('finance','i');

			if ($finance ==1 ) {
				//获取要审核的收付款的id，传送出信息
				$id = sget('id','i',0);
				$this->assign('finance',$finance);
				$this->assign('id',$id);

				$res = M('product:collection')->where('id='.$id)->getAll();
				if($res){
					$un_price = $res[0]['collected_price']+$res[0]['uncollected_price'];
					$this->assign('c_price',$res[0]['collected_price']);
					$this->assign('u_price',$un_price);
					$this->assign('remark',$res[0]['remark']);//备注
				}
			}else{
				//获取最后一条收付款信息
				$res = M('product:collection')->getLastInfo($name='o_id',$value=$data[0][o_id]);
				if($res){
// 					$this->assign('total_price',$res[0]['total_price']);
// 					$this->assign('uncollected_price',$res[0]['uncollected_price']);
// 					$this->assign('remark',$res[0]['remark']);//备注
				    $collected_price =  $this->db->model('collection')->select("IFNULL(SUM(collected_price),0) AS collected_price")->where("o_id='".$data[0][o_id]."'")->getOne();
				    $uncollected_price = $data[0]['total_price'] -  $collected_price;
				    $this->assign('total_price',$data[0]['total_price']);
				    $this->assign('uncollected_price',$uncollected_price);
				    $this->assign('remark',$res[0]['remark']);//备注
				}
			}
			$this->display('collection.add.html');

	}

	/**
	* 保存付款收款信息
	*/
	public function ajaxSave(){
		$data = sdata();
		$o_id = sget('o_id','i',0);
		if ($data['collection_token'] != $_SESSION['collection_token']) {
			$this->error("非法提交数据");
			unset($_SESSION['collection_token']);
		}else{
			unset($_SESSION['collection_token']);
		}
		//根据o_id获取订单中的业务员id,即customer_manager
		$customer_manager = $this->db->model('order')->select('customer_manager')->where('o_id='.$data['o_id'])->getOne();
		$data['customer_manager']=$customer_manager;

			//保存收付款相关信息
			if(empty($data['uncollected_price'])){
				$this->db->model('order')->where('o_id='.$data['o_id'])->update('total_price ='.$data['total_price'].',invoice_status=1');
				$m = $data['total_price']-$data['collected_price'];
			}else{
				$m = $data['uncollected_price']-$data['collected_price'];
			}

			$this->db->startTrans();//开启事务

				if($data['finance'] ==1){
					if($m>0){
						if(!$this->db->model('order')->where('o_id='.$data['o_id'])->update(array('collection_status'=>2,'update_time'=>CORE_TIME))) $this->error("跟新订单交易状态失败");
					}

					if($m==0){
						if(!$this->db->model('order')->where('o_id='.$data['o_id'])->update(array('collection_status'=>3,'update_time'=>CORE_TIME))) $this->error("跟新订单交易状态失败");
					}
					if($m<0){
						$this->error("数据错误");
					}
					$data['uncollected_price'] = $m;
					$data['collection_status'] = 2;
					$data['payment_time']=strtotime($data['payment_time']);
					$id = $data['id'];
					unset($data['id']);
					//更新收付款信息
					if(!$re=$this->db->model('collection')->where('id='.$id)->update($data+array('update_time'=>CORE_TIME, 'update_admin'=>$_SESSION['username']))) $this->error("交易失败");
					//添加account_log账户明细信息,默认设计账户类型就是账户id
					$add_data['account_id']=$data['account'];
					$add_data['money']=$data['collected_price'];
					$add_data['remark']=$data['remark'];
					$add_data['type']=$data['order_type']==1?1:2;
					$add_data['order_id']=$data['o_id'];
					$add_data['order_type']=$data['order_type'];

					if(!$this->db->model('company_account_log')->add($add_data+array('input_time'=>CORE_TIME, 'input_admin'=>$_SESSION['username']))) $this->error("交易失败");

					//修改account账户信息，1是销售，收款

					if($data['order_type']==1){
						if(!$this->db->model('company_account')->where('id='.$data['account'])->update("`sum`=sum+".$data['collected_price'].",`update_time`=".CORE_TIME.",`update_admin`='".$_SESSION['username']."'")) $this->error("交易失败");

					}else{
						$money = $this->db->model('company_account')->where('id='.$data['account'])->select('sum')->getOne();
						if ($data['collected_price']>$money) {
							$this->error('余额不足');
						}else{
							if(!$this->db->model('company_account')->where('id='.$data['account'])->update("`sum`=sum-".$data['collected_price'].",`update_time`=".CORE_TIME.",`update_admin`='".$_SESSION['username']."'")) $this->error("交易失败");
						}

					}

				}else{
					$data['uncollected_price'] = $m;
					if(!$re=$this->db->model('collection')->add($data+array('input_time'=>CORE_TIME,'input_admin'=>$_SESSION['username']))) $this->error("交易失败");
				}
			if($this->db->commit()){
				$this->success('操作成功');
			}else{
				$this->db->rollback();
				$this->error('保存失败：'.$this->db->getDbError());
			}

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
}