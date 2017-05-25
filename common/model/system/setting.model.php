<?php
/**
 * 系统设置 
 */
class settingModel extends model{
	public function __construct() {
		parent::__construct(C('db_default'), 'setting');
	}
	
	/*
	 * 获取系统设置
	 * @access public
	 * @return array
	 */
	public function getSetting(){
		$_key='setting';
		$cache=cache::startMemcache();
		$data=$cache->get($_key);
		if(empty($data)){
			$arr=$this->getAll();
			foreach($arr as $k=>$v){
				$value=$v['value'];
				if($value && $value{0} == '{'){
					$value=json_decode($value,true);
				}
				$data[$v['code']]=$value;
			}
			$cache->set($_key,$data,86400); //加入缓存
		}
		return $data;
	}

	/**
	 * 获取单个系统设置
	 * @param  string $code 设置key
	 * @return mixed
	 */
	public function get($code){
		$_key='setting_'.$code;
		$cache=cache::startMemcache();
		$data=$cache->get($_key);
		if(empty($data)){
			$data = $this->getfieldbycode($code,'value');
			$data = json_decode($data, TRUE) ?: $data;
			$cache->set($_key,$data,86400); //加入缓存
		}
		return $data;
	}

	public function del_cache($key)
	{
		if(empty($key))
		{
			return false;
		}
		$cache=cache::startMemcache();
		$data=$cache->get($key);
		if(!empty($data))
		{
			$cache->delete($key);
		}

		return true;
	}
}
?>