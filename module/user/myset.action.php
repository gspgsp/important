<?php
/**
 * 账户设置
 */
class mysetAction extends homeBaseAction{

	protected $db;
	public function __init()
	{
		$this->db=M('public:common');
	}

	public function init()
	{
		$this->act="myset";
		$user_info=$this->db->from('customer_contact c')
			->join('contact_info i','c.user_id=i.user_id')
			->leftjoin('admin ad','c.customer_manager=ad.admin_id')
			->where("c.user_id=$this->user_id")
			->select("c.user_id,c.mobile,c.name,c.tel,c.fax,c.c_id,email,i.points,i.thumb,ad.name as ad_name")
			->getRow();
		// p($user_info);

		//公司信息
		$customer=$this->db->model('customer')->where("c_id={$user_info['c_id']}")->getRow();

		$this->area=M('system:region')->get_regions(1);

		$origin=explode('|',$customer['origin']);
		if($origin[0]){
			$this->city=M('system:region')->get_regions($origin[0]);
		}
		$this->assign('origin',$origin);
		$this->assign('user_info',$user_info);
		$this->assign('customer',$customer);
		$this->display('myset');
	}

	public function edit_info()
	{
		if($_POST)
		{
			$this->is_ajax=true;
			if(!$name=trim(sget('name','s',''))) $this->error('姓名不能为空');
			$_data=array(
				'name'=>$name,
				'tel'=>sget('tel','s',''),
				'email'=>sget('email','s',''),
				'fax'=>sget('fax','s',''),
			);
			$this->db->model('customer_contact')->where("user_id=$this->user_id")->update($_data);
			if($thumb=sget('thumb','s','')){
				$this->db->model('contact_info')->where("user_id=$this->user_id")->update(array('thumb'=>$thumb));
			}
			$this->success('修改成功');
		}
	}

	public function edit_company()
	{
		if($_POST){
			$this->is_ajax=true;
			$model=$this->db->model('customer');
			$_data=saddslashes($_POST);
			$c_id=$_SESSION['uinfo']['c_id'];//用户关联客户id
			if(!$customer=$model->where("c_id=$c_id")->getRow()) $this->error('信息不存在');
			if(!trim($_data['c_name'])) $this->error('公司名称不能为空');
			if(!trim($_data['need_product'])) $this->error('需求牌号不能为空');
			if(!trim($_data['address'])) $this->error('公司详细地址不能为空');
			if(!$_data['origin']) $this->error('公司地址不能为空');
			$c_name=trim($_data['c_name']);
			if($c_name!=$customer['c_name'] && $model->where("c_name='{$c_name}'")->getOne()) $this->error('公司已存在，不能重复新建。');
			if($customer['contact_id']!=$this->user_id) $this->error('不是默认联系人不能修改');
			$_data['update_time']=CORE_TIME;
			$_data['origin']=implode('|',$_data['origin']);
			if(!$model->where("c_id=$c_id")->update($_data)) $this->error('操作失败，系统错误。');
			$this->success('操作成功');
		}
	}


}