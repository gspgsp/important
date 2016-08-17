<?php

class indexAction extends homeBaseAction{

	protected $db;
	public function __init(){
		$this->db=M('public:common');
	}

	public function init()
	{	
		$where=1;
		$order="customer.c_id desc";                
		//公司筛选
		if($copN=sget('copName','s','')){
			$this->assign('copName',$copN);
			$where.=" and c_name like '%$copN'";
		}
		//地址筛选
		if($address=sget('address','s','')){
			$this->assign('address',$address);
			$where.=" and address like '%$address%'";
		}
		//公司列表
		$page=sget('page','i',1);
		$pageSize=20;
		$list=M('product:companyList')->getOfferList($where,$order,$page,$pageSize);
		$this->seo = array('title'=>'客户公司详情',);
		$this->pages=pages($list['count'],$page,$pageSize);
		$this->assign('list',$list);
		$this->display('index');
	}
}