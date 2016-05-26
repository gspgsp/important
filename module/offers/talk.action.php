<?php

class talkAction extends homeBaseAction{

	protected $db;
	public function __init(){
		$this->db=M('public:common');
	}
	public function init()
	{
		if($this->user_id<=0) $this->forward('/user/login');

		$id=sget('id','i',0);
		$data=M('product:purchase')->getPurchaseById($id);
		if($data['type']!=2) $this->forward('/offers');

		$contact=M('user:customerContact')->getContactByuserid($data['user_id']);
		//产品类型
		$product_type=L('product_type');
		$data['product_type']=$product_type[$data['product_type']];
		$data=$data+$contact;
		$this->assign('data',$data);
		$this->display('talk.html');
	}

	public function addorder()
	{
		if($_POST){
			$this->is_ajax=true;
			$data=$_POST;
			if(!$data['number']||!$data['price']||!$data['delivery_date']||!$data['p_id']) $this->error('信息填写不完整');
			$p_id=$data['p_id'];
			$data['p_id']=$p_id;
			$data['delivery_date']=strtotime($data['delivery_date']);
			$data['input_time']=CORE_TIME;
			$data['user_id']=$this->user_id;
			$data['sn']=genOrderSn();
			$this->db->model('sale_buy')->add($data);
			$this->db->model('purchase')->where("id=$p_id")->update("supply_count=supply_count+1");
			$this->success('提交成功');
		}else{
			$this->error('请求错误');
		}

	}
}