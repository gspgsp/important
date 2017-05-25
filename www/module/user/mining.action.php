<?php
/**
 * Created by PhpStorm.
 * User: yuanjiaye
 * Date: 2016/7/8
 * Time: 14:13
 */

class miningAction extends  homeBaseAction{
	protected $db;
	public function __init()
	{
		$this->db=M('public:common');
	}

	public function init(){
	    $this->product_type=L('product_type');//产品类型
	    $this->model=$this->db->model("product")->select("model")->getAll();
		$this->seo = array('title'=>'塑料代采',);
		$this->display('mining');
	}
	//明细表
	public function detail(){
	    $this->product_type=L('product_type');//产品类型
	    $this->seo = array('title'=>'塑料代采明细',);
	    $this->display('mining_detail');
	}
}