<?php
/**
 * Created by PhpStorm.
 * User: xqj
 * Date: 2016/9/13
 * Time: 14:13
 */

class financingAction extends  homeBaseAction{
	protected $db;
	public function __init()
	{
		$this->db=M('public:common');
	}

	public function init(){
// 	    $this->product_type=L('product_type');//产品类型
		$this->seo = array('title'=>'仓单融资',);
		$this->display('financing');
	}
	//明细表
	public function detail(){
	    $this->product_type=L('product_type');//产品类型
	    $this->seo = array('title'=>'仓单融资明细',);
	    $this->display('financing_detail');
	}
}