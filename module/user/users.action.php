<?php
class usersAction extends adminBaseAction{

	public function __init()
	{
		$this->debug = false;
		$this->db=M('public:common')->model('user');

	}

	public function init()
	{
		$action=sget('action','s');
		if($action=='grid'){ //获取列表
			$this->_grid();exit;
		}
		$user_status=L('user_status');
		$this->assign('user_status', $user_status);
		$this->display('users.list.html');
	}


	protected function _grid()
	{
		$page = sget("pageIndex",'i',0); //页码
		$size = sget("pageSize",'i',20); //每页数
		$key_type=sget('key_type','s');
		$where='1';
		if($keyword=sget('keyword','s')){
			switch ($key_type) {
				case 'mobile':
					$where.=" and u.mobile=$keyword";
					break;
				case 'real_name':
					$where.=" and i.real_name='$keyword'";
					break;
				case 'c_name':
					$where.=" and c.c_name='$keyword'";
					break;
				default:
					break;
			}
		}
		if($key_status=sget('key_status','i')){
			$where.=" and u.status=$key_status";
		}

		$list=$this->db
			->from('user u')
			->join('user_info i', 'u.user_id=i.user_id')
			->leftjoin('customer c', 'u.customer_id=c.c_id')
			->leftjoin('admin a', 'u.trader_id=a.admin_id')
			->select('u.user_id,u.mobile,u.last_login,u.status,u.customer_id,u.trader_id,i.real_name,i.qq,i.telephone,i.reg_time,i.remark,c.c_name,c.type,a.name')
			->where($where)
			->page($page+1,$size)
			->order('u.user_id desc')
			->getPage();
		$user_status=L('user_status');
		$company_type=L('company_type');
		foreach ($list['data'] as $key => $value) {
			$list['data'][$key]['company_type']=$company_type[$value['type']];
			$list['data'][$key]['reg_time']=date('Y-m-d H:i:s', $value['reg_time']);
			$list['data'][$key]['last_login']=date('Y-m-d H:i:s', $value['last_login']);
			$list['data'][$key]['status']=$user_status[$value['status']];
		}
		$result=array('total'=>$list['count'],'data'=>$list['data'],'msg'=>'');
		$this->json_output($result);
	}

	public function get_user_status()
	{
		$this->is_ajax=true;
		$uid=sget('uid','i');
		$result=$this->db
			->from('user u')
			->join('user_info i', 'u.user_id=i.user_id')
			->select('u.user_id,u.status,i.remark')->where("u.user_id=$uid")->getRow();
		$this->json_output($result);
	}

	public function updateStatus()
	{
		$this->is_ajax=true;
		$data=sdata();
		$uid=$data['uid'];
		$this->db->where("user_id=$uid")->update(array('status'=>$data['status']));
		$this->db->model('user_info')->where("user_id=$uid")->update(array('remark'=>$data['remark']));
		echo 1;exit;
	}

	public function details()
	{
		//基本信息
		$uid=sget('uid','i');
		$info=$this->db->from('user u')
			->join('user_info i','u.user_id=i.user_id')
			->select('u.*,i.*')
			->where("u.user_id=$uid")
			->getRow();
		$user_status=L('user_status');
		$info['status']=$user_status[$info['status']];
		$sex=L('sex');
		$info['sex']=$sex[$info['sex']];

		//所属公司
		$company=$this->db->model('customer')
			->where("c_id={$info['customer_id']}")
			->getRow();

		$company_type=L('company_type');
		$company['type']=$company_type[$company['type']];
		$company_level=L('company_level');
		$company['level']=$company_level[$company['level']];
		$company_chanel=L('company_chanel');
		$company['chanel']=$company_chanel[$company['chanel']];
		$credit_level=L('credit_level');
		$company['credit_level']=$credit_level[$company['credit_level']];
		$status=L('status');
		$company['status']=$status[$company['status']];

		$this->assign('info',$info);
		$this->assign('company',$company);
		$this->display('users.info.html');
	}


}