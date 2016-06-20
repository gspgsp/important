<?php
/**
*应用首页-app
*/
class mainPageAction extends homeBaseAction
{
	public function __init() {
		$this->db=M('public:common')->model('info');
    }
    //进入首页
    public function enMainPage(){
    	$this->display('index');
    }
    //获取首页数据
    public function getMainPage(){
        $this->is_ajax = true;
        if($this->user_id<0) $this->error('账户错误');
    	$type = sget('type','i',1);//type为1:今日头条,2:调价动态
    	if($infos = M('myapp:mainPage')->getInfos($type)) $this->json_output(array('err'=>0,'infos'=>$infos));
    	$this->json_output(array('err'=>2,'msg'=>'没有相关资讯'));
    }
    //进入今日资讯
    public function enTodayInfos(){
    	$this->display('info');
    }
    //点击获取更多今日资讯
    public function getMoreInfos(){
        $this->is_ajax = true;
        if($this->user_id<0) $this->error('账户错误');
    	$pid=sget('pid','i',29);
		$page = sget('page','i',0);
		$size = sget('size','i',10);
		if(!$infos = M('touch:infos')->getCateList($pid,$page,$size)) $this->json_output(array('err'=>0,'infos'=>$infos['data']));
		$this->json_output(array('err'=>2,'msg'=>'没有更多相关资讯'));
    }
    //进入资讯详情
    public function enDetailInfos(){
    	$this->display('info_detail');
    }
    //获取资讯详情
    public function getDetailInfos(){
        $this->is_ajax = true;
        if($this->user_id<0) $this->error('账户错误');
    	$id=sget('id','i',0);
        if(!$detInf=$this->db->model('info')->where("id=$id")->getRow()) $this->json_output(array('err'=>1,'msg'=>'没有该条资讯详情'));
        $this->json_output(array('err'=>0,'detInf'=>$detInf));
    }
    //进入调价动态
    public function enDyPrice(){
    	$this->display('info_oil');
    }
    //点击获取更多调价动态
    public function getMoreDyPrice(){
        $this->is_ajax = true;
        if($this->user_id<0) $this->error('账户错误');
		$page = sget('page','i',1);
		$size = sget('size','i',10);
		if(!$list = M('myapp:mainPage')->getAllPriceFloor($page,$size)) $this->json_output(array('err'=>1,'msg'=>'没有更多调价动态'));
		$this->json_output(array('err'=>0,'dyprice'=>$list['data']));
    }
    //进入首页搜素页
    public function enMainSearch(){
    	$this->display('appSearch');
    }
    //获取历史搜索和热门搜索
    public function getHisAndHot(){
        $this->is_ajax = true;
        if($this->user_id<0) $this->error('账户错误');
    	//历史搜索结果
    	$hisData = $this->_history();
    	//热门搜索结果
    	$hotData = $this->_hotSearch();
    	$this->json_output(array('err'=>0,'hisData'=>$hisData,'hotData'=>$hotData));
    }
    //历史搜索方法
    private function _history(){
	    //判断cookie类里面是否有浏览记录
	    if($_cfrom=cookie::get('_keyWords'))
	    return unserialize($_cfrom);
    }
    //热门搜索方法
    private function _hotSearch(){
    	$_key='hotWords';
		$cache=cache::startMemcache();
		$data=$cache->get($_key);
		if(empty($data)){
			$param = array(
				'ki1'=>'HDPE',
				'ki2'=>'LDPE',
				'ki3'=>'LLDPE',
				'ki4'=>'PP',
				'ki5'=>'PVC',
				'mo1'=>'C100V',
				'mo2'=>'E2059',
				'mo3'=>'K221',
				'mo4'=>'MP7151',
				'mo5'=>'P70F',
				'mo6'=>'S3160',
				'fa1'=>'上海金菲',
				'fa2'=>'上海石化',
				'fa3'=>'中石化青岛',
				'fa4'=>'中石油广西',
				'fa5'=>'沙特APPC',
				);
			$cache->set($_key,$param,0); //加入缓存
			$data = $param;
		}
		return $data;
    }
    //进入搜索结果页(通过首页搜索)
    public function enSearchRes(){
    	//cookie保存输入框的关键字
		$_cfrom=sget('keyWords','s');//将搜索的关键字传入
		$history = array();
		array_push($history, $_cfrom);
		/* 去除重复记录 */
        $rows = array();
        foreach ($history as $v)
        {
            if(in_array($v, $rows))
            {
                continue;
            }
            $rows[] = $v;
        }

        /* 如果记录数量多余5则去除后面的元素 */
        if (count($rows) > 5)
        {
            $newRows = array_slice($rows, 0, 5);
            $keyWords = serialize($newRows);
            cookie::set('_keyWords',$keyWords,time()+1800);
        }
    	//
    	$this->display('appSearchResult');
    }
    //获取搜索结果数据（通过首页搜索），包括：搜索框，历史搜索，热门搜索
    public function getSearchRes(){
        $this->is_ajax = true;
        if($this->user_id<0) $this->error('账户错误');
    	//搜素关键字
        $keywords = sget('keywords','s');
    	$page = sget('page','i','1');
		$size = sget('size','i',8);
        if(!$searchRes = M('myapp:mainPage')->getAllSearchRes($keywords,$page,$size)) $this->json_output(array('err'=>2,'msg'=>'没有相关结果'));
        $this->json_output(array('err'=>0,'searData'=>$searchRes));
    }
    //结果数据按价格排序(1从低到高,2从高到低，3默认时间)
    public function getResByPrice(){
        $this->is_ajax = true;
        if($this->user_id<0) $this->error('账户错误');
        $stype = sget('stype','i',1);
        $page = sget('page','i','1');
        $size = sget('size','i',8);
        if(!$sdata=M('myapp:mainPage')->getSortedData($stype,$page,$size)) $this->json_output(array('err'=>2,'msg'=>'排序查找失败'));
        $this->json_output(array('err'=>0,'sdata'=>$sdata));
    }

}