<?php
/**
 * 渠道管理 
 */
class chanelModel extends model{
	public function __construct() {
		parent::__construct(C('db_default'), 'chanel');
	}

	/*
	 * 获取渠道列表
	 * @access public
     * @return array
	*/
	public function getChanels(){
		$cache=cache::startMemcache();
		$chanels=$cache->get('chanels');
		if(empty($chanels)){
			$chanels=array();
			$arr=$this->model('chanel')->select("chanel_id as id,name")->where('status=1')->getAll();
			foreach($arr as $v){
				$chanels[$v['name']]=$v['id'];
			}
			$cache->set('chanels',$chanels,86400);
		}
		return $chanels;
	}
	
	/*
	 * 根据渠道名获取渠道ID
	 * @access public
	 * @param string name 渠道名
     * @return int
	*/
	public function getChanelID($name=''){
		$chanels=$this->getChanels();
		if(isset($chanels[$name])){
			return (int)$chanels[$name];
		}
		return 0;
	}

	/*
	 * 根据渠道名获取渠道ID
	 * @access public
	 * @param int id 渠道ID
     * @return string
	*/
	public function getChanelName($id=0){
		static $chanels=array();
		if(empty($chanels)){
			$chanels=$this->getChanels();
			if(!empty($chanels)){ //键值互换
				$chanels=array_flip($chanels);	
			}
		}		
		if(isset($chanels[$id])){
			return $chanels[$id];
		}
		return '';
	}
}
?>