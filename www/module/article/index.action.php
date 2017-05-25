<?php 
class IndexAction extends homeBaseAction{

	protected $db;
	public function __init(){
		
		//资讯分类
		$this->cate=M('system:cate')->where("pid=23")->select('cate_id,cate_name')->getAll();
		//最新现货资源
		$purchase=M('product:purchase');
		$this->newOffers=$purchase->getpur("type=2 and pur.shelve_type=1 and  pur.cargo_type=1 and pur.status in (2,3,4)");
		$this->newPurchase=$purchase->getpur("type=2 and pur.shelve_type=1 and  pur.cargo_type=2 and pur.status in (2,3,4)");
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
		$title = $cate_id==29 ? '市场点评' : ($cate_id==30 ? '行业热点' : ($cate_id==31 ? '企业调价' : ($cate_id==32 ? '装置动态' : ($cate_id==33 ? '塑料期货' : '行业资讯'))));
		$this->seo = array('title'=>$title.'-塑料头条');
		$this->display('index.html');

	}

	public function info()
	{
		$id=sget('id','i',0);
		$data=sstripslashes(M('system:info')->getInfoById($id));
		$data['cate_name']=$data['cate_id']==29 ? '市场点评' : ($data['cate_id']==30 ? '行业热点' : ($data['cate_id']==31 ? '企业调价' : ($data['cate_id']==32 ? '装置动态' : ($data['cate_id']==33 ? '塑料期货' : '行业资讯'))));
		$this->ids=$this->db->model('info')->select('id,title')->where("cate_id={$data['cate_id']}")->order('update_time desc')->limit('10')->getAll();
		$this->seo = array('title'=>$data['title'],'keywords'=>$data['keywords'],'description'=>$data['description'],'cate_name'=>$data['cate_name']);
		$this->assign('data',$data);
		$this->display('info.html');
	}

	
}


 ?>