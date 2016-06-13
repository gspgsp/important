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

	// 我的报价列表
	public function lists()
	{
		$this->act="offerlist";
		$this->type=2;
		$this->name="报价管理";
		$this->product_type=L('product_type');//产品类型
		$this->shelve_type=L('shelve_type');//上下架状态
		$this->cargo_type=L('cargo_type');//现货期货
		$this->display('mypurchase.list');
	}


}