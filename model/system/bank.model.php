<?php
/**
 * 地区管理 
 */
class bankModel extends model{
	public function __construct() {
		parent::__construct(C('db_default'), 'lib_bank');
	}
	
	/*
	 * 获取所有银行信息
	 * @access public
     * @return array
	 */
	public function get_bank(){
		$arr=$this->select('*')->getAll();
		$bank=array();
		foreach($arr as $k=>$v){
			unset($v['id']);
			$bank[$v['bank_code']]=$v;
		}
		return $bank;
	}
	
	/*
	 * 获取所有银行信息
	 * @access public
     * @return array
	 */
	public function getBankList($chanel=''){
		//把银行列表信息加入缓存
		$cache = cache::startMemcache();
		$key = 'BANK_LIST';
		$data = $cache->get($key);
		if(empty($data)){
			$data = $this->get_bank();
			$cache->set($key,$data,7200);
		}
		if(!empty($chanel)){
			foreach($data as $k=>$b){
				if(empty($b[$chanel.'_code'])){ //不支持sina支付的清除掉
					unset($data[$k]);	
					continue;
				}				
			}
		}
		return $data;
	}

}
?>
