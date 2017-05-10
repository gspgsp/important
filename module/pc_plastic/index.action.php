<?php

class indexAction extends homeBaseAction
{

	// 初始api 版本
	protected $api;
	public function __init()
	{
		$this->api = "qapi_3";
	}
	// 通讯录
	public function init()
	{

		$this->display('../pc_plastic/index.html');
	}
	// 中间
	public function middle(){
		if($_GET['type']==0){
			header('Content-type:text/html;charset=utf-8');
			$token= "dc7e2474c867a5d553b985b59274579e";
			$url="http://test.myplas.com/api/qapi1/getPlasticPerson?token=".$token;
			$params = array(
//				"keywords" => "",
//				"page" => "",
//				"quan_type" => "",
//				"region" => "",
//				"c_type" => "",
			);
			$postJson=urldecode(json_encode($params));
			echo $postJson;
			$res=$this->http_curl($url,'get','json',$postJson);
			var_dump($res);
		}
		$this->display('../pc_plastic/center.html');
	}
	// 右边
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


	/**
	 *  模块 2  供求
	 */
	public function buy_sell()
	{
		$this->display('../pc_plastic/buy_sell.html');
	}


	/**
	 * 模块 3  发现 center
	 */
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

	/**
	 * 我的  模块
	 *
	 */
	public function my_info()
	{
		$this->display('../pc_plastic/center3.html');
	}


	/*
	* $url 接口url string
	* $type 请求类型 string
	* $res 返回数据类型 string
	* $arr post 请求参数
	*
	* */
	function http_curl($url,$type='get',$res='json',$arr=''){
		//1 初始化curl
		$ch= curl_init();
		//2 设置curl的参数
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);// 将页面以文件流的形式保存
		if($type == 'post'){
			curl_setopt($ch,CURLOPT_POST,1);
			curl_setopt($ch,CURLOPT_POSTFIELDS,$arr);
		}
		//3 采集
		$output= curl_exec($ch);
		//4关闭
		curl_close($ch);
		if($res =='json'){
			if(curl_errno($ch)){
				//请求失败，返回错误信息
				return curl_errno($ch);
			}else{
				//请求成功
				return json_decode($output,true);//ture 或 1;将json转为数组
			}
		}else{
			return json_decode($output,true);//ture 或 1;将json转为数组
		}
	}

}