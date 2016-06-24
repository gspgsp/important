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
	public function getCateList($cid,$page=0,$size=20,$sortField='input_time',$sortOrder='desc'){

		$list = $this->model('info')->select('id,title,img,cate_id,content,input_time,update_time')->where('cate_id='.$cid)
			->page($page+1,$size)
			->order("$sortField $sortOrder")
			->getPage();
		return $list;
	}

}