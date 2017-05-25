<?php
/*
 * 微信自动回复
*/
class sysCmsAction extends adminBaseAction {
	protected $output=array(); //待输出参数
	protected $sys = null;
	protected $is_ajax = true;
	public function __constructor(){
	}
	
	/**
	 * 设置自动回复	
	 * @access public 
	 * @return json
	 */
	public function setReply(){
		$param=array(
         	'title'=>array('rule'=>'*1-80','msg'=>'请输入正确的规则名'),
         	'input_string'=>array('rule'=>'*','msg'=>'请输入关键字规则'),
         	'reply_string'=>array('rule'=>'*','msg'=>'请输入自动回复内容'),
			'input_type'  =>array('rule'=>'*','msg'=>'请选择输入类型'),
			'use_type'  =>array('rule'=>'*','msg'=>'2'),
			'reply_type'  =>array('rule'=>'*','msg'=>'4'),
			'create_time'  =>array('rule'=>'*','msg'=>'5','need'=>'n'),
			'expire_time'  =>array('rule'=>'*','msg'=>'6','need'=>'n'),
		);
		$data=$this->getParam($param);
		$data['reply_rand'] = sget("reply_rand","s");
		// 检查输入的文本
		if(is_array($_POST['input_string'])){
			$input_string = json_encode($_POST['input_string']);
		}else{
			$input_string = $_POST['input_string'];
		}
		$data['input_string'] = addslashes($input_string);
		
		// 检查回复的文本
		if(is_array($_POST['reply_string'])){
			$reply_string = json_encode($_POST['reply_string']);
		}else{
			$reply_string = $_POST['reply_string'];
		}
		$data['reply_string'] = addslashes($reply_string);
		
		if($data['create_time']==0){
			$data['create_time'] = time();
		}else{
			$data['create_time'] = strtotime($data['create_time']."00:00:00");
		}
		if($data['expire_time']){
			$data['expire_time'] = (strtotime($data['expire_time']."08:00:00")>0?strtotime($data['expire_time']."23:59:00"):0);
		}
		
		$result = M("system:wxCms")->setReply($data);
		
		if($result){
			$this->output = array('data'=>$result);
			$this->success("添加成功");
		}else{
			$this->error("写入失败");
		}
	}
	
	/**
	 * 编辑自动回复	
	 * @access public 
	 * @return json
	 */
	public function editReply(){
		$param=array(
			'id'=>array('rule'=>'normal1-10','msg'=>'id输入不正确'),
			'title'=>array('rule'=>'*1-80','msg'=>'请输入正确的规则名'),
         	'input_string'=>array('rule'=>'*','msg'=>'请输入关键字规则'),
         	'reply_string'=>array('rule'=>'*','msg'=>'请输入自动回复内容'),
			'input_type'  =>array('rule'=>'*','msg'=>'请选择输入类型'),
			'use_type'  =>array('rule'=>'*','msg'=>'1：欢迎语；2：自动回复','need'=>'n'),
			'reply_type'  =>array('rule'=>'*','msg'=>'请输入回复类型','need'=>'n'),
			'create_time'  =>array('rule'=>'*','msg'=>'输入创建时间','need'=>'n'),
			'expire_time'  =>array('rule'=>'*','msg'=>'输入结束时间','need'=>'n'),
		);
		$data=$this->getParam($param);
		$data['reply_rand'] = sget("reply_rand","s");
		// 检查输入的文本
		if(is_array($_POST['input_string'])){
			$input_string = json_encode($_POST['input_string']);
		}else{
			$input_string = $_POST['input_string'];
		}
		$data['input_string'] = addslashes($input_string);
		
		// 检查回复的文本
		if(is_array($_POST['reply_string'])){
			$reply_string = json_encode($_POST['reply_string']);
		}else{
			$reply_string = $_POST['reply_string'];
		}
		$data['reply_string'] = addslashes($reply_string);
		
		//有效时间比当前时间早，则取当前时间
		$cur_time = time();
		if($data['create_time']==0){
			$data['create_time'] = $cur_time;
		}else{
			$data['create_time'] = (strtotime($data['create_time']."08:00:00")<$cur_time?$cur_time:strtotime($data['create_time']."00:00:00"));
		}
		if($data['expire_time']){
			$data['expire_time'] = (strtotime($data['expire_time']."08:00:00")>0?strtotime($data['expire_time']."23:59:00"):0);
		}
		
		$result = M("system:wxCms")->setReply($data,$data['id']);
		
		if($result){
			$this->output = array('data'=>$result);
			$this->success("添加成功");
		}else{
			$this->error("写入失败");
		}
	}
	
	/**
	 * 设置回复模板	
	 * @access public 
	 * @return json
	 */
	public function setTemple(){
		$id = sget('id','i');
		$param=array(
         	'title'=>array('rule'=>'*1-80','msg'=>'请输入模板名称'),
         	'template_string'=>array('rule'=>'*','msg'=>'请输入模板内容'),
			'create_time' => array('rule'=>'*','msg'=>'1','need'=>'n'),
			'expire_time' => array('rule'=>'*','msg'=>'2','need'=>'n'),
			'template_type' => array('rule'=>'*','msg'=>'请输入模板类型'),
		);
		
		$data=$this->getParam($param);
		if($data['create_time']==0){
			$data['create_time'] = time();
		}
		// 检查回复的文本
		if(is_array($_POST['template_string'])){
			$template_string = json_encode($_POST['template_string']);
		}else{
			$template_string = $_POST['template_string'];
		}
		$data['template_string'] = addslashes($template_string);
		if(empty($id)){
			$result = M("system:wxCms")->setTemple($data);
		}else{
			$result = M("system:wxCms")->setTemple($data,$id);
		}
		if($result){
			$data['template_string'] = json_decode($template_string);
			$data['id'] = $result;
			$this->output = array('data'=>$data);
			$this->success("添加成功");
		}else{
			$this->error("写入失败");
		}
	}
	
	/**
	 * 获取模板列表	
	 * @access public 
	 * @return json
	 */
	public function getReplyList(){
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
		$result = M("system:wxCms")->getReplyList('',$page,$size);
		foreach($result['data'] as $k=>&$v){
			$orign_input = $v['input_string'];
			$txt_inputstr = array();
			if(is_array(json_decode($v['input_string'],true))){
				$inputArr = json_decode($v['input_string'],true);
				$input_strs ="";
				// 判断输入类型
				if($inputArr['txt']){
					$input_strs = $input_strs."关键字：<em class='cred'>“".$inputArr['txt']."”</em>";
				}else{
					// 正则表达式的构造
					$input_reg  = $inputArr['regexp'];
					// 数字
					if($input_reg['num']){
						$input_strs = $input_strs."数字：".($input_reg['num']=="rnd"?"随机":$input_reg['num'])."位，";
					}
					// 字母
					if($input_reg['wrd']){
						$input_strs = $input_strs."字母：".($input_reg['wrd']=="rnd"?"随机":$input_reg['wrd'])."位，";
					}
					// 文本
					if($input_reg['wrd']){
						$input_strs = $input_strs."文本：“".$input_reg['txt']."”";
					}
				}
				$input_strs = $input_strs."（包含：<em class='cred'>".($inputArr['contain']==1?"是":"否")."</em>）";
				$txt_inputstr[] = $input_strs;
			}
			$v['input_string'] = $txt_inputstr;
			$reply_str = array();
			// 回复的json字符串解析
			if(is_array(json_decode($v['reply_string'],true))){
				$v['reply_string'] = json_decode($v['reply_string'],true);
			}else{
				$replyArr = "";
				$replyArr = $v['reply_string'];
				$v['mid'] = $v['reply_string'];
				$temples = M("system:wxCms")->getTempleDetail($replyArr);
				$count = $temples['count'];
				
				foreach($temples['data'] as $key=>$tmp){
					$reply_str[] = json_decode($tmp['template_string'],true);
					// 模板类型1：文本；2：图文
					$tmp_type = $v['reply_type'];
				}
				
				// 将模板内容解析出来
				$tmp_str = $this->formateTemp($reply_str,$v['reply_type']);
				if($tmp_str){
					$v['reply_string'] = $tmp_str;
				}
			}
			
			$v['create_time']  = date("Y-m-d",$v['create_time']);
			// 失效时间修改
			if($v['expire_time']==0){
				$v['expire_time']  = "永久";
			}else{
				$v['expire_time']  = date("Y-m-d",$v['expire_time']);
			}
			if($v['input_type']==1){
				$v['input_type'] = "1：文本";
			}else if($v['input_type']==2){
				$v['input_type'] = "2：正则表达式";
			}else if($v['input_type']==3){
				$v['input_type'] = "3：图片";
				$v['input_string'] = "image";
			}else if($v['input_type']==4){
				$v['input_type'] = "4：事件";
				$v['input_string'] = array("0"=>"键值：<em class='cred'>".$orign_input."</em>");
			}
			$v['use_type']     = ($v['use_type']==1?"欢迎语":"自动回复");
			$v['reply_type']   = ($v['reply_type']==1?"文本":"图文");
			$rlist[] = $v;
		}
		$this->output  = array('total'=>$result['count'],'data'=>$rlist);
		$this->success();
	}
	
	/**
	 * 删除文本/图文模板	
	 * @access public 
	 * @return json
	 */
	public function delTemple(){
		$param=array(
         	'ids'=>array('rule'=>'*1-80','msg'=>'请输入模板id'),
		);
		$data=$this->getParam($param);
		
		$result = M("system:wxCms")->deleteTemple($data['ids']);
		if($result){
			$this->success();
		}else{
			$this->error("删除失败");
		}
	}
	
	/**
	 * 删除回复模板	
	 * @access public 
	 * @return json
	 */
	public function delReply(){
		$param=array(
         	'ids'=>array('rule'=>'*1-80','msg'=>'请输入模板id'),
		);
		$data=$this->getParam($param);
		
		$result = M("system:wxCms")->deleteReply($data['ids']);
		if($result){
			$this->success();
		}else{
			$this->error("删除失败");
		}
	}
	/**
	 * 获取文本&图文回复列表	
	 * @access public 
	 * @return json
	 */
	public function getTempleLsit(){
		$aciton = sget('action','s');
		//分页
		$page = sget("page",'i',0); //页码
		$size = sget("pageSize",'i',20); //每页数		
		if($aciton =='grid'){
			$page = sget("pageIndex",'i',0); //页码
			$size = sget("pageSize",'i',20); //每页数	
			$sortField = sget("sortField",'s','input_time'); //排序字段
			$sortOrder = sget("sortOrder",'s','desc'); //排序
		}
		$param=array(
         	'id'=>array('rule'=>'normal1-80','msg'=>'请输入模板id'),
		);
		$data = $this->getParam($param);
		if($data['id']==2){
			// 获取图文列表
			$news_list = M("system:wxCms")->getTempleList(2,$page,$size);
			foreach($news_list['data'] as $k=>&$v){
				$tmpArr = "";
				if(json_decode($v['template_string'],true)){
					$tmpArr 			  = json_decode($v['template_string'],true);
					$v['desc'] 		      = $tmpArr['desc'];
					$v['template_string'] = $tmpArr;
					$v['views'][]	  = "<span class='fl_left'><a href='".$tmpArr['link']."' class='cblue'><img src='".$tmpArr['imgurl']."' width='100px' height='50px'/><br/>".$tmpArr['title']."</a>";
				}
				$v['create_time']     =  date("Y-m-d",$v['create_time']);
				$nlist[] = $v;
				$count = $news_list['count'];
			}
			$this->output = array('total'=>$count,'data'=>$nlist);
		}else if($data['id']==1){
			$txt_list = M("system:wxCms")->getTempleList(1,$page,$size);
			foreach($txt_list['data'] as $k=>&$v){
				if(is_array(json_decode($v['template_string'],true))){
					$linkArr = json_decode($v['template_string'],true);
					$link_str  = "";
					$reply_str = $linkArr['reply'];
					
					if(count($linkArr['link'])>0){
						foreach ($linkArr['link'] as $link=>&$val){
							$link_str = $link_str."<a href='".$val['url']."' class='cblue'>".$val['wrd']."</a>";
							// 为文本替换链接
							$reply_str = str_replace($val['wrd'],"<a href='".$val['url']."' class='cblue'>".$val['wrd']."</a>",$reply_str);
						}
					}
					$v['link'][] = $link_str;
					$v['reply'][] = $reply_str;
				}
				$v['create_time']     = date("Y-m-d",$v['create_time']);
				$tlist[] = $v;
				$count = $txt_list['count'];
			}
			$this->output = array('total'=>$count,'data'=>$tlist);
		}
		$this->success();
	}
	
	/**
	 * 获取文本&图文回复详情	
	 * @access public 
	 * @return json
	 */
	public function getTempleDetail(){
		$id = sget('id','s');
		if(empty($id)){
			$this->error("模板id不能为空");
		}
		//获取回复列表
		$reply_item = M("system:wxCms")->getTempleDetail($id);
		$tmp = array();
		foreach($reply_item['data'] as $v){
			$tmpArr = "";
			$tmp['id'] = $v['id'];
			$tmp['template_type'] = $v['template_type'];
			$tmp['create_time'] = $v['create_time'];
			if($v['template_type']==2){
				if(json_decode($v['template_string'],true)){
					$tmpArr 			   = json_decode($v['template_string'],true);
					$tmp['desc'] 		   = $tmpArr['desc'];
					$tmp['template_string'] = $tmpArr;
					$tmp['views'][]	  = "<span class='fl_left'><a href='".$tmpArr['link']."' class='cblue'><img src='".$tmpArr['imgurl']."' width='100px' height='50px'/><br/>".$tmpArr['title']."</a>";
					
				}
			}else if($v['template_type']==1){
				if(is_array(json_decode($v['template_string'],true))){
					$linkArr = json_decode($v['template_string'],true);
					$link_str  = "";
					$reply_str = $linkArr['reply'];
						
					if(count($linkArr['link'])>0){
						foreach ($linkArr['link'] as $link=>&$val){
							$link_str = $link_str."<a href='".$val['url']."' class='cblue'>".$val['wrd']."</a>";
							// 为文本替换链接
							$reply_str = str_replace($val['wrd'],"<a href='".$val['url']."' class='cblue'>".$val['wrd']."</a>",$reply_str);
						}
					}
					$tmp['link'][]    = $link_str;
					$tmp['views'][]  = $reply_str;
				}
			}
			$tmp['create_time']     = date("Y-m-d",$v['create_time']);
			$list = $tmp;
		}
		if($reply_item){
			$this->output=array('data'=>$list);
			$this->success();
		}else{
			$this->error("获取模板失败");
		}
	}
	// 解析模板内容
	private function formateTemp($tmp=array(),$type=''){
		// 模板类型1：文本；2：图文
		if(empty($type)){
			return false;
		}
		
		if($type==1){
			$r_str = "";
			foreach($tmp as $k=>&$v){
				$link_str  = "";
				$reply_str = $v['reply'];
				
				if(count($v['link'])>0){
					foreach ($v['link'] as $link=>&$val){
						$link_str = $link_str."<a href='".$val['url']."' class='cblue'>".$val['wrd']."</a>";
						// 为文本替换链接
						$reply_str = str_replace($val['wrd'],"<a href='".$val['url']."' class='cblue'>".$val['wrd']."</a>",$reply_str);
					}
				}
				$v['link']  = $link_str;
				$v['reply'] = $reply_str;
				$r_str 	   .= $reply_str."<br>";
			}
			$tlist[]    = $r_str;
			return $tlist;
		}else if($type==2){
			$srt = "";
			foreach($tmp as $k=>&$v){
				$str 	 .= "<span class='f_left'><a href='".$v['link']."'><img src='".$v['imgurl']."' width='100px' height='50px'><br/>".$v['title']."</a></span>";
			}
			$nlist[] = $str;
			return $nlist;
		}
		return false;
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