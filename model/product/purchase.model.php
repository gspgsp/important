<?php 
//订单模型
class purchaseModel extends model{
	public function __construct() {
		parent::__construct(C('db_default'), 'purchase');
	}
	//根据pid获取指定的字段值
	public function getColById($pid=0,$col='id',$value = 'p_id'){
		$result=$this->select("$col")->where("$value =".$pid)->getOne();
		return $result>0 ? $result : 0;
	}
	public function getInfoById($id=0){
		return $this->where("`id` = '$id'")->getRow();
	}
	/**
	 * 获取报价单关联信息
	 */
	public function getPurchaseById($id=0){
		return $this->from('purchase pur')
			->join('product pro','pur.p_id=pro.id')
			->join('factory fa','pro.f_id=fa.fid')
//			->join('lib_region reg','pur.provinces=reg.id')
			->where("pur.id={$id}")
			->select('pur.id,pur.user_id,pur.type,pur.cargo_type,pur.unit_price,pur.number,pur.store_house,pro.model,pro.product_type,fa.f_name,pur.`store_house`')
			->getRow();
	}
	/**
	 * 获取交易订单的所有信息
	 */
	public function getPurchaseInfo($id=0){
		return $this->select('p.*,pd.model, pd.f_id as pdf_id, pd.product_type, pd.process_type, pd.status as pdstatus, pd.remark as pdremark, f.f_name')
                        ->from('purchase p')->leftjoin('product pd','p.p_id=pd.id')
                        ->leftjoin('factory f','f.fid = pd.f_id')
                        ->where("p.id = $id")
                        ->getRow();
	}

	public function getPurPage($where=1,$page=1,$pageSize=10){
		return $this->from('purchase pur')
			->join('product pro','pur.p_id=pro.id')
			->join('factory fa','pro.f_id=fa.fid')
//			->join('lib_region reg','pur.provinces=reg.id')
			->where($where)
			->order('pur.input_time desc')
			->page($page,$pageSize)
			->select('pur.id,pur.supply_count,pur.bargain,pur.user_id,pur.shelve_type,pur.is_union,pur.unit_price,pur.c_id,pur.number,pur.status,pur.cargo_type,pur.period,pur.input_time,pur.type,pro.model,pro.f_id,pro.product_type,pro.process_type,fa.f_name,pur.store_house')
			->getPage();

	}

	/**
	 * 获取最新现货资源
	 * @param int $where
	 * @param int $page
	 * @param int $pageSize
	 * @return mixed
     */
	public function getpur($where=1, $page=1, $pageSize=3){
		return $this->from('purchase pur')
			->join('product pro','pur.p_id=pro.id')
			->join('factory fa','pro.f_id=fa.fid')
			->join('lib_region reg','pur.provinces=reg.id')
			->where($where)
			->order('pur.input_time desc')
			->page($page,$pageSize)
			->select('pur.id,pur.supply_count,pur.bargain,pur.user_id,pur.shelve_type,pur.is_union,pur.unit_price,pur.c_id,pur.number,pur.provinces,pur.status,pur.cargo_type,pur.period,pur.input_time,pur.type,pro.model,pro.f_id,pro.product_type,pro.process_type,fa.f_name,reg.name as cityname')
			->getPage();
	}

	/**
	 * 根据条件查询我的购货信息
	 * @param $where
	 * @param $page
	 * @param $pageSize
     */
	public function getWantBuy($where, $page=1, $pageSize=10)
	{
	    return 	$this->from('sale_buy as s')
				->join('purchase as pur ', 's.p_id=pur.id')
				->join('lib_region as reg', 'pur.province=reg.id')
				->join('product as p', 'p.id=pur.p_id')
				->join('customer as c', 'c.c_id=s.c_id')
				->join('factory as f', 'p.f_id=f.fid')
				->join('union_order as u', 's.id=u.p_sale_id')
				->where($where.'and pur.last_buy_sale=s.id')
				->order('s.input_time desc')
				->page($page, $pageSize)
				->select('c.c_name,s.number,s.price,s.delivery_date,s.status,s.update_time,s.remark, pur.store_house,pur.cargo_type,reg.name,p.product_type,p.model,p.process_type, f.f_name,u.id AS oid')
				->getPage();
	}

	/**
	 * 获取洽谈中的采购信息
	 *
     */
	public function getInfo(){
	    return $this->from('purchase as pur')
					->leftjoin('lib_region as reg','pur.provinces=reg.id')
					->leftjoin('product as p','p.id=pur.p_id')
					->where('pur.status=2 and pur.type=1')
					->order('pur.input_time DESC')
					->select('pur.id,pur.p_id,p.product_type,p.model,pur.number,pur.unit_price ,reg.name,pur.input_time,pur.type,pur.cargo_type')
					->limit('6')
					->getAll();

	}


	/**
	 * 根据关注商品的pid 获取最低价格差
	 * @param $pid
     */
	public function footPrice($arr){

		     return    $res=$this->from('purchase as pur ')
						->leftjoin('product as p','pur.p_id=p.id')
						->leftjoin('factory as f','p.f_id=f.fid')
						->where('pur.p_id='.$arr['p_id'])
						->order('pur.input_time desc')
						->select(' pur.p_id,f.f_name,pur.unit_price,pur.input_time,p.model')
						->limit('2')
						->getAll();


	}
	//可能感兴趣的产品
	public function getInfos($where=1,$page=1,$pageSize=10){
		return $this->from('purchase pur')
			->join('product pro','pur.p_id=pro.id')
			->join('factory fa','pro.f_id=fa.fid')
			->join('lib_region reg','pur.provinces=reg.id')
			->where($where)
//			->order('input_time asc')
			->page($page,$pageSize)
			->select('pur.id,pur.supply_count,pur.bargain,pur.user_id,pur.shelve_type,pur.is_union,pur.unit_price,pur.c_id,pur.number,pur.provinces,pur.status,pur.cargo_type,pur.period,pur.input_time,pur.type,pro.model,pro.f_id,pro.product_type,pro.process_type,fa.f_name,reg.name as cityname')
			->limit('1')
			->getAll();
	}

	/**
	 * 根据订单号获取订单的id
	 * @param string $sn
	 * @return mixed
	 * @Author: yuanjiaye
     */
	public function getoidBysn($sn= ''){
		return $this->model('order')->select('o_id')->where("order_sn = '$sn'")->getOne();
	}

	/**
	 * 根据厂家联系人（user_id）获取厂家报价信息
	 * @param int $where
	 * @param int $page
	 * @param int $pageSize
	 * @return mixed
	 * @Author: yuanjiaye
     */
	public function getPurchaseByUserId($where=1, $page=1, $pageSize=10){

		return $this->from('purchase as pur ')
					->leftjoin('product as pro','pur.p_id=pro.id')
					->leftjoin('factory as fac','pro.f_id=fac.fid')
					->leftjoin('lib_region as reg','pur.provinces=reg.id')
					->where($where)
					->order('pur.input_time desc')
					->page($page,$pageSize)
					->select('pro.product_type,pro.model,fac.f_name,pur.number,pur.unit_price,reg.name,pur.cargo_type,pur.bargain,pur.input_time,pur.store_house')
					->getPage();

	}

	/**
	 * 根据公司c_id 获取该公司报价信息
	 * @param int $where
	 * @return mixed
	 * @Author: yuanjiaye
     */
	public function getPurchasePrice($where=1){
		return $this->from('purchase as pur ')
			->leftjoin('product as pro','pur.p_id=pro.id')
			->leftjoin('factory as fac','pro.f_id=fac.fid')
			->leftjoin('lib_region as reg','pur.provinces=reg.id')
			->where($where)
			->order('pur.input_time desc')
			->select('pro.product_type,pro.model,fac.f_name,pur.number,pur.unit_price,reg.name,pur.cargo_type,pur.bargain,pur.input_time,pur.store_house')
			->limit('8')
			->getAll();
	}

	/**
	 *根据采购及报价表（id）来获取信息
	 * @param int $where
	 * @param int $page
	 * @param int $pageSize
	 * @return mixed
	 * @Author: zhanpeng
	 */
	public function getPurchaseLeftById($where=1,$page='',$size=''){
		 $this->select('pur.id,pur.p_id,pur.user_id,pro.model,pur.unit_price,pur.store_house,fa.f_name,pur.type,pur.content,pur.input_time')->from('purchase pur')
				->leftjoin('product pro','pur.p_id=pro.id')
				->leftjoin('factory fa','pro.f_id=fa.fid')
				->where($where)
				->order('pur.input_time desc');
		if(empty($page)&&empty($size)){
			return $this->getRow();
		}elseif(!empty($page)&&!empty($size)){
			return $this->page($page,$size)->getPage();
		}else{
			return false;
		}

	}


}