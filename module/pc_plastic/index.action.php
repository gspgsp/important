<?php

class indexAction extends homeBaseAction
{
	public function init()
	{

		$this->display('../pc_plastic/index.html');
	}
	// index middle
	public function middle(){
		$this->display('../pc_plastic/center.html');
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