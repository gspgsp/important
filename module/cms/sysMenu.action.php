<?php
/*
 * 微信自动回复
*/
class sysMenuAction extends adminBaseAction {
	protected $output=array(); //待输出参数
	protected $sys = null;
	protected $is_ajax = true;
	private $appid  = "";
	private $secret = "";
	private $sub_appid  = "";
	private $sub_secret = "";
	private $weixin_url = "https://api.weixin.qq.com/";
	
	public function __init(){
		$setting = M('system:setting')->get('weixin');
		$this->appid=$setting['appid'];
		$this->secret=$setting['secret'];
		$this->sub_appid  = $setting['serve_appid'];
		$this->sub_secret = $setting['serve_secret'];
	}
	
	/**
	 * 设置菜单	
	 * @access public 
	 * @return json
	 */
	public function setMenu(){
		$param=array(
			'name'=>array('rule'=>'*1-30','msg'=>'菜单名长度不超过8个汉字或16个英文字符'),
			'mark' =>array('rule'=>'normal','msg'=>'菜单位置输入不能为空'),
			'level'=>array('rule'=>'normal1-2','msg'=>'菜单等级输入不正确'),
			'type'=>array('rule'=>'normal','msg'=>'key设置不正确','need'=>'n'),
			'keyname'=>array('rule'=>'*1-255','msg'=>'keyname设置不正确','need'=>'n'),
			'menu_string'  =>array('rule'=>'*','msg'=>'菜单数据不能为空'),
		);
		$data=$this->getParam($param);
		// 检查回复的文本
		if(is_array($_POST['menu_string'])){
			$menu_string = json_encode($_POST['menu_string']);
		}else{
			$menu_string = $_POST['menu_string'];
		}
		$data['menu_string'] = addslashes($menu_string);
		$data['create_time'] = time();
		// 写入菜单模板
		$result = M("system:wxMenu")->setMenu($data);
		if($result&&empty($result['msg'])){
			$result['menu_string'] = json_decode($result['menu_string'],true); 
			$this->output = $result;
			$this->success("添加成功");
		}else if($result['msg']){
			$this->output = array('msg'=>$result['msg']);
		}
		$this->error();
	}
	
	/**
	 * 编辑菜单	
	 * @access public 
	 * @return json
	 */
	public function editMenu(){
		
		$param=array(
			'id'=>array('rule'=>'*1-10','msg'=>'id不能为空'),
			'name'=>array('rule'=>'*1-30','msg'=>'菜单名长度不超过8个汉字或16个英文字符'),
			'mark' =>array('rule'=>'normal','msg'=>'菜单位置输入不能为空'),
			'level'=>array('rule'=>'normal1-2','msg'=>'菜单等级输入不正确'),
			'type'=>array('rule'=>'normal','msg'=>'key设置不正确','need'=>'n'),
			'keyname'=>array('rule'=>'*1-255','msg'=>'keyname设置不正确','need'=>'n'),
			'menu_string'  =>array('rule'=>'*','msg'=>'菜单数据不能为空'),
		);
		$data=$this->getParam($param);		
		$data['status']=sget("status","i");
		
		$reply_string = $_POST['menu_string']['reply'];
		// 检查回复的文本
		if(is_array($_POST['menu_string'])){
			$menu_string = json_encode($_POST['menu_string']);
		}else{
			$menu_string = $_POST['menu_string'];
		}
		$data['menu_string'] = addslashes($menu_string);
		$data['create_time'] = time();
		
		//
		if(M("system:wxMenu")->getMenuDetail('',$data['mark'],$data['level'])&&$data['level']==1){
			$mark_main =M("system:wxMenu")->getMenuDetail('',$data['mark'],$data['level']);
			if($mark_main[id]!=$data['id']){
				$this->error("变更目标一级菜单已经存在，不能变更");
			}
			if($mark_main['mark']!=$data['mark']){
				$this->error("一级菜单不能变更");
			}
			//$result = M("system:wxMenu")->editMenu($data,'',$data['mark']);
		}
		// 写入菜单模板
		$result = M("system:wxMenu")->editMenu($data,$data['id'],$data['mark']);
		
		//keyevent内容添加自动回复模板
		if((int)$data['type']===3){
			$rtype = explode("_",$data['keyname']);
			$reply_type = ($rtype[1]=="news"?2:1);
			$reply = array(
				'id' => '',
				'title'      =>$data['name'],
				'use_type'   => 2,
				'input_type' => 4,
				'input_string' => $data['keyname'],
				'reply_type'  => $reply_type,
				'reply_rand'  => 0,
				'reply_string'=>$reply_string,
				'create_time' => time()
			);
			//存入自动回复模板
			$reply_temp = M("system:wxCms")->setReply($reply);
		}
		if($result){
			$result['menu_string'] = json_decode($result['menu_string'],true); 
			$this->output = $result;
			$this->success("更新成功");
		}else{
			$this->error("数据更新失败");
		}
	}
	
	/**
	 * 获取菜单列表	
	 * @access public 
	 * @return json
	 */
	public function getMenuList(){
		$aciton = sget('action','s');
		//分页
		$page = sget("page",'i',0); //页码
		$size = sget("pageSize",'i',20); //每页数
		//grid调用接口
		if($aciton =='grid'){
			$page = sget("pageIndex",'i',0); //页码
			$size = sget("pageSize",'i',20); //每页数	
			$sortField = sget("sortField",'s','input_time'); //排序字段
			$sortOrder = sget("sortOrder",'s','desc'); //排序
		}	
		//获取回复列表
		$result = M("system:wxMenu")->getMenuList("0,1",'',$page,$size);
		foreach($result['data'] as $k=>&$v){
			$txt_str = array();
			$v['create_time']     	  =  date("Y-m-d",$v['create_time']);
			$v['level_name'] 		  =  $v['level']==1?"一级菜单":"二级菜单";
			switch($v['type']){
				case 1 :
					$v['menu_type'] = "子菜单";
				break;
				case 2 :
					$v['menu_type'] = "链接";
				break;
				case 3 :
					$v['menu_type'] = "事件";
				break;
			}
			$v['canUse']=$v['status']==1?"是":"否";
			if(is_array(json_decode($v['menu_string'],true))){
				$mstr = json_decode($v['menu_string'],true);
				$in_str = "";
				foreach($mstr as $attr=>$val){
					if($attr=="type"&&$val==2){
						$in_str .= "类型：链接；";
					}else if($attr=="type"&&$val==3){
						$in_str .= "类型：事件；";
					}else{
						$in_str .= $attr."：".$val."；";
					}
				}
				$v['menu_string'] = $in_str;
			}
			$rlist[] = $v;
		}
		$this->output  = array('total'=>$result['count'],'data'=>$rlist);
		$this->success();
	}
		
	/**
	 * 删除菜单	
	 * @access public 
	 * @return json
	 */
	public function delMenu(){
		$param=array(
			'ids'=>array('rule'=>'*1-10','msg'=>'请输入模板id','need'=>'n'),
			'mark'=>array('rule'=>'normal1-3','msg'=>'请输入正确的菜单层级','need'=>'n'),
		);
		$data=$this->getParam($param);
		$result = M("system:wxMenu")->deleteMenu($data['ids'],$data['mark']);
		if($result){
			$this->success();
		}else{
			$this->error("删除失败");
		}
	}
	
	/**
	 * 获取当前线上菜单
	 * @access public 
	 * @return json
	 */
	public function getTrunkMenu(){
		$url = "https://api.weixin.qq.com/cgi-bin/menu/get?access_token=".$this->getToken();
		$result=$this->post($url);
		if($result){
			$this->output = $result;
			$this->success();
		}
		$this->error();
	}
	
	/**
	 * 存储一个历史模板
	 * @access public 
	 * @return json
	 */
	public function creatHistory($temp=array()){
		//格式化数组
		$menu = addslashes(json_encode($temp['menu']));
		$history = addslashes(json_encode($temp['history']));
		
		$data = array('status'=>1,'create_time'=>time(),'temp_string'=>$history,'menu_string'=>$menu);
		//存储
		$result = M("system:wxMenu")->creatHistory($data);
	}
	
	/**
	 * 获取菜单访问次数
	 * @access public 
	 * @return json
	 */
	public function getAccessTimes(){
		$param=array(
			'keyname'=>array('rule'=>'*1-255','msg'=>'keyname设置不正确','need'=>'n'),
			'daily'  =>array('rule'=>'*','msg'=>'菜单数据不能为空','need'=>'n'),
			'start'  =>array('rule'=>'*','msg'=>'菜单数据不能为空','need'=>'n'),
			'end'  =>array('rule'=>'*','msg'=>'菜单数据不能为空','need'=>'n'),
		);
		$data=$this->getParam($param);		
		//是否查询某一天
		if(!empty($data['daily'])){
			$ctime = strtotime($data['daily']."08:00:00");
			$data['start'] ="";
			$data['end']   ="";
		}else{
			$ctime = "";
		}
		
		$data = M("system:wxMenu")->getAccessTimes($data['kename'],$ctime,$data['start'],$data['end']);
		$this->output = array('data'=>$data,'count'=>count($data));
		$this->success();
	}
	
	/**
	 * 获取菜单列表
	 * @access public 
	 * @return json
	 */
	public function getAccessList(){
		$aciton = sget('action','s');
		$keyname = sget('keyname','s');
		//分页
		$page = sget("page",'i',0); //页码
		$size = sget("pageSize",'i',20); //每页数
		//grid调用接口
		if($aciton =='grid'){
			$page = sget("pageIndex",'i',0); //页码
			$size = sget("pageSize",'i',20); //每页数	
			$sortField = sget("sortField",'s','input_time'); //排序字段
			$sortOrder = sget("sortOrder",'s','desc'); //排序
		}
		$time = time();
		$today = date("Y-m-d",$time);
		$month = date("Y-m",$time);
		//统计本月访问	
		$total = M("system:wxMenu")->getMonthCount($month);
		//获取今日访问数
		$list = M("system:wxMenu")->getAccessList($keyname,$page,$size,$today);
		//将今日访问次数拿出来
		foreach($list['data'] as $item){
			$arr[$item['temp_id']] = $item['access_times'];
		}
		$final = array();
		
		foreach($total['data'] as $v){
			$v['daily'] = $today;
			$v['access_times'] = $arr[$v['temp_id']]?$arr[$v['temp_id']]:0;
			$fList[] = $v;
		}
		$this->output = array('total'=>count($list),'data'=>$fList);
		//$this->output = array('total'=>count($total),'data'=>$total['data']);
		$this->success();
	}
	// 发布菜单
	public function postMenu(){
		$platform = sget('platform','s');
		
		$menu = $this->getCurMenu();
		if($menu['msg']){
			//有子菜单未定义
			$this->error($menu['msg']);
		}
		if($platform == 'serve'){
			// 追加扫描名片
			$menuCount = count($menu['menu']['button'][2]['sub_button']);// 计算最后一个菜单总数
			if($menuCount>1){
				$picmenu = array(
					'type'=>'pic_photo_or_album', 
					'name'=>'扫描名片', 
					'key'=>'rselfmenu_1_1', 
					);
				$menu['menu']['button'][2]['sub_button'][]=$picmenu;
			}
		}
		$result = $this->setWxMenu($menu['menu'],$platform);
		if((int)$result['errcode']===0){
			//为本次菜单创建建立历史
			$this->creatHistory($menu);
			// 输出
			$this->output = $menu;
			$this->success();
		}else{
			$this->output = $menu['menu'];
			$this->error($result['errcode'].':'.$result['errmsg']);
		}
	}
	
	private function getCurMenu(){
		$result = M("system:wxMenu")->getMenuList();
		if(empty($result)||$result['count']==0){
			return array('msg'=>'未获取到菜单数据');
		}
		$menu1 = array();		
		$menu2 = array();		
		$menu3 = array();
		$history = array();		
		foreach($result['data'] as $k=>$v){
			$arr =array();
			$menu_string = json_decode($v['menu_string'],true);
			// 子菜单的type尚未设置
			if($v['type']==0&&$v['level']!=1){
				return array('msg'=>'你的菜单中有未设置的子菜单');
			}
			// 一级菜单尚未设置
			if($v['type']==0&&$v['level']==1){
				//return array('msg'=>'你的菜单中有未设置的一级菜单');
			}
			if($v['mark']==1){
				if($v['level']==2){
					//加入历史菜单
					//组建当前创建菜单需要的数组
					$arr['type'] = $menu_string['type'];
					$arr['name'] = $v['name'];
					if($v['type']==2){
						$arr['url'] = $menu_string['url'];
					}else if($v['type']==3){
						$arr['key'] = $menu_string['keyname'];
					}
					if(count($menu1['sub_button'])>=5) continue;
					$menu1['sub_button'][] = $arr;
				}else{
					$menu1['name'] = $v['name'];
					$menu1['type'] = $menu_string['type'];
					if($v['type']==2){
						$menu1['url'] = $menu_string['url'];
					}else if($v['type']==3){
						$menu1['key'] = $menu_string['keyname'];
					}else{
						if(empty($menu1['sub_button'])){
							$menu1['type'] = "click";
							$menu1['key'] = "noKey_".$v[id];
						}
					}
				}
			}else if($v['mark']==2){
				if($v['level']==1){
					$menu2['name'] = $v['name'];
					$menu2['type'] = $menu_string['type'];
					if($v['type']==2){
						$menu2['url'] = $menu_string['url'];
					}else if($v['type']==3){
						$menu2['key'] = $menu_string['keyname'];
					}else{
						if(empty($menu2['sub_button'])){
							$menu2['type'] = "click";
							$menu2['key'] = "noKey_".$v[id];
						}
					}
				}else{
					$arr['type'] = $menu_string['type'];
					$arr['name'] = $v['name'];
					if($v['type']==2){
						$arr['url'] = $menu_string['url'];
					}else if($v['type']==3){
						$arr['key'] = $menu_string['keyname'];
					}else{
						$arr['key'] = "noKey_".$v[id];
					}
					if(count($menu2['sub_button'])>=5) continue;
					
					$menu2['sub_button'][] = $arr;
				}
			}else if($v['mark']==3){
				if($v['level']==1){
					$menu3['name'] = $v['name'];
					$menu3['type'] = $menu_string['type'];
					if($v['type']==2){
						$menu3['url'] = $menu_string['url'];
					}else if($v['type']==3){
						$menu3['key'] = $menu_string['keyname'];
					}else{
						if(empty($menu3['sub_button'])){
							$menu3['type'] = "click";
							$menu3['key'] = "noKey_".$v[id];
						}
					}
				}else{
					$arr['type'] = $menu_string['type'];
					$arr['name'] = $v['name'];
					if($v['type']==2){
						$arr['url'] = $menu_string['url'];
					}else if($v['type']==3){
						$arr['key'] = $menu_string['keyname'];
					}
					if(count($menu3['sub_button'])>=5) continue;
					$menu3['sub_button'][] = $arr;
				}
			}
			$history[] = array('tmp_id'=>$v['id'],'type'=>$v['type'],'keyname'=>$v['keyname'],'name'=>$v['name']);
		}
		if(!empty($menu1)){
			$menu['button'][] = $menu1;
		}
		if(!empty($menu2)){
			$menu['button'][] = $menu2;
		}
		if(!empty($menu3)){
			$menu['button'][] = $menu3;
		}
		//$this->output = $menu;
		//$this->success();
		 return array('menu'=>$menu,'history'=>$history);
	}
	
	/**
	 * 获取本次token
	 * @access public 
	 * @param bool $force 是否强制获取
	 * @return string
	 */
	private function getToken($plate=""){
		if($plate=="sub"){
			$appid  = $this->sub_appid;
			$secret = $this->sub_secret;
		}else{
			$appid  = $this->appid;
			$secret = $this->secret;
		}
		
		$_key='wx_token';
		$cache=cache::startMemcache();
		// $token=$cache->get($_key);
		if(empty($token)){
			$url = $this->weixin_url."cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$secret;
			$result=$this->post($url);
			$token=$result['access_token'];
			$cache->set($_key,$token,3600); //加入缓存
		}
		return $token;
	}

	/**
	 * 设置菜单
	 * @access public 
	 * @param array $menu 菜单数组
	 * @return bool
	 */
	private function setWxMenu($menu=array(),$plate){
		$url = $this->weixin_url."cgi-bin/menu/create?access_token=".$this->getToken($plate);
		if(is_array($menu)){
			$menu=$this->_json($menu);
		}
		$result=$this->post($url,$menu);
		return $result;	
	}
	
	/**
	 * 检查微信返回数据
	 * @access public 
	 * @return string
	 */
	private function checkResult($res=array()) {
		$result = true;
		if(isset($res['errcode']) && (0!==(int)$res['errcode'])){
			$result = false;
			$this->error=$res['errcode'].':'.$res['errmsg'];
		}
		return $result;
	}
	/**
	 *	将数组转换为JSON字符串（兼容中文）
	 *	@param	array	$array		要转换的数组
	 */
	private function _json($data=array()){
		if(defined('JSON_UNESCAPED_UNICODE')){ //PHP5.4
			return json_encode($data,JSON_UNESCAPED_UNICODE);
		}
		$this->_arrayRecursive($data, 'urlencode', true);
		$json = json_encode($data);
		return urldecode($json);
	}
	
	private function post($url='',$data=array()){
		$result=curl_data($url,$data);
		return json_decode($result,true);
	}
	/**
	 *	使用特定function对数组中所有元素做处理
	 *	@param	string	&$array		要处理的字符串
	 *	@param	string	$function	要执行的函数
	 *	@return boolean	$apply_to_keys_also		是否也应用到key上
	 *	@access private
	 */
	private function _arrayRecursive(&$array, $function, $apply_to_keys_also = false){
		static $recursive_counter = 0;
		if (++$recursive_counter > 1000) {
			die('possible deep recursion attack');
		}
		foreach ($array as $key => $value) {
			if (is_array($value)) {
				$this->_arrayRecursive($array[$key], $function, $apply_to_keys_also);
			} else {
				$array[$key] = $function($value);
			}
	 
			if ($apply_to_keys_also && is_string($key)) {
				$new_key = $function($key);
				if ($new_key != $key) {
					$array[$new_key] = $array[$key];
					unset($array[$key]);
				}
			}
		}
		$recursive_counter--;
	}
	/**
	 * 检查弱密码
	 * @access private
	 * @param array $string 密码原文
	 * @return bool
	 */
	protected function _weakPasswd($string=''){
		if(!preg_match('/(.)\\1{6,19}/i',$string)){ //字符相同
			for($i=1;$i<strlen($string);$i++){ //升序或降序
				if(abs(ord($string[$i-1])-ord($string[$i]))!=1){
					return false;	
				}	
			}
		}
		return true;
	}
	
	/**
	 * 操作错误的方法
	 * @access protected
	 * @param string $msg 错误信息
	 * @return void
	 */
	protected function error($msg=''){
		$this->output($msg,1);
	}
	
	/**
	 * 操作成功的方法
	 * @access protected
	 * @param string $msg 返回提示信息
	 * @return void
	 */
	protected function success($msg='OK'){
		$this->output($msg,0);
	}
	
	/**
	 * 必须要校验token信息
	 * @access protected
	 */
	protected function chkToken(){
		if(empty($this->token) && empty($this->userid)){
			$this->output('Token信息不正确或已过期',99);
		}
	}

	public function __set($name,$value) { 
	}
	public function __get($name) {
	}
	/**
	 * APP输出结果
	 * @access private 
	 * @param string $msg 错误信息
	 * @param int $err 状态
	 * @return json
	 */
	public function output($msg='',$err=0){
		header('Access-Control-Allow-Origin:*');
		$result=array(
			'err'=>$err,			  
			'msg'=>$msg,			  
		);
		// $this->output['ssid']=$this->ssid; //所有输出追加ssid 
		$result=json_encode(array_merge($result,$this->output));
		$jsoncallback=sget('callback');
		if(!empty($jsoncallback)){
			header('Content-type:text/javascript; charset=utf-8');
			$result=$jsoncallback."($result)";
		}else{
			header('Content-type:application/json; charset=utf-8');
		}
		//wlog(CACHE_PATH.'log/app.log',date("Y-m-d H:i:s")." Output:\r\n".$result."\r\n\r\n");
		die($result);
	}
	
	/**
	 * 获取请求参数
	 * @access protected
	 * @param array $param 请求参数
	 * @return void
	 */
	private function getParam($param=array()){
		foreach($param as $k=>$v){
			$type = isset($v['type']) ? strtolower($v['type']) : 's'; //s-string,f-float,i-int,a-array
			$need = isset($v['need']) ? strtolower($v['need']) : 'y'; 
			$msg = isset($v['msg']) ?  $v['msg'] : "[$k]不符合规则";
			$rule = isset($v['rule']) ?  strtolower($v['rule']) : '*';
			
			$data=sget($k,$type);
			if($need=='y' && empty($data) || $data && !$this->_valid($data,$rule)){
				$this->output($msg,3);
			}
			
			$param[$k]=$data;
		}
		return $param;
	}
	/**
	 * 校验传入的参数是否符合规则
	 * @access private
	 * @param string $data 待校验的值
	 * @param string $rule 待校验的正则规则
	 * @return bool
	 */
	private function _valid(&$data='',$rule='*') {
		static $validate = array(
			'match'=>'/^(.+?)(\d+)-(\d+)$/', //可以扩展的格式n1-n2
			'normal' => '/^\d+$/', //自然数
			'integer' => '/^[-\+]?\d+$/', //正负整数
			'float' => '/^[-\+]?\d+(\.\d+)?$/', //正负浮点数
			'qq'=>'/^[1-9]\d{4,10}$/',  //QQ号码
			'zip' => '/^[1-9]\d{5}$/',
			'english' => '/^[A-Za-z]+$/', //纯英文
			'chinese' => '/^[\\x4E00-\\x9FA5\\xF900-\\xFA2D]+/', //纯中文
			'email' => '/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/', //邮箱
			'mobile'=>'/^1[3-8]\d{9}$/', //手机号
			'idcard'=>'/^(\s)*(\d{15}|\d{18}|\d{17}x)(\s)*$/i',  //身份证
			'url' => '/^http:\/\/[A-Za-z0-9]+\.[A-Za-z0-9]+[\/=\?%\-&_~`@[\]\':+!]*([^<>\"\"])*$/', //url地址
			'*' => '/^[\w\W]+$/', //所有符号
			'passwd' => '/^[\w\W]+$/', //所有符号
			's'=>'/^[\\x4E00-\\x9FA5\\xF900-\\xFA2D\w\.\s]+$/', //中英文(非空)
			'date' => '/^\d{4}[\/\-](0?[1-9]|1[012])[\/\-](0?[1-9]|[12][0-9]|3[01])$/',
			'time' => '/^([0-9]|[01][0-9]|2[1-3])(:([0-5]?[0-9])){1,2}$/',
		);

		//追加s1-n格式的正则表达
		if(!isset($validate[$rule]) && preg_match($validate['match'],$rule,$mac)){
			if(isset($validate[$mac[1]])){
				$validate[$rule]=str_replace("+$/",'',$validate[$mac[1]]).'{'.$mac[2].','.$mac[3].'}$/';
			}
		}

		//客户端密码需要解密
		if(strpos($rule,'passwd') === 0){
			switch($this->chanel){
				case 8://ios 版本号小于2不加密
					if($this->version >= 2){
						$data = desDecrypt_IOS($data);//IOS处理
					}
					break;
				case 7://android
				case 9://wp
					$data=desDecrypt($data);
			}
		}

		//校验正则
		if(isset($validate[strtolower($rule)])){
			$rule = $validate[strtolower($rule)];
			return preg_match($rule,$data)===1;
		}else{
			return false;
		}
	}
}

?>