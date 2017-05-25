<?php
class indexAction extends homeBaseAction{

	protected $productModel,$articleModel;
	public function __init(){
		$this->db=M('public:common');
		$this->productModel=M('mycompany:product');
		$this->articleModel=M('mycompany:article');
		$this->purchaseModel=M('product:purchase');
		$this->sourceModel = M('resourcelib:resourcelib');
		$this->newsCateModel   =M('public:common')->model('news_content');
	}
	public function init(){
		$userId=$_SESSION['userid'];
		$type = sget('type', 's', '');
		$c_id=sget('id','i',0);
		//网站首页 关于我们
		if(!$company=$this->db->model('customer')->where("c_id=$c_id")->getRow()) $this->forward('/');

		$contact=$this->db->model('customer_contact')->where("user_id={$company['contact_id']}")->getRow();
		//网站首页 产品展示
		$this->product_index=$this->productModel->where("cid=$c_id")->order('input_time desc')->limit(3)->getAll();
		//网站首页 最新资讯
//		$this->article_index=$this->articleModel->where("cid=$c_id")->order('input_time desc')->limit(8)->getAll();
		$news=M('public:common')->model('news_content')->where("cate_id=7  AND STATUS=1 AND hot=1")->order('update_time desc')->select('id,title,update_time,type')->limit(8)->getAll();

		//最新资讯
		$articleCateTemp=L('article_kind');
		$articleCate=array();
		foreach ($articleCateTemp as $key => $value) {
			$articleCate[$key+1]=$this->articleModel->where("type=$key and cid=$c_id")->order('input_time desc')->getAll();
		}
		//产品展示
		$productTemp=L('product_kind');
		$productCate=array();
		foreach ($productTemp as $key => $value) {
			$productCate[$key]=$this->productModel->where("type=$key and cid=$c_id")->order('update_time desc')->getAll();
		}
		//商城报价
		$where="pur.c_id={$c_id} and pur.shelve_type=1 and pur.type=2";
		$list=$this->purchaseModel->getPurchasePrice($where);
		foreach($list['data'] as $key => $value ){
			$list['data'][$key]['number']=($value['number']==0.00)?'':$value['number'];

		}
		//我的资源库
		$page = sget('page', 'i', 1);
		$pageSize = 3;
		$res = $this->sourceModel->getResource(abs($page-1), $pageSize, $type,$userId);
		$count = $type == '' ? $this->countall : ($type == 1 ? $this->count1 : $this->count2);
		$pages=$this->pages = pages($count, $page, $pageSize);

		$this->assign('res',$res);
		$this->assign('articleCate',$articleCate);
		$this->assign('productCate',$productCate);
		$this->assign('company',$company);
		$this->assign('contact',$contact);
		$this->assign('news',$news);
		$this->assign('list',$list);
		$this->display('index');
	}

	//资讯详情
	public function getArticleDetail()
	{
		if($_POST){
			$this->is_ajax=true;
			$id=sget('id','i',0);
			$data=$this->articleModel->wherePk($id)->getRow();
			$this->success($data);
		}
	}
}