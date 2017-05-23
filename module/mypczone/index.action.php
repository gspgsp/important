<?php

class indexAction extends homeBaseAction
{
	public function __init()
	{
	}
	// 通讯录
	public function init()
	{
		$this->display('index.html');
	}
	// 供求
	public function supplybuy()
	{
	    $this->display('supplybuy.html');
	}
	// 发现
	public function find()
	{
	    $this->display('find.html');
	}
	// 我的
	public function my()
	{
	    $this->display('my.html');
	}
}