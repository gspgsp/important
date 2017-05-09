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
	//
	public function buy_sell()
	{
		echo '22';
	}

}