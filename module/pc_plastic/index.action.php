<?php

class indexAction extends homeBaseAction
{
	public function init()
	{

		$this->display('../pc_plastic/index.html');
	}
	// 中间
	public function middle(){
		$this->display('../pc_plastic/center.html');
	}
	//右边
	public  function right()
	{
		$this->display('../pc_plastic/right.html');
	}
	// 供求
	public function buy_sell()
	{
		$this->display('../pc_plastic/buy_sell');
	}
	public function tou_tiao()
	{
		$this->display('');
	}

}