<?php
/**
 * Created by PhpStorm.
 * User: yuanjiaye
 * Date: 2016/7/8
 * Time: 14:13
 */

class iousAction extends  homeBaseAction{
	protected $db;
	public function __init()
	{
		$this->db=M('public:common');
	}

	public function init(){
	    $this->finance_type=2;//产品类型
	    $sid=$GLOBALS['CORE_SESS']->getSid();
	    $row = $_SESSION[$sid.'_trycalc_'.'2'];
	    if(empty($row)){
	        $this->row=null;
	        $this->finance_type = 2;
	    }else{
	        $arr = (Array)json_decode($row);
	        $this->row= $arr;
	        $this->finance_type = $arr['finance_type'];//1代采 2白条 3仓单融资
	    }
		$this->seo = array('title'=>'塑料白条',);
		$this->display('ious');
	}
}