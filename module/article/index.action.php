<?php 
class IndexAction extends homeBaseAction{

	//
	public function init()
	{

		$p = sget('page', 'i', 1);
		$cid = sget('cid', 'i');

		$this->cate = M('system:cate')->where('pid=23')->getAll();
		$list = M('system:info')->getListByCate($cid, $p, 2);
		$this->page = pages($list['count'], $p, 2);

		$this->assign('list',$list);
		$this->display('index');
	}


	// 文章详情
	public function info()
	{
		$model = M('system:info');
		$id = sget('id', 'i', 0);
		if( $data = $model->getInfoById($id) ){
			p($data);

			$this->display('info');
		}
	}
}


 ?>