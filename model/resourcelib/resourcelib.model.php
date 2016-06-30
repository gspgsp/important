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
		return $this->where($where)->order('input_time desc')->getAll();
	}


	public function getSearch($ids)
	{
		$ids = implode(',' ,$ids);
		return $this->where("id in ($ids)")->getAll();
	}


	/**
	 * 获取今日报价发布总数
	 * @return mixed
	 */
	public function getTotalOne($date1){
		return  $this->from('resourcelib as res')->where("res.type=1 and res.input_time between  $date1 and ".time()." and res.status=0")
			->select('count(res.id) as total')
			->getAll();
	}
	/**
	 * 获取今日求购发布总数
	 * @return mixed
	 */

	public function getTotalTow($date1){
		return 	$this->from('resourcelib as res')->where("res.type=0 and res.input_time between  $date1 and ".time()." and res.status=0")
			->select('count(res.id) as total')
			->getAll();
	}

	/**
	 *获取最新发布一条的资源信息
	 *
	 */

	public function getNew($status){
		return $this->from('resourcelib as res')
			->where('res.status=0')
			->order('res.input_time desc')
			->select('res.user_nick,res.content as contents')
			->limit('1')
			->getAll();
	}

	
	
}