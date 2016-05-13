<?php
/**
*资讯模型
*/
class infosModel extends model
{
	public function __construct() {
		parent::__construct(C('db_default'), 'info');
	}
	//获取对应的文章
	public function getCateList($cid,$page=1,$size=20,$sortField='input_time',$sortOrder='desc'){
		$_key='article_list'.$cid;
		$cache=cache::startMemcache();
		$data=$cache->get($_key);
		if(empty($data)){
			$list = $this->model('info')->select('id,title,img,cate_id,content,description,input_time,update_time')->where('cate_id='.$cid)
			->page($page+1,$size)
			->order("$sortField $sortOrder")
			->getPage();
		$cache->set($_key,$list,86400);//加入缓存
		}
		return $data;
	}
}