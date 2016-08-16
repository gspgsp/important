<?php
/**
*微信红包支付功能
*/
class hbPayAction extends homeBaseAction{
	public $openid;
	public function __init(){
		$this->db=M('public:common');
		//if(!isset($_SESSION['weixinAuth'])) exit('未授权');
		//$this->openid=$_SESSION['weixinAuth']['openid'];
		$this->openid="o1SYHww1LpW0o6cm2uWngRiD4HmY";
		define(WEIXIN_MCHID,'1324710901');//商户号id
		define(WEIXIN_PARTNERKEY, 'x39kmrlyOBOYvfR3vBlJpnAvkNsmQygJ');//秘钥
	}
	//进入红包支付页面
	public function enPay(){
		$this->display('record');
	}
	//我的奖品
	public function myPrize(){
	    $cache = cache::startMemcache();
	    if(($cache->get('weixinAuth')==null)||($cache->get('weixinAuth')=="")){
	        exit('用户信息为空');
	    }
	    $this->openid = get('weixinAuth')['openid'];
		if(!$userinfo=$this->db->model('weixin_name')->where(saddslashes(array('openid'=>$this->openid)))->getRow()) $this->json_output(array('err'=>6,'msg'=>'微信未授权登录'));
		//详情
		$count = count($this->db->model('weixin_prize')->where(saddslashes(array('oid'=>$userinfo['id'],'status'=>0)))->getAll());
		$money = $this->db->model('weixin_prize')->select("sum('price') as pr")->where(saddslashes(array('oid'=>$userinfo['id'],'status'=>0)))->getOne();
		$name = $userinfo['name'];
		$img = $userinfo['img'];
		//中奖记录
		$no = $this->db->model('weixin_prize')->where(saddslashes(array('oid'=>$userinfo['id'],'status'=>0)))->getAll();//没兑换
		foreach ($no as &$value) {
			$value['addtime'] = date("Y-m-d",$value['addtime']);
		}
		$yes=$this->db->model('weixin_prize')->where(saddslashes(array('oid'=>$userinfo['id'],'status'=>1)))->getAll();//已经兑换
		foreach ($yes as &$value) {
			$value['updatetime'] = date("Y-m-d",$value['updatetime']);
		}
		//返回数据
		$this->json_output(array('err'=>7,'count'=>$count,'money'=>$money,'name'=>$name,'img'=>$img,'no'=>$no,'yes'=>$yes));
	}
	//提现红包
	public function cash(){
		$this->is_ajax=true;
		if(!$userinfo=$this->db->model('weixin_name')->where("openid='{$this->openid}'")->getRow()) $this->json_output(array('err'=>2,'msg'=>'微信未授权登录'));
		//if($userinfo['username']=='') exit($this->json_out(4,'账号未登录'));
		$prizeModel = $this->db->model('weixin_prize');
		// $model=M('weixin_prize');
		$count = $this->db->model('weixin_prize')->select("sum(price) as pr")->where("oid={$userinfo['id']} and status=0")->getOne();
		//$count=$prizeModel->where(array('oid'=>$userinfo['id'],'status'=>0))->sum('price');
		if($count<200) $this->json_output(array('err'=>3,'msg'=>'红包金额不足2元,无法提现。'));
		$prizeModel->startTrans();
		try {//
			if(!$prizeModel->where("oid={$userinfo['id']} and prize=1 and status=0")->update(saddslashes(array('status'=>1,'updatetime'=>time())))) throw new Exception('系统错误。code:101');
			if( !$this->hongbao($this->openid, $count, 1) ) throw new Exception("系统错误。code:103");
			if( !$this->hongbao_log($this->openid, 0, 1, $count, 1) ) throw new Exception("系统错误。code:102");
		} catch (Exception $e) {
			$prizeModel->rollback();
			$this->json_output(array('err'=>4,'msg'=>$e->getMessage()));
		}
		$prizeModel->commit();
		$result=array('count'=>$count/100,'msg'=>'提现成功');
		$this->json_output(array('err'=>5,'result'=>$result));
	}
	//领取红包日志(记录)
	protected function hongbao_log($openid, $type, $status, $price, $num){
		$data = array(
			'openid'		=> $openid,
			'type'			=> $type,
			'status'		=> $status,
			'input_time'	=> time(),
			'price'			=> $price,
			'num'			=> $num,
			);
		return $this->db->model('weixin_hongbao')->add($data);
	}
	//商户同意
	protected function hongbao($re_openid='', $total_amount=100, $total_num=1){
		$this->is_ajax=true;
		$parameter = array(
		   'mch_billno' => $this->get_billno(WEIXIN_MCHID),//商户订单
		   'mch_id' => '1324710901',//商户id
		   'wxappid' => 'wxbe66e37905d73815',//服务号appid
		   'send_name' => '我的塑料网',//红包发送者名称
		   'nick_name'=>'红包',//提供方名称
		   're_openid' => $re_openid,//相对于一脉互通的openid
		   'total_amount' => $total_amount,//付款金额，单位分
		   'min_value'=>100,//最小红包金额，单位分
		   'max_value'=>300,//最大红包金额，单位分
		   'total_num' =>$total_num,//红包収放总人数
		   'wishing' => '我的塑料网红包',//红包祝福诧
		   'client_ip' => $_SERVER['SERVER_ADDR'],//调用接口的机器 Ip 地址
		   'act_name' => '微信红包',//活劢名称
		   'remark' => '微信红包',//备注信息
		   'nonce_str' => $this->rand_str(),//随机字符串
		);
		ksort($parameter);
		$result = $this->formatQueryParaMap($parameter, false);
		$sign = $this->sign($result);
		$parameter['sign'] = $sign;
		$xmlTpl = $this->arrayToXml($parameter);
		p($xmlTpl);
		$url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack';
		$responseXml = $this->curl_post_ssl($url, $xmlTpl);
		//logger($responseXml);
		$postObj = simplexml_load_string($responseXml, 'SimpleXMLElement', LIBXML_NOCDATA);
		if($postObj->result_code == 'SUCCESS'){
		    return true;
		}else{
		    return false;
		}
	}
	/**
	 * [formatQueryParaMap 按照字段名的ASCII 码从小到大排序（字典序）红包接口用]
	 * @param  [type] $paraMap   [生成排序的数组]
	 * @param  [type] $urlencode [false]
	 * @return [type]            [description]
	 */
	protected function formatQueryParaMap($paraMap, $urlencode=false){
		$buff = "";
		ksort($paraMap);
		foreach ($paraMap as $k => $v){
		   if (null != $v && "null" != $v && "sign" != $k) {
		       if($urlencode){
		         $v = urlencode($v);
		      }
		      $buff .= $k . "=" . $v . "&";
		   }
		}
		$reqPar;
		if (strlen($buff) > 0) {
		   $reqPar = substr($buff, 0, strlen($buff)-1);
		}
		return $reqPar;
	}
	/**
	 * [arrayToXml 数组转xml 红包接口用]
	 * @param  [type] $arr [传入数组]
	 * @return [type]      [返回xml]
	 */
	protected function arrayToXml($arr){
		$xml = "<xml>";
		foreach ($arr as $key=>$val)
		{
		  if (is_numeric($val))
		  {
		    $xml.="<".$key.">".$val."</".$key.">"; 
		  }
		  else{
		    $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";  
		  } 
		}
		$xml.="</xml>";
		return $xml; 
	}
	//微信日志
	// protected function logger($postStr, $type=1){
	// 	$model = M('weixin_log');
	// 	$data = array(
	// 		'content' 		=> $postStr,
	// 		'type'	  		=> $type,
	// 		'input_time'	=> time(),
	// 	);
	// 	return $model->add($data);
	// }

	/**
	 * [get_billno 生产商户订单ID 红包接口用]
	 * @param  [type] $mch_id [商户id]
	 * @return [type]         [description]
	 */
	protected function get_billno($mch_id){
	     return  $mch_id . date('Ymd', time()) . rand(1000000000,9999999999);
	}
	/**
	 * [sign 生成md5签名-红包接口用]
	 * @param  [type] $content [formatQueryParaMap 返回的 ASCII 码从小到大排序字符串]
	 * @return [type]          [description]
	 */
	protected function sign($content){
		$signStr = $content . "&key=" . WEIXIN_PARTNERKEY;
		return strtoupper(md5($signStr));
	}

	protected function curl_post_ssl($url, $vars, $second=30,$aHeader=array()){
	   $ch = curl_init();
	   //超时时间
	   curl_setopt($ch,CURLOPT_TIMEOUT,$second);
	   curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
	   //这里设置代理，如果有的话/验证主机
	   curl_setopt($ch,CURLOPT_URL,$url);
	   curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
	   curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);  
	   
	   //cert 与 key 分别属于两个.pem文件
	   curl_setopt($ch,CURLOPT_SSLCERT,FILE_URL.'/myapp/certify/apiclient_cert.pem');
	   curl_setopt($ch,CURLOPT_SSLKEY,FILE_URL.'/myapp/certify/apiclient_key.pem');
	   curl_setopt($ch,CURLOPT_CAINFO,FILE_URL.'/myapp/certify/rootca.pem');
	 
	   if( count($aHeader) >= 1 ){
	      curl_setopt($ch, CURLOPT_HTTPHEADER, $aHeader);
	   }
	 
	   curl_setopt($ch,CURLOPT_POST, 1);
	   curl_setopt($ch,CURLOPT_POSTFIELDS,$vars);
	   $data = curl_exec($ch);
	   if($data){
	      curl_close($ch);
	      return $data;
	   }
	   else { 
	      $error = curl_errno($ch);
	      curl_close($ch);
	      return $error;
	   }
	}
	protected function rand_str($length=32) {
	  $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	  $str = "";
	  for ($i = 0; $i < $length; $i++) {
	    $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
	  }
	  return $str;
	}
}