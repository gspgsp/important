<?php
class indexAction extends homeBaseAction{

	protected $sourceModel;
	public function __init()
	{
		$this->sourceModel = M('source:resourcelib');
	}

	public function init()
	{
		$this->count1 = $this->sourceModel->select('count(*)')->where("type=1")->getRow()['count(*)'];
		$this->count2 = $this->sourceModel->select('count(*)')->where("type=0")->getRow()['count(*)'];
		$this->countall = $this->count1 + $this->count2;
		$p = sget('page', 'i', 1);
		$pageSize = 10;
		$keyword = trim(sget('keyword', 's', ''));
		if($keyword){
			$sphinx = new SphinxClient;
			$sphinx->SetServer('localhost',9312);
			$sphinx->SetMatchMode(SPH_MATCH_ALL);
			$sphinx->setLimits(abs($p-1)*$pageSize ,$pageSize ,1000);
			$result = $sphinx->query('*'."$keyword".'*','sourcelib');
			$ids = array_keys($result['matches']);
			$list = $this->sourceModel->getSearch($ids);
			$this->pages = pages($result['total'], $p, $pageSize);
			$this->assign('list', $list);
		}else{
			$type = sget('type', 's', '');
			$list = $this->sourceModel->getList(abs($p-1), $pageSize, $type);
			$count = $type == '' ? $this->countall : ($type == 1 ? $this->count1 : $this->count2);
			$this->pages = pages($count, $p, $pageSize);
			$this->assign('list', $list);
		}
		$this->display('index.html');
	}


	public function release()
	{
		if($_POST)
		{
			$content = trim(sget('content', 's', ''));

		}
	}
}


