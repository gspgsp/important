<?php
/**
 * 信息管理 
 */
class infoModel extends model{
	private $cache=NULL; //缓存
	public function __construct() {
		parent::__construct(C('db_default'), 'info');
	}
	
	public function getListByCate($cate_id,$page=1,$page_size=0){
		$this->where('cate_id in ('.implode(',',(array)$cate_id).') and status=1')->order('sort_order desc,input_time desc');
		if($page && $page_size)
			return $this->page($page,$page_size)->getPage();
		elseif($page_size)
			$this->limit($page_size);
		return $this->getAll();
	}

	public function getListByCateSpell($cate_spell,$page,$page_size){
		$cate_id = M('system:cate')->getfieldbyspell($cate_spell,'cate_id');
		if($cate_id) return $this->getListByCate($cate_id, $page, $page_size);
		return array();
	}

	public function getListByParentCateSpell($parent_cate_spell,$page,$page_size){
		$pid = M('system:cate')->getfieldbyspell($parent_cate_spell,'cate_id');
		if($pid){
			$cate_ids = M('system:cate')->select('cate_id')->where('pid='.$pid)->getCol();
			$cate_ids[] = $pid;
			return $this->getListByCate($cate_ids, $page, $page_size);
		}
		return array();
	}


	public function getInfoById($id)
	{
		return $this->where("id=$id")->getRow();
	}


	/**
	 * 获取首页新闻 存入cache
	 */
	public function get_index_article()
	{
		$cache=cache::startMemcache();
		$keys = 'article_index';
		if( $articleList = $cache->get($keys) ) return $articleList;
		$temp = M('system:cate')->where("pid=23")->select('cate_id,cate_name')->getAll();
		foreach ($temp as $key => $value) {
			$articleList[$key+1]=$value;
			$articleList[$key+1]['list'] = $this->where("cate_id={$value['cate_id']}")->order('input_time desc')->select('id, cate_id, title, input_time')->limit(10)->getAll();
			$articleList[$key+1]['list'] = array_chunk($articleList[$key+1]['list'], 5);
		}
		$cache->set($keys, $articleList);
		return $articleList;
	}

	//底部文章
	public function getFooterCate(){
		$cate=M('system:cate')->getCateBySpell('help');
		array_pop($cate);
		foreach ($cate as $key => $value) {
			$cate[$key]['son']=$this->getListByCate($value['cate_id']);
		}
		return $cate;
	}
    //底部关于我们
	public function aboutUs(){
        $cate=M('system:cate')->getCateBySpell('help');
        $cate=array_pop($cate);
		$cate['son']=$this->getListByCate($cate['cate_id']);
        return $cate;
	}

}
?>
