<?php
/**
 * 地区管理 
 */
class regionModel extends model{
	public function __construct() {
		parent::__construct(C('db_default'), 'lib_region');
	}
	
	/*
	 * 获取所有地区
	 * @access public
	 * @param int $pid 父类ID
	 * @param int $level_id 等级ID
     * @return array
	 */
	public function get_allRegions(){
		//把地区信息加入缓存
		$cache = cache::startMemcache();
		//$cache->delete('region_list');
		$cache_key = 'region_list';
		$data = $cache->get($cache_key);
		if(empty($data)){
			$data = $this->select('id,name,pid,level_id')->getAll();
			$data = arrayKeyValues($data,'id');
			$cache->set($cache_key,$data,7200);
		}
		return $data;
	}
	
	/*
	 * 获取地区
	 * @access public
	 * @param int $pid 父类ID
	 * @param int $level_id 等级ID
     * @return array
	 */
	public function get_regions($pid = 0){
		return $this->select('id,name')->where('pid='.$pid)->getAll();
	}
	
}
?>