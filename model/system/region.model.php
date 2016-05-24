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
	/**
	 * 获取全部的信息
	 */
	public function get_reg(){
		return $this->select('id,name')->getAll();
	}
	/**
	 * 根据id值获取name的值
	 */
	public function get_name($id = 0){
		if(is_array($id)){
			foreach ($id as $k => $v) {
				$link = $k==count($id)-1 ? '': '|';
				$result .= $this->select('name')->where("`id` = $v")->getOne().$link;
			}
			return $result;
		}
		return $this->select('name')->where("`id` = $id")->getOne();
	}
	/**
	 * 根据区域一级id获得所属的华南华东信息
	 */
	 public function get_area($id = 0){
	 	return $this->select('area')->where('id='.$id)->getOne();
	 }

	 /**
	  * 获取所有省份信息，保存在cache中
	  */
	 public function getProvinceCache(){
	 	$key="Province";
		$cache = cache::startMemcache();
		$data = $cache->get($key);
		if(!$data){
			$data=$this->where("pid=1")->order("sort desc")->getAll();
		}
		$cache->set($key,$data);
		return $data;

	 }


	  /*
	  *根据传入的的区域信息返回中文信息
	  */
	  public function get_chinese_area($area = '0|0'){
	 	 $areaArr = explode('|', $area);
	 	 return $this->get_name($areaArr[0]).'|'.$this->get_name($areaArr[1]);
	  }

}
?>