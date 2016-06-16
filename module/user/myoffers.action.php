<?php
/**
 * 报价发布--黎贤
 */
class myoffersAction extends userBaseAction{

	protected $db;
	public function __init()
	{
		$this->db=M('public:common');
	}

	public function init()
	{
		$this->act="myoffers";
		$this->name="报价发布";
		$this->type=2;
		
		$this->product_type=L('product_type');//产品类型
		$this->period=L('period');//期货周期
		$this->process_level=L('process_level');//加工级别
		$this->area=M('system:region')->get_regions(1);//地区
		$this->display('mypurchase');
	}

	public function msgToList()
	{
		$id=sget('id','i',0);
		$type=sget('type','i',1);
		$url=$type==1?'mypurchase':'myoffers';
		$_SESSION['msg_pid']=$id;
		$this->forward("/user/{$url}/lists");
	}

	// 我的报价列表
	public function lists()
	{
		$this->act="offerlist";
		$this->type=2;
		$this->name="报价管理";


		if($id=$_SESSION['msg_pid']){
			$_SESSION['msg_pid']=null;
			$size=2;
			$this->db->query('set @mytemp = 0');
			$result=$this->db->query("select nu from (select (@mytemp:=@mytemp+1) as nu,id from p2p_purchase where user_id=$this->user_id and type=$this->type order by input_time desc) as A where A.id=$id");
			$row=mysql_fetch_assoc($result);
			$this->page=ceil($row['nu']/$size);
			if(empty($row)) $id=0;
			$this->assign('id',$id);
		}

		$this->product_type=L('product_type');//产品类型
		$this->shelve_type=L('shelve_type');//上下架状态
		$this->cargo_type=L('cargo_type');//现货期货
		$this->display('mypurchase.list');
	}


	/**
	 * 批量导入报价excel表页面
	 *
     */
	public function offers_excels(){

		$this->assign('type',$_GET['type']);
		$this->display('offers_excels');
	}

	/**
	 * 处理excel文件
	 *
	 *
     */
	public function doExcel(){

		$dir=dirname(__FILE__);
		p($_FILES['excel']);
		if(!empty($_FILES['excel']['tmp_name'])){

		}

	}


}