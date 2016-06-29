<?php
class indexAction extends homeBaseAction{

	protected $productModel,$articleModel;
	public function __init(){
		$this->db=M('public:common');
		$this->productModel=M('mycompany:product');
		$this->articleModel=M('mycompany:article');

	}
	public function init(){
		$c_id=sget('id','i',0);
		if(!$company=$this->db->model('customer')->where("c_id=$c_id")->getRow()) $this->forward('/');
		$contact=$this->db->model('customer_contact')->where("user_id={$company['contact_id']}")->getRow();

		$this->product_index=$this->productModel->where("cid=$c_id")->order('input_time desc')->limit(3)->getAll();
		$this->article_index=$this->articleModel->where("cid=$c_id")->order('input_time desc')->limit(8)->getAll();

		$articleCateTemp=L('article_kind');
		$articleCate=array();
		foreach ($articleCateTemp as $key => $value) {
			$articleCate[$key+1]=$this->articleModel->where("type=$key")->order('input_time desc')->getAll();
		}
		$productTemp=L('product_kind');
		$productCate=array();
		foreach ($productTemp as $key => $value) {
			$productCate[$key]=$this->productModel->where("type=$key")->order('input_time desc')->getAll();
		}

		$this->assign('articleCate',$articleCate);
		$this->assign('productCate',$productCate);
		$this->assign('company',$company);
		$this->assign('contact',$contact);
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