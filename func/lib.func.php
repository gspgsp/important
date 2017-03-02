<?php
/*
 * 生成签名串
 * @param string $string 签名串
 * @return string
*/
function desEncrypt($string){
	$des=new desSecurity(C('DES_PASSCODE'));
	return $des->encrypt($string);
}	

/*
 * 解密签名串
 * @param string $string 签名串
 * @return string
*/
function desDecrypt($string=''){
	$des=new desSecurity(C('DES_PASSCODE'));
	return $des->decrypt($string);
}

function genOrderSn($type=1){
	$date=date("YmdHis");
	//日期+交易类型+时分+6为随机
	// return substr($date,0,8).str_pad($type, 4, '0', STR_PAD_RIGHT).substr($date,8,4)
	// 		.str_pad(mt_rand(1000, 999999), 6, '0', STR_PAD_LEFT);
	//return time().str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
	return substr($date,0,12).str_pad(mt_rand(10, 9999), 4, '0', STR_PAD_LEFT);
}


//获取某类系统交易参数
function getTradeParam($key=''){
	$param=getSystemParam('param_fee');
	if(!empty($key)){
		return 	$param[$key];
	}
	return $param;
}

//获取某类系统参数
function getSystemParam($key=''){
	$sys=M('system:setting')->getSetting();
	if(!empty($key)){
		return 	$sys[$key];
	}
	return $sys;
}


//[前台]检查验证码
function chkVcode($name='',$value=''){
	$name='vc_'.$name;
	if(isset($_SESSION[$name]) && $_SESSION[$name]==$value){
		return true;	
	}
	return false;
}
function unsetVcode($name=''){
	$name='vc_'.$name;
	unset($_SESSION[$name]);
}
/*
 * 隐藏字符
 * @param string $str 银行卡号
 * $lcon 卡号的前几位，$rcon卡后的后几位，$count隐藏的位数 
 * @return str 
*/
function hideStr($str='',$lcon=0,$rcon=0){
	$newStr="";
	$strLen = strlen($str);
	$newStr = substr($str,0,$lcon);
	$count = $strLen-$lcon-$rcon;
	for($i=0;$i<$count;$i++){
		$newStr .= "*";
	}
	$newStr .= substr($str,-$rcon);
	return $newStr;
}

function createItemNO($ctype='001',$itemId){
	$prefix = "";
	$NO = "";
	$NO = $prefix.$ctype;
	$NO .=sprintf("%08d", $itemId);
	return $NO;
}
/*
 * 检查银行卡号是否合法
 * @param string $bankno 银行卡号
 * 1.将未带校验位的 15（或18）位卡号从右依次编号 1 到 15（18），位于奇数位号上的数字乘以 2。
 * 2.将奇位乘积的个十位全部相加，再加上所有偶数位上的数字。
 * 3.将加法和加上校验位能被 10 整除。   
 * @return array('err','msg')
*/
function validBankNo($bankno){
	$strLen = strlen($bankno);
	if ($strLen < 16 || $strLen > 19) {
		return array('err'=>1,'msg'=>'银行卡号长度不正确');
	}
	
	if(!preg_match('/^\d*$/',$bankno)){
		return array('err'=>1,'msg'=>'银行卡号必须全为数字');
	}
	//开头6位
	$strBin=array(10,18,30,35,37,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,58,60,62,65,68,69,84,87,88,94,95,98,99); 
	$vkey = substr($bankno,0,2);
	
	if(!in_array($vkey,$strBin)){ 
		return array('err'=>1,'msg'=>'银行卡号开头6位不符合规范');
	}
	
	$lastNum = substr($bankno,-1);//取出最后一位（与luhm进行比较）	
	$first15Num = substr($bankno,0,strlen($bankno)-1); //前15或18位
	$newArr = array();
	
	for($i=strlen($first15Num)-1;$i>-1;$i--){    //前15或18位倒序存进数组
		array_push($newArr,$first15Num{$i});
	}
	$arrJiShu = array();  //奇数位*2的积 <9
	$arrJiShu2 = array(); //奇数位*2的积 >9
	 
	$arrOuShu=array();  //偶数位数组
	for($j=0;$j<count($newArr);$j++){
		if(($j+1)%2==1){//奇数位
			if($newArr[$j]*2<9)
				array_push($arrJiShu,$newArr[$j]*2);
			else
				array_push($arrJiShu2,$newArr[$j]*2);
		}else{ //偶数位
			array_push($arrOuShu,$newArr[$j]);
		}
	}
	 
	$jishu_child1=array();//奇数位*2 >9 的分割之后的数组个位数
	$jishu_child2=array();//奇数位*2 >9 的分割之后的数组十位数
	for($h=0;$h<count($arrJiShu2);$h++){
		array_push($jishu_child1,intval($arrJiShu2[$h]%10));
		array_push($jishu_child2,intval($arrJiShu2[$h]/10));
	}       
	 
	$sumJiShu=0; //奇数位*2 < 9 的数组之和
	$sumOuShu=0; //偶数位数组之和
	$sumJiShuChild1=0; //奇数位*2 >9 的分割之后的数组个位数之和
	$sumJiShuChild2=0; //奇数位*2 >9 的分割之后的数组十位数之和
	$sumTotal=0;
	for($m=0;$m<count($arrJiShu);$m++){
		$sumJiShu=$sumJiShu+$arrJiShu[$m];
	}
	for($n=0;$n<count($arrOuShu);$n++){
		$sumOuShu=$sumOuShu+$arrOuShu[$n];
	}
	for($p=0;$p<count($jishu_child1);$p++){
		$sumJiShuChild1=$sumJiShuChild1+$jishu_child1[$p];
		$sumJiShuChild2=$sumJiShuChild2+$jishu_child2[$p];
	}     
	//计算总和
	$sumTotal=$sumJiShu+$sumOuShu+$sumJiShuChild1+$sumJiShuChild2;
	 
	//计算Luhm值
	$k= intval($sumTotal%10)==0?10:intval($sumTotal%10);       
	$luhm= 10-$k;
	 
	if($lastNum==$luhm){
		return array('err'=>0,'msg'=>'验证通过');
	}else{
		return array('err'=>1,'msg'=>'银行卡号不正确，请重新输入！');
	}       
}

/*
 * 检查企业银行卡号是否合法
 * @param string $bankno 银行卡号
 * 1.企业卡号为9到22位。
 * 2.卡号全为数字。
 * @return array('err','msg')
*/
function validCompanyBankNo($bankno){
	$strLen = strlen($bankno);
	if ($strLen < 9 || $strLen > 26) {
		return array('err'=>1,'msg'=>'银行卡号长度不正确');
	}
	
	if(!preg_match('/^\d*$/',$bankno)){
		return array('err'=>1,'msg'=>'银行卡号必须全为数字');
	}
	return array('err'=>0,'msg'=>'验证通过');

}

//根据手机号查询用户所在城市
function getCityByMobile($mobile=''){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'http://apis.juhe.cn/mobile/get?phone='.$mobile.'&key=d34abb87b2619a4614773abe03e28bc2');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	$output = curl_exec($ch);
	curl_close($ch);
	$ch = curl_init();
	$res = json_decode($output,true);
	if(!empty($res['result'])){
		return $res['result'];
	}else{
		return '';
	}
}

//根据ip查询用户所在城市
function getCityByIP($ip=''){
	//太平洋接口
	$_info=file_get_contents("http://whois.pconline.com.cn/ip.jsp?ip=".$ip);
	if(!empty($ip)){
		return gbk2utf($_info);
	}
	
	//淘宝接口
	$_info=file_get_contents("http://ip.taobao.com/service/getIpInfo.php?ip=".$ip);
	$_info=json_decode($_info,true);
	if(isset($_info['data'])){
		return $_info['data']['region'].$_info['data']['city'];
	}
	return '';
}
//对数字进行加减处理
function operationAlphaID($in,$plus = false){
	if ($plus) { 
		$out = alphaID($in+10000000);
	} else {
		$out = alphaID($in,true)-10000000;
	}
	return $out;
}
function xmlNode($xml='',$node=''){
	preg_match('/<'.$node.'>(.*?)<\/'.$node.'>/',$xml,$match);
	return $match[1];
}

//获取联盟密码
function getUnionPasswd($passwd=''){
	return md5($passwd.'ssr.union');
}

//获取年龄
function getAge($birthday) {
	$age = 0;
	$year = $month = $day = 0;
	if($birthday!="0000-00-00"){
		if(is_array($birthday)) {
			extract($birthday);
		} else {
			if (strpos($birthday, '-') !== false) {
				list($year, $month, $day) = explode('-', $birthday);
				$day = substr($day, 0, 2); //get the first two chars in case of '2000-11-03 12:12:00'
			}
		}
	
		$age = date('Y') - $year;
		if (date('m') < $month || (date('m') == $month && date('d') < $day)) $age--;
	}
	return $age;
}

//抽奖概率
function getProbRand($proArr) { 
    $result = ''; 
 
    //概率数组的总概率精度 
    $proSum = array_sum($proArr); 
 
    //概率数组循环 
    foreach ($proArr as $key => $proCur) { 
        $randNum = mt_rand(1, $proSum); 
        if ($randNum <= $proCur) { 
            $result = $key; 
            break; 
        } else { 
            $proSum -= $proCur; 
        } 
    } 
    unset ($proArr); 
 
    return $result; 
} 

//将序列数组转为关联数组 
//如array(array('key'=>1,'value'=>mixed),array('key'=>2,'value'=>mixed)) to array('1'=>mixed,'2'=>mixed)
function arrayKeyValues(array $arr,$key,$value=''){
	$data = array();
	if($arr){
		foreach($arr as $r){
			$data[$r[$key]] = $value ? $r[$value] : $r;
		}
	}
	return $data;
}

//提取数组中指定keys,用于只显示部分字段
function arrayExtractKeysValue(array $arr,array $keys){
	extract($arr);
	return call_user_func_array('compact',$keys);
}

//生成新浪短链接
function shortenURL($long_url){
	$api_url='http://api.weibo.com/2/short_url/shorten.json?source=2701006471&url_long='.$long_url;
	$data = json_decode(file_get_contents($api_url));
	return is_object($data) && $data->urls ? $data->urls[0]->url_short : $long_url;
}
function getTimeFilterByDateTime($field="",$start='startTime',$end='endTime'){
	$string='';	
	$starTime=sget($start,'s'); //开始时间
	$endTime=sget($end,'s');  //结束时间
	if(!empty($starTime)){
		if(strlen($starTime)==10) $starTime.=" 00:00:00";
		$string.=" and $field>='".$starTime."'";
	}
	if(!empty($endTime)){
		if(strlen($endTime)==10) $endTime.=" 23:59:59";
		$string.=" and $field<='".$endTime."'";	
	}
	return $string;
}
?>
