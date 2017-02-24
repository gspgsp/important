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
	    $this->finance_type=1;//产品类型
	    $sid=$GLOBALS['CORE_SESS']->getSid();
	    $row = $_SESSION[$sid.'_trycalc_'.'1'];
	    if(empty($row)){
	        $this->row=null;
	        $this->finance_type = 1;
	    }else{
	        $arr = (Array)json_decode($row);
	        $this->row= $arr;
	        $this->finance_type = $arr['finance_type'];//1代采 2白条 3仓单融资
	    }
	    $this->model=$this->db->model("product")->select("model")->getAll();
		$this->seo = array('title'=>'塑料代采',);
		$this->display('mining');
	}

	
	

}