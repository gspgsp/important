<?php 
class IndexAction extends homeBaseAction{

	protected $db;
	public function __init(){

		//资讯分类
		$this->cate=M('system:cate')->where("pid=23")->select('cate_id,cate_name')->getAll();
		
		//最新现货资源
		$purchase=M('product:purchase');
		$this->newOffers=$purchase->getPurPage("type=2 and pur.shelve_type=1 and  pur.cargo_type=1 and pur.status in (2,3,4)");
		$this->newPurchase=$purchase->getPurPage("type=2 and pur.shelve_type=1 and  pur.cargo_type=2 and pur.status in (2,3,4)");
		$this->db=M('public:common');



	}
	//
	public function init()
	{
		//资讯id
		if(!$cate_id=sget('cid','i',0)) $cate_id=$this->cate[0]['cate_id'];
		//分页
		
		$page=sget('page','i',1);
		$page_size=10;
		//获取资讯分页列表
		$list=M('system:info')->getListByCate($cate_id,$page,$page_size);
		$this->pages = pages($list['count'], $page, $page_size);
		$this->assign('list',$list);
		$this->assign('cid',$cate_id);
		$this->display('index.html');

	}

	public function info()
	{
		$id=sget('id','i',0);
		$this->data=M('system:info')->getInfoById($id);
		$this->display('info.html');
	}

	
}


 ?>