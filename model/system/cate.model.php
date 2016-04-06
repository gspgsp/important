<?php
/**
 * 分类管理 
 */
class cateModel extends model{
	public function __construct() {
		parent::__construct(C('db_default'), 'cate');
	}
	
	/*
	 * 获取分类
	 * @access public
	 * @param int $cate_type 类型(1信息)
     * @return array
	 */
	public function getTree($cate_type=0){
		$_key='cate_'.$cate_type;
		$cache=cache::startMemcache();
		$data=$cache->get($_key);
		if(empty($data)){
			$arr=$this->getCates($cate_type);
			foreach($arr as $k=>$v){
				$data[$v['id']]=$v['name'];
			}
			$cache->set($_key,$data,86400); //加入缓存
		}
		return $data;
	}

	/*
	 * 获取分类
	 * @access public
	 * @param int $cate_type 类型(1商品,2商户,3媒体,4行业)
	 * @param int $pid 父类ID
	 * @param int $level 等级ID
     * @return array
	 */
	public function getCates($cate_type=0, $pid = 0,$level=0){
		$where='cate_type='.$cate_type.' and status=1';
		if($pid>0){
			$where.=' and pid='.$pid;
		}
		if($level>0){
			$where.=' and level='.$level;
		}
		return $this->select('cate_id id,cate_name name,pid')->where($where)->order('sort_order')->getAll();
	}
}
?>
