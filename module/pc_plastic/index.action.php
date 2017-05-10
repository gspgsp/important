<?php

class indexAction extends homeBaseAction
{

	// 通讯录
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
	// info
	public function info()
	{
		$this->display('../pc_plastic/info.html');
	}
	// 求购信息
	public function info_buy()
	{
		$this->display('../pc_plastic/info_buy.html');
	}
	// 供给信息
	public function info_sell()
	{
		$this->display('../pc_plastic/info_sell.html');
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
	// 查自己
	public function credit_1()
	{
		$this->display('../pc_plastic/credit1.html');
	}

	// 查别人
	public function credit_3()
	{
		$this->display('../pc_plastic/credit3.html');
	}

}