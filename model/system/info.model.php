<?php
/**
 * 信息管理 
 */
class infoModel extends model{
	private $cache=NULL; //缓存
	public function __construct() {
		parent::__construct(C('db_default'), 'info');
	}
	
	public function getListByCate($cate_id,$page=1,$page_size=0){
		$this->where('cate_id in ('.implode(',',(array)$cate_id).') and status=1')->order('sort_order desc,input_time desc');
		if($page && $page_size)
			return $this->page($page,$page_size)->getPage();
		elseif($page_size)
			$this->limit($page_size);
		return $this->getAll();
	}

	public function getListByCateSpell($cate_spell,$page,$page_size){
		$cate_id = M('system:cate')->getfieldbyspell($cate_spell,'cate_id');
		if($cate_id) return $this->getListByCate($cate_id, $page, $page_size);
		return array();
	}

	public function getListByParentCateSpell($parent_cate_spell,$page,$page_size){
		$pid = M('system:cate')->getfieldbyspell($parent_cate_spell,'cate_id');
		if($pid){
			$cate_ids = M('system:cate')->select('cate_id')->where('pid='.$pid)->getCol();
			$cate_ids[] = $pid;
			return $this->getListByCate($cate_ids, $page, $page_size);
		}
		return array();
	}
}
?>
