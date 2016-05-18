<?php
/**
*塑料资讯控制器
*/
class informationAction extends homeBaseAction
{

	public function __init() {
		$this->debug = false;
		$this->db=M('public:common')->model('info');
    }
    //资讯页面
    public function init(){
    	$this->display('information');
    }
    //详情页面
    public function detailInfo(){
    	$id = sget('id','i',0);
        $content = $this->db->model('info')->select('content')->where('id='.$id)->getOne();
    	$this->assign('content',$content);
    	$this->display('detailinfo');
    }
    //返回资源的内容
    public function get_items(){
    	//获取资源的类型
		$pid=sget('pid','i',0);
		$page = sget('page','i',0);
		$size = sget('size','i',10);
		$result = array();
		if($pid>0){
			$result = M('touch:infos')->getCateList($pid,$page,$size);
		}
		$this->json_output($result);
	}

}