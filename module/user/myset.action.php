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
		$user_info=$this->db->from('customer_contact c')
			->join('contact_info i','c.user_id=i.user_id')
			->leftjoin('admin ad','c.customer_manager=ad.admin_id')
			->where("c.user_id=$this->user_id")
			->select("c.user_id,c.mobile,c.name,c.tel,c.fax,c.c_id,email,i.points,i.thumb,ad.name as ad_name")
			->getRow();
		// p($user_info);
		$this->assign('user_info',$user_info);
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
}