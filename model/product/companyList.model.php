<?php
/**
 * 客户公司列表模型
 */
class companyListModel extends Model{
	public function __construct() {
		parent::__construct(C('db_default'), 'big_offers');
	}
	public function getOfferList($where,$order,$page=1,$pageSize=10)
	{
		return $this->from('customer')
			->where($where)
			->select('c_name,address,com_intro')
			->page($page,$pageSize)
			->getPage();
	}
	
}
