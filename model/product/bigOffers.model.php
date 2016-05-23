<?php
/**
 * 大客户报价模型
 */
class bigOffersModel extends Model{
	public function __construct() {
		parent::__construct(C('db_default'), 'big_offers');
	}


	public function getOfferList($where,$order,$page=1,$pageSize=10)
	{
		return $this->from('big_offers of')
			->join('big_client cl','of.cid=cl.id')
			->where($where)
			->select('of.*,cl.gsname,cl.qq,cl.phone,cl.lxr,cl.logo,cl.cjphone')
			->order($order)
			->page($page,$pageSize)
			->getPage();
	}
}
