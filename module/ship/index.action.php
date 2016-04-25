<?php 
class indexAction extends homeBaseAction{

	public function init()
	{
		
		$this->display('index.html');
	}


	public function get_price()
	{
		if($_POST){
			$starting = sget('s', 's', '');
			$ending  = sget('e', 's', '');
			$weight  = sget('w', 'i', 0);
			$province  = sget('p', 's', '');
			if(empty($starting)) die(json_encode(array('err'=>'1','msg'=>'发货起始地不能为空！')));
			if(empty($ending)) die(json_encode(array('err'=>'1','msg'=>'卸货地不能为空！')));
			if(empty($weight)) die(json_encode(array('err'=>'1','msg'=>'请输入货物重量！')));
			if(empty($province)) die(json_encode(array('err'=>'1','msg'=>'送达省不能为空！')));
			if($weight<5) die(json_encode(array('err'=>'1','msg'=>'请输入正确的货物重量')));
			$Ship = M('operator:ship_price');
			if(!$Ship->where("`start`='$starting'")->getRow()) die(json_encode(array('err'=>'1','msg'=>'您输入的发货地暂时不在受理范围！')));
			if(!$Ship->where("`end`='$ending'")->getRow())  die(json_encode(array('err'=>'1','msg'=>'您输入的卸货地暂时不在受理范围哦！')));
			if($weight<5) die(json_encode(array('err'=>'1','msg'=>'您的货物小于5吨，请联系客服电话协商')));
			if($weight>=5)  $key_type = '5to10';
			if($weight>=10)  $key_type = '10to15';
			if($weight>=15)  $key_type = '15to20';
			if($weight>=20)  $key_type = '20to25';
			if($weight>=25)  $key_type = '25to30';
			if($weight>30)  $key_type = '30plus';
			$shop_info = $Ship->where(" 1 AND `start`='$starting' AND `end` = '$ending' AND `cities` = '$province'")->getRow();
			if(empty($shop_info))   die(json_encode(array('err'=>'1','msg'=>'您查找的信息不存在')));
			$price = $shop_info['addition']==0 ? $shop_info["$key_type"]*$weight : ($shop_info["$key_type"]+$shop_info["addition"])*$weight;
			die(json_encode(array('err'=>'0','msg'=>$price)));
		}
	}
















}


