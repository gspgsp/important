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
        $this->display('detailInfo');
    }

    public function getArticleInfo(){
        $id=sget('id','i',0);
        $data=$this->db->model('info')->where("id=$id")->getRow();
        $this->json_output($data);

    }
    //返回资源的内容
    public function get_items(){
    	//获取资源的类型
		$pid=sget('pid','i',0);
		$page = sget('page','i',0);
		$size = sget('size','i',10);
        $articles = array();
        $tempArr = array();
		if($pid>0){
			$result = M('touch:infos')->getCateList($pid,$page,$size);
		}
        foreach ($result['data'] as $key => $value) {
            $result['data'][$key]['content']=strip_tags($result['data'][$key]['content']);
            $result['data'][$key]['brief']=mb_substr($result['data'][$key]['content'],0,20,'utf-8')."...";
            $tempArr['id']=$result['data'][$key]['id'];
            $tempArr['title']=$result['data'][$key]['title'];
            $tempArr['brief']=$result['data'][$key]['brief'];
            $tempArr['input_time']=$result['data'][$key]['input_time'];
            $articles[]=$tempArr;
            unset($tempArr);
        }
		//$this->json_output($result);
        $this->json_output(array('err'=>0,'result'=>$articles));
	}

}