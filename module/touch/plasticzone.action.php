<?php
/**
*塑料圈控制器
*/
class plasticzoneAction extends homeBaseAction
{
	public function __init() {
		$this->debug = false;
		$this->db=M('public:common');
    }
    public function init(){
    	$this->display('');
    }
}