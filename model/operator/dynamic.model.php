<?php
/**
 * 调价动态
 */
class dynamicModel extends model{
	protected $cache,$keys;
	public function __construct() {
		parent::__construct(C('db_default'), 'dynamic');
		$this->keys="dynamic_index";
		$this->cache=cache::startMemcache();
	}

	//列表
	public function getList(){
		if($list=$this->cache->get($this->keys)) return $list;
		$list=$this->order('input_time desc')->getAll();
		$this->cache->set($this->keys,$list);
		return $list;
	}
	//删除缓存
	public function delCache(){
		$this->cache->delete($this->keys);
	}
	
}