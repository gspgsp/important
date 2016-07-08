<?php
/**
*开票资料管理控制器
*/
class customer_billingAction extends adminBaseAction
{
	public function __init(){
		$this->db=M('public:common')->model('customer_billing');
	}
	public function init(){
		//获取列表数据
		$doact=sget('do','s');
		$action=sget('action');
		if($action=='grid'){
			$page = sget("pageIndex",'i',0); //页码
			$size = sget("pageSize",'i',20); //每页数
			$sortField = sget("sortField",'s','c_id'); //排序字段
			$sortOrder = sget("sortOrder",'s','desc'); //排序
			//搜索条件
			$where="1";
			//关键词
			$key_type=sget('key_type','s','c_name');
			$keyword=sget('keyword','s');
			if(!empty($keyword)){
				if ($key_type == 'c_name') {
					$c_ids = M('user:customer')->getLikeCidByCname($keyword,$condition='c_id');
					$where.=" and `c_id` in $c_ids";
				}else{
					$where.=" and $key_type like '%$keyword%' ";
				}
			}
			$list=$this->db->where($where)
						->page($page+1,$size)
						->order("$sortField $sortOrder")
						->getPage();
	//p($list);die;
			foreach($list['data'] as $k=>$v){
				$list['data'][$k]['input_time']=$v['input_time']>1000 ? date("Y-m-d H:i:s",$v['input_time']) : '-';
				$list['data'][$k]['update_time']=$v['update_time']>1000 ? date("Y-m-d H:i:s",$v['update_time']) : '-';
			}
			$result=array('total'=>$list['count'],'data'=>$list['data'],'msg'=>'');
			$this->json_output($result);
		}
		$this->assign('choose',sget('choose','s'));
		$this->assign('doact',$doact);
		$this->assign('page_title','仓库管理');
		$this->display('customer_billing.list.html');
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
		$data['invoice_account'] = desEncrypt($data['invoice_account']);
		//处理数据
		$data = $data+array(
			'input_time'=>CORE_TIME,
			'input_admin'=>$_SESSION['name'],
		);
		$result = $this->db->add($data);
		if(!$result) $this->error('操作失败');
		$cache=cache::startMemcache();
		$cache->delete('customer_billing');
		$this->success('操作成功');
	}
	
	/**
	 * 保存行内编辑仓库数据
	 * @access public 
	 * @return html
	 */
	public function save(){
		$this->is_ajax=true; //指定为Ajax输出
		$data = sdata(); //获取UI传递的参数
		if(empty($data)){
			$this->error('错误的操作');
		}
		if(!empty($data['store_name'])){
		$name =M('product:store')->curUnique('store_name',$data['store_name'],$data[id]);
			if (!$name)	$this->error('仓库名重复');
		}
		if(!empty($data['store_tel'])){
		$tel =M('product:store')->curUnique('store_tel',$data['store_tel'],$data[id]);
			if (!$tel)	$this->error('仓库电话重复');
		}
		
		$sql=array();
		foreach($data as $v){
			$_id=$v['id'];
			if($_id>0){
				$update=array(
					'store_name'   => $v['store_name'],
					'store_tel'    =>$v['store_tel'],
					'store_address'=>$v['store_address'],
					'remark'       =>$v['remark'],
					'update_time'  =>CORE_TIME,
					'update_admin' =>$_SESSION['name'],
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


}