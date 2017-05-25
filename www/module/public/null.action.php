<?php
class nullAction extends action{
    /**
     * 初始化控制器
     * @access public
    */
	public function __construct(){
		parent::__construct();
		$this->title='错误的页面';

		//是否需要HTTPs跳转
		if(C('HTTPS_ON')){
			if(!isset($_SERVER['HTTPS'])){ //所有都不必须要跳、或指定模块跳转
				$modules=explode(',',C('HTTPS_MODULE'));
				if(empty($modules[0]) || in_array(ROUTE_M,$modules) || __A__=='/home/index/init'){
					$this->forward(str_replace('http:','https:',APP_URL).__SELF__);
				}
			}else{ //替换静态文件https
				C('TEMP_REPLACE.__JS__',str_replace('http:','https:',C('TEMP_REPLACE.__JS__')));
				C('TEMP_REPLACE.__IMG__',str_replace('http:','https:',C('TEMP_REPLACE.__IMG__')));
				C('TEMP_REPLACE.__CSS__',str_replace('http:','https:',C('TEMP_REPLACE.__CSS__')));
				C('TEMP_REPLACE.__UPLOAD__',str_replace('http:','https:',C('TEMP_REPLACE.__UPLOAD__')));
			}
		}
	
		//系统信息:赋值模板
		$this->sys=M('system:setting')->getSetting();

		$this->error('您访问的页面不存在','/');
		exit;
	}
}

?>
