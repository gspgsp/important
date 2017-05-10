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


	// 模块 2  供求
	public function buy_sell()
	{
		$this->display('../pc_plastic/buy_sell.html');
	}
	public function tou_tiao()
	{
		$this->display('');
	}

	// 登录
	public function login()
	{
		$this->display('../pc_plastic/login.html');
	}

	public function forget_pwd()
	{
		$this->display('../pc_plastic/forget_pwd.html');
	}

	public function register()
	{
		$this->display('../pc_plastic/register.html');
	}

	public function agreement()
	{
		$this->display('../pc_plastic/agreement.html');
	}

	//  模块 3  发现 center
	public function dis_center()
	{
		$this->display('../pc_plastic/center2.html');
	}
	// 头条
	public function head_line()
	{
		$this->display('../pc_plastic/headline.html');
	}
	public function head_line_2()
	{
		$this->display('../pc_plastic/headline2.html');
	}

}