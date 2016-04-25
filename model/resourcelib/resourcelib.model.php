<?php
/**
 * 资源库信息
 */
class resourcelibModel extends model{
	public function __construct() {
		parent::__construct(C('db_default'), 'resourcelib');
	}


	public function getList($page=0, $pageSize=0, $type='')
	{
		$where = "";
		if($type!==''){
			$where = "type=$type";
		}else{
			$where = "type in (0,1)";
		}
		$pages = $page*$pageSize;
		$ids = $this->select('id')->where($where)->order('input_time desc')->limit("$pages, $pageSize")->getCol();
		$ids = implode(',', $ids);
		$where = "id in ($ids)";
		return $this->where($where)->getAll();
	}


	public function getSearch($ids)
	{
		$ids = implode(',' ,$ids);
		return $this->where("id in ($ids)")->getAll();
	}
	
}