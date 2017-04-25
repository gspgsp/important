<?php
/**
*发票逾期预警
*/
class billingRiskAction extends adminBaseAction
{
	public function __init(){
		$this->db=M('public:common')->model('order');
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
		$this->display('billingRisk.list.html');
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
		$where ="  1 and `o`.`order_type` = 2 AND `o`.`order_status` = 2 AND `o`.`transport_status` = 2 and `o`.`invoice_status` <> 3 and `o`.`payd_time`>0 ";
		//交易日期
		$sTime = sget("sTime",'s','o.input_time'); //搜索时间类型
		$where.= getTimeFilter($sTime); //时间筛选

		//关键词搜索
		$key_type=sget('key_type','s','order_sn');
		$keyword=sget('keyword','s');
		if(!empty($keyword)){
			switch ($key_type) {
				case 'order_sn':
					$where.=" and o.`order_sn` = '$keyword' ";
					break;
				case 'c_name':
					$where.=" and c.`c_name` like '%$keyword%'";
					break;
				case 'admin':
					$customer_manager = M('rbac:adm')->getAdmin_Id($keyword);
					$where.=" and o.`customer_manager` = $customer_manager";
					break;
				default:
					$where.=" and `$key_type`  = '$keyword' ";
					break;
			}
		}
		//筛选过滤自己的订单信息
		if($_SESSION['adminid'] != 1 && $_SESSION['adminid'] > 0){
			$sons = M('rbac:rbac')->getSons($_SESSION['adminid']);
			$roleid = M('rbac:rbac')->model('adm_role_user')->select('role_id')->where("`user_id` = {$_SESSION['adminid']}")->getOne();
			if(!in_array($roleid, array('30','26','27'))){
				$where .= " and o.`customer_manager` in ($sons) ";
			}
		}
		$list=$this->db->model('order as o')
			->where($where)
			->select("`o`.`o_id`,`o`.`order_sn`,`o`.`order_name`,`o`.`total_price`,IFNULL(SUM(`coll`.`collected_price`),0) AS `collected_price`,IFNULL(SUM(b.`billing_price`),0) AS billing_price,IFNULL((o.`total_price` - SUM(b.`billing_price`)),0) AS unbilling_price,`o`.`customer_manager`,`c`.`c_name`,`c`.`chanel`,CEIL((UNIX_TIMESTAMP()- `o`.`payd_time`)/86400) AS days,`o`.`input_time`")
			->leftjoin('billing as b','b.o_id=o.o_id AND `b`.`invoice_status` = 2')
			->leftjoin('customer as c','c.c_id=o.c_id')
			->leftjoin('collection as coll','coll.o_id=o.o_id AND `coll`.collection_status <> 1 ')
			->page($page+1,$size)
			->group('o.o_id')
			->order("$sortField $sortOrder")
			->getPage();
					// showtrace();
					// p($list);
		foreach($list['data'] as $k=>$v){
			$list['data'][$k]['input_time']=$v['input_time']>1000 ? date("Y-m-d H:i:s",$v['input_time']) : '-';
			$customer_manager = M('rbac:adm')->getUserInfoById($v['customer_manager']);
			$list['data'][$k]['customer_manager']= $customer_manager['name'];
			//收付款主题
			$list['data'][$k]['order_name'] = L('company_account')[$list['data'][$k]['order_name']];
			$list['data'][$k]['chanel']=L('company_chanel')[$v['chanel']];//客户渠道
		}
		$msg="";
		$result=array('total'=>$list['count'],'data'=>$list['data'],'msg'=>$msg);
		$this->json_output($result);
	}
}