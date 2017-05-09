<?php

class indexAction extends homeBaseAction
{
	public function init()
	{

		$this->display('../pc_plastic/index.html');
	}

//	public function buy_sell()
//	{
//		echo '3333';
//		$this->display('../pc_plastic/buy_sell.html');
//	}

	public function login()
	{
//		 $ch=curl_init();
//		 curl_setopt($ch,CURLPOT_URL,'http://test.myplas.com/api/qapi1/login');
//		 curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
//		 curl_setopt($ch,CURLPOT_HEADER,0);
//		 $output=curl_exec($ch);
//		 curl_close($ch);

		$this->display('../pc_plastic/login.html');

	}
	public function center()
	{

	}
}