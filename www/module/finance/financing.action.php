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
	    $this->finance_type=3;//产品类型
	    $sid=$GLOBALS['CORE_SESS']->getSid();
	    $row = $_SESSION[$sid.'_trycalc_'.'3'];
	    if(empty($row)){
	        $this->row=null;
	        $this->finance_type = 3;
	    }else{
	        $arr = (Array)json_decode($row);
	        $this->row= $arr;
	        $this->finance_type = $arr['finance_type'];//1代采 2白条 3仓单融资
	    }
	    $this->stores=$this->db->model('store')->select("id,store_name")->getAll();//获取当前所有仓库信息
		$this->seo = array('title'=>'仓单融资',);
		$this->display('financing');
	}
}