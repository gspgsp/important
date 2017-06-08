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
	// 通讯录个人信息
	public function indexinfo()
	{
	    $this->display('indexinfo.html');
	}
	// 个人求购信息
	public function infosale()
	{
	    $this->display('infosale.html');
	}
	// 个人供给信息
	public function infobuy()
	{
	    $this->display('infobuy.html');
	}
	//用户登录
	public function login()
	{
	    $this->display('login.html');
	}
	// 协议
	public function agreement()
	{
	    $this->display('agreement.html');
	}
	// 找回密码
	public function findpwd()
	{
	    $this->display('findpwd.html');
	}

	//用户注册
	public function register()
	{
	    $this->display('register.html');
	}
	//供给全部信息
	public function releasedetail()
	{
	    $this->display('releasedetail.html');
	}
	/**
	 *发现下的子页面
	*/
	public function headline(){
		$this->display('headline');
	}
	public function headline2(){
		$this->display('headline2');
	}
	public function checkSelf(){
		$this->display('chself');
	}
	public function creditIntro(){
		$this->display('creditIntro');
	}
	public function checkOther(){
		$this->display('chother');
	}
	public function checkOther2(){
		$this->display('chother2');
	}
	// 我的
	public function my()
	{
	    $this->display('my');
	}
	/**
	 * 我的下面子页面
	 * @return [type] [description]
	 */
	public function mySupply()
	{
	    $this->display('mySupply');
	}
	public function myIntro(){
		$this->display('myIntro');
	}
	public function myComment(){
		$this->display('myComment');
	}
	public function myMsg(){
		$this->display('myMsg');
	}
	public function mySudou(){
		$this->display('mySudou');
	}
	public function myHelp(){
		$this->display('myHelp');
	}
	public function myEdit(){
		$this->display('myEdit');
	}
	public function chargeDo(){
		$this->display('chargeDo');
	}
	public function howCharge(){
		$this->display('howCharge');
	}
}