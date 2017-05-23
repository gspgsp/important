<?php

class indexAction extends homeBaseAction
{

	// 初始api 版本
	protected $api;
	public function __init()
	{
		$this->api = 'qapi1_2';
	}
	// 通讯录
	public function init()
	{
//		if($_GET['token']){
//			$_SESSION['token']=$_GET['token'];
//			$_SESSION['userid']=$_GET['user_id'];
//		}
		$this->display('index.html');
	}
}