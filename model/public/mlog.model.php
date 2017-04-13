<?php
/**
 * 用户日志模型 
 */
class mlogModel extends model{
	public function __construct() {
		parent::__construct(C('db_default'), '');
	}
	
	/*
	 * 渠道日志
	 * @access public
	 * @param int $chanel_id 渠道ID
	 * @param int $user_id 用户ID
     * @return bool
	*/
	public function chanel($chanel_id=0,$user_id=0,$platform=''){
	    $request='GET';//默认为GET
	    $content='';//请求内容 默认为空
	    //判断是get还是post
	    if(isset($_SERVER['REQUEST_METHOD']) && !strcasecmp($_SERVER['REQUEST_METHOD'],'GET')){
	        $request='GET';
	        $content=json_encode($_GET);
	    }
	    if(isset($_SERVER['REQUEST_METHOD']) && !strcasecmp($_SERVER['REQUEST_METHOD'],'POST')){
	        $request='POST';
	        $content=json_encode($_POST);
	    }
		$_data=array(
			'chanel_id'=>$chanel_id,
			'user_id'=>$user_id,
			'input_time'=>CORE_TIME,
			'from_url'=>isset($_SERVER['HTTP_REFERER']) ? substr($_SERVER['HTTP_REFERER'],0,160) : '',
			'ip'=>get_ip(),
			'uv_hash'=>genUV(),
			'method'=>__A__,
		    'platform'=>$platform,
		    'url'=>get_url(),
		    'broswer'=>($platform=='pc'?getBroswer():''),
		    'request'=>$request,
		    'content'=>$content,
		);
		p($_data);exit;
		if(C('DB_REDIS_STAT')){
			$sql=$this->model('log_chanel')->addSql($_data);
			return $this->push($sql);
		}
		$result=$this->model('log_chanel')->add($_data);
		return $result;
	}
	
	/*
	 * 用户活动
	 * @access public
	 * @param int $vtype 访问页面ID
	 * @param int $user_id 用户ID
	 * @param string $tag 其他说明
     * @return bool
	*/
	public function maction($vtype=0,$user_id=0,$tag=''){
	}

	/*
	 * 队列SQL语句
	 * @access public
	 * @param mix $sql sql语句
     * @return bool
	 */
	public function push($sql=''){
		return true;
	}
}
?>