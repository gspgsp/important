<?php
/**
*开票资料管理控制器
*/
class customer_billingAction extends adminBaseAction
{
	public function __init(){
		$this->db=M('public:common')->model('customer_billing');
	}

	/**
	 * 开票资料列表
	 * @access public
	 * @return html
	 */
	public function init(){
		$action=sget('action','s');
		if($action=='grid'){ //获取列表
			$this->_grid();exit;
		}
		$this->assign('choose',sget('choose','s'));
		$this->assign('doact',$doact);
		$this->assign('page_title','开票资料审核');
		$this->display('customer_billing.list.html');

	}

	public function _grid(){
		//获取列表数据
		$page = sget("pageIndex",'i',0); //页码
		$size = sget("pageSize",'i',20); //每页数
		$sortField = sget("sortField",'s','c_id'); //排序字段
		$sortOrder = sget("sortOrder",'s','desc'); //排序
		//搜索条件
		$where="`display_status`=1";//未假删除的
		//审核状态搜索
		$status = sget('status','i');
		$where .=" and `status` = $status ";
		//关键词
		$key_type=sget('key_type','s','c_name');
		$keyword=sget('keyword','s');
		if(!empty($keyword)){
			if ($key_type == 'c_name') {
				$c_ids = M('user:customer')->getLikeCidByCname($keyword,$condition='c_id');
				$where.=" and `c_id` in ($c_ids)";
			}else if($key_type == 'admin'){
				$customer_manager = M('rbac:adm')->getAdmin_Id($keyword);
				$where.=" and `customer_manager` = $customer_manager";
			}else{
				$where.=" and $key_type = '$keyword' ";
			}
		}
		$list=$this->db->where($where)
					->page($page+1,$size)
					->order("$sortField $sortOrder")
					->getPage();
		foreach($list['data'] as $k=>$v){
			$list['data'][$k]['input_time']=$v['input_time']>1000 ? date("Y-m-d H:i:s",$v['input_time']) : '-';
			$list['data'][$k]['update_time']=$v['update_time']>1000 ? date("Y-m-d H:i:s",$v['update_time']) : '-';
			$list['data'][$k]['c_name'] = M('user:customer')->getColByName($v['c_id']);
			//关联业务员
			$list['data'][$k]['username']=M('rbac:adm')->getUserByCol($v['customer_manager']);
			//审核状态
			if ($v['status']==1) {
				$list['data'][$k]['status']='审核通过';
			}elseif($v['status']==2){
				$list['data'][$k]['status']='未审核';
			}else{
				$list['data'][$k]['status']='数据有误';
			}
		}
		$result=array('total'=>$list['count'],'data'=>$list['data'],'msg'=>'');
		$this->json_output($result);

		
	}

	/**
	 * 开票资料审核页面check
	 */
	public function check(){
		$id = sget('id','i',0);
		$list=$this->db->from('customer_billing cb')
            ->join('customer c','c.c_id=cb.c_id')
            ->where("cb.id={$id}")
            ->select("cb.*,c.c_name")
            ->getRow();
        $this->assign('id',$list['id']);
        $this->assign('user_id',$list['user_id']);
        $this->assign('c_id',$list['c_id']);
        $this->assign('tax_id',$list['tax_id']);
        $this->assign('invoice_bank',$list['invoice_bank']);
        $this->assign('invoice_address',$list['invoice_address']);
        $this->assign('invoice_tel',$list['invoice_tel']);
        //银行账号加密
        // $this->assign('invoice_account',desDecrypt($list['invoice_account']));
        $this->assign('invoice_account',$list['invoice_account']);
        $this->assign('fax',$list['fax']);
        $this->assign('c_name',$list['c_name']);
        $this->assign('ems_address',$list['ems_address']);

		$this->display('customer_billing.add.html');
	}

	/**
	 * 保存添加的开票信息
	 * @access public
	 */
	public function ajaxSave(){
		$this->is_ajax=true; //指定为Ajax输出
		$data = sdata(); //传递的参数
		if(empty($data)){
			$this->error('错误的请求');
		}
		if(validCompanyBankNo($data['invoice_account'])['err']==1){$this->error('银行卡号错误');}
		if ($data['id']>0) {
			$result = $this->db->where('id='.$data['id'])->update($data+array('input_time'=>CORE_TIME,'input_admin'=>$_SESSION['name'],'status'=>1,));
		}
		if(!$result) $this->error('操作失败');
		$cache=cache::startMemcache();
		$cache->delete('customer_billing');
		$this->success('操作成功');
	}

	/**
	 * 导出excel
	 * @access public 
	 * @return html
	 */
	public function download(){
		$sortField = sget("sortField",'s','c_id'); //排序字段
		$sortOrder = sget("sortOrder",'s','desc'); //排序
		//搜索条件
		$where="`display_status`=1";//未假删除的
		//审核状态搜索
		$status = sget('status','i');
		$where .=" and `status` = $status ";
		//关键词
		$key_type=sget('key_type','s','c_name');
		$keyword=sget('keyword','s');
		if(!empty($keyword)){
			if ($key_type == 'c_name') {
				$c_ids = M('user:customer')->getLikeCidByCname($keyword,$condition='c_id');
				$where.=" and `c_id` in ($c_ids)";
			}else if($key_type == 'admin'){
				$customer_manager = M('rbac:adm')->getAdmin_Id($keyword);
				$where.=" and `customer_manager` = $customer_manager";
			}else{
				$where.=" and $key_type = '$keyword' ";
			}
		}

		//筛选领导级别
		if($_SESSION['adminid'] != 1 && $_SESSION['adminid'] > 0){
			$sons = M('rbac:rbac')->getSons($_SESSION['adminid']);  //领导
			$where .= " and `customer_manager` in ($sons) ";
		}
		$orderby = "$sortField $sortOrder";

		$list=$this->db->where($where)
					->page($page+1,$size)
					->order("$sortField $sortOrder")
					->getAll();
		foreach($list as $k=>$v){
			$list[$k]['input_time']=$v['input_time']>1000 ? date("Y-m-d H:i:s",$v['input_time']) : '-';
			$list[$k]['update_time']=$v['update_time']>1000 ? date("Y-m-d H:i:s",$v['update_time']) : '-';
			$list[$k]['c_name'] = M('user:customer')->getColByName($v['c_id']);
			//关联业务员
			$list[$k]['username']=M('rbac:adm')->getUserByCol($v['customer_manager']);
			//审核状态
			if ($v['status']==1) {
				$list[$k]['status']='审核通过';
			}elseif($v['status']==2){
				$list[$k]['status']='未审核';
			}else{
				$list[$k]['status']='数据有误';
			}
		}
		
		$str = '<meta http-equiv="Content-Type" content="text/html; charset=utf8" /><table width="100%" border="1" cellspacing="0">';

		$str .= '<tr><td>客户名称</td><td>纳税人识别号</td><td>开票地址</td><td>邮寄地址</td>
					<td>开票电话</td><td>开票银行</td><td>开票帐号</td><td>传真</td>
					<td>审核状态</td><td>业务员</td>
				</tr>';
		foreach($list as $val){
			$str .= "<tr><td style='vnd.ms-excel.numberformat:@'>".$val['c_name']."</td><td>".$val['tax_id']."</td><td>".$val['invoice_address']."</td><td>".$val['ems_address']."</td>
						<td>".$val['invoice_tel']."</td><td>".$val['invoice_bank']."</td><td>".$val['invoice_account']."</td><td>".$val['fax']."</td>
						<td>".$val['status']."</td><td>".$val['username']."</td>
					</tr>";
		}
		$str .= '</table>';
		$filename = 'invoice.'.date("Y-m-d");
		header("Content-type: application/vnd.ms-excel; charset=utf-8");
		header("Content-Disposition: attachment; filename=$filename.xls");
		echo $str;
		exit;
	}
}