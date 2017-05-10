<?php

class indexAction extends homeBaseAction
{

	// 初始api 版本
	protected $api;
	public function __init()
	{
		$this->api = "qapi1_2";
	}
	// 通讯录
	public function init()
	{

		$this->display('../pc_plastic/index.html');
	}
	// 中间
	public function middle(){

			header('Content-type:text/html;charset=utf-8');
			$token= "dc7e2474c867a5d553b985b59274579e";
			$url='http://test.myplas.com/api/'.$this->api.'/getPlasticPerson?token='.$token;
			$params = array(
				"keywords" => "",
				"page" => "",
				"quan_type" =>"",
				"region" => "",
				"c_type" => "",
			);
			$postJson=urldecode(json_encode($params));
			$res=$this->http_curl($url,'get','json',$postJson);
			if($res['err']==0){
				$template='';
				$str='';
				$str.='<li data-val="'.$res["top"]["user_id"].'">
                <!--pic begin-->
                <div class="pic flt">
                    <img src="'.$res["top"]["thumb"].'">
                    <div class="authen no">V</div>
                </div>
                <div class="info flt">
                    <p>
                        <span class="company">上海企辉物流有限公司</span>
                        <span class="name">张飞扬 女</span>
                    </p>
                    <p>
                        <span class="supply">供：196</span>
                        <span class="demand">求：34</span>
                    </p>
                    <p>主营:LDPE,LLDPE,HDPE,1000S,7042...</p>
                </div>
           
                <div class="set-top">已置顶</div>
              
        	</li>';
				foreach ($res['persons'] as $val){
					$template.='
                     <li data-attr="1">
			                <div class="pic flt">
			                    <img src="'.$val["thumb"].'">
			                    <div class="authen no">V</div>
			                </div>
			                <div class="info flt">
			                    <p>
			                        <span class="company">'.$val["c_name"].'</span>
			                        <span class="name">'.$val["name"].' '.$val["sex"].'</span>
			                    </p>
			                    <p>
			                        <span class="supply">供：'.$val["buy_count"].'</span>
			                        <span class="demand">求：'.$val["sale_count"].'</span>
			                    </p>
			                    <p>主营:'.$val["need_product"].'</p>
			                </div>
                     </li>';
				}

			}
		$this->assign('str',$str);
		$this->assign('template',$template);
		$this->display('../pc_plastic/center.html');
	}

	// 个人info 详情
	public function info()
	{
		if($_GET['user_id']){
			header('Content-type:text/html;charset=utf-8');
			$token= "3bf198c15c2b3b98bd41832df8445a89";
			$url='http://test.myplas.com/api/'.$this->api.'/getZoneFriend?token='.$token;
			$params = array(
				"userid" => $_GET['user_id'],
			);
			$postJson=urldecode(json_encode($params));
			$res=$this->http_curl($url,'get','json',$postJson);
			var_dump($res);
		}

		$this->display('../pc_plastic/info.html');
	}
	// 默认右边
	public  function right()
	{
		$this->display('../pc_plastic/right.html');
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