<?php
/**
*库存预警
*/
class storeRiskAction extends adminBaseAction
{
	public function __init(){
		$this->db=M('public:common')->model('in_log');
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
		$this->display('storeRisk.list.html');
	}
	/**
	 * Ajax获取列表内容
	 * @access private
	 * @return html
	 */
	private function _grid(){
		$page = sget("pageIndex",'i',0); //页码
		$size = sget("pageSize",'i',20); //每页数
		$sortField = sget("sortField",'s','o.input_time'); //排序字段
		$sortOrder = sget("sortOrder",'s','desc'); //排序
		//搜索条件
		$where =" 1 ";
		//交易日期
		$sTime = sget("sTime",'s','o.input_time'); //搜索时间类型
		$where.= getTimeFilter($sTime); //时间筛选
		//牌号
		$p_id = sget('p_id','s');
		if(!empty($p_id)){
			$p_id=M('product:product')->getpidByPname($p_id);
			$where.=" and il.`p_id` in ($p_id) ";
		}
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
// p($where);
		//筛选领导级别
		if($_SESSION['adminid'] != 1 && $_SESSION['adminid'] > 0){
			$sons = M('rbac:rbac')->getSons($_SESSION['adminid']);  //领导
			$where .= " and `customer_manager` in ($sons) ";
		}
		//筛选过滤自己的订单信息
		if($_SESSION['adminid'] != 1 && $_SESSION['adminid'] > 0){
			$sons = M('rbac:rbac')->getSons($_SESSION['adminid']);
			if(in_array($roleid, array('30','26','27'))){
				$where .= " and (`customer_manager` in ($sons) or `partner` = {$_SESSION['adminid']})  ";
			}
		}

		$list=$this->db->model('in_log as `il`')
			->where($where)
			->select("`il`.*,`o`.`o_id`,`o`.`order_sn`,`o`.`customer_manager`,`c`.`c_name`,`o`.`input_time`")
			->leftjoin('order as `o`','`o`.o_id=`il`.o_id')
			->leftjoin('customer as c','c.c_id=o.c_id')
			->page($page+1,$size)
			->order("$sortField $sortOrder")
			->getPage();
					// showtrace();
					// p($list);
		foreach($list['data'] as $k=>$v){
			$list['data'][$k]['input_time']=$v['input_time']>1000 ? date("Y-m-d H:i:s",$v['input_time']) : '-';
			$customer_manager = M('rbac:adm')->getUserInfoById($v['customer_manager']);
			$list['data'][$k]['customer_manager']= $customer_manager['name'];
			$list['data'][$k]['model']=strtoupper(M("product:product")->getModelById($v['p_id']));//获取牌号
			// showtrace();
			$list['data'][$k]['f_name']=M("product:product")->getFnameByid($v['p_id']);
			$list['data'][$k]['total_price'] = $v['unit_price']*$v['remainder'];
			$sale_unit_price=M("product:saleLog")->getLastSalePriceByPid($v['p_id']);
			// showtrace();
			// p($sale_unit_price);
			$today_price = $sale_unit_price * $v['remainder'];
			$list['data'][$k]['today_price'] = $today_price;
			$list['data'][$k]['diff'] = $sale_unit_price - $v['unit_price'];
			$list['data'][$k]['diff_rate'] = round((($sale_unit_price - $v['unit_price'])/$sale_unit_price)*100 ,2);
			// showtrace();
			// $list['data'][$k]['unit_price'] =  (M("product:order")->getColByOid($v['p_id'],'customer_manager') == $_SESSION['adminid'] OR in_array($_SESSION['adminid'],array(1,726,10,11))) ? $v['unit_price'] : '***';//只有李总超管饶卫平赵飞可以看到
		}
		$msg="";
		$result=array('total'=>$list['count'],'data'=>$list['data'],'msg'=>$msg);
		$this->json_output($result);
	}
}