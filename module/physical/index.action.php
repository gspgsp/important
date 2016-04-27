<?php 
class indexAction extends homeBaseAction{


	protected $physicalModel;

	public function __init()
	{
		$this->physicalModel = M('physical:physical');
	}
	public function init()
	{
		$sphinx = new SphinxClient();
		p($sphinx);
		$this->display('index.html');
	}

	// 获取模糊匹配
	public function get_search_list()
	{
		$keyword = trim(sget('keyword', 's', ''));
		if($keyword){
			$sphinx = new SphinxClient;
			$sphinx->SetServer('localhost',9312);
			$sphinx->SetMatchMode(SPH_MATCH_ALL);
			$sphinx->setLimits(0,10,10);
			$result = $sphinx->query('*'."$keyword".'*','physical');
			$lids = array_keys($result['matches']);
			$list = $this->physicalModel->get_search_list($lids);
			json_output($list);
		}
		
	}

	// 搜索页面
	public function search()
	{
		$keyword = sget('keyword', 's', '');
		if($keyword){
			$p = abs(sget('page', 'i', 1));
			$pageSize = 10;
			$sphinx = new SphinxClient;
			$sphinx->SetServer('localhost',9312);
			$sphinx->SetMatchMode(SPH_MATCH_ALL);
			$sphinx->setLimits(abs($p-1)*$pageSize	,$pageSize,1000);
			$result = $sphinx->query('*'."$keyword".'*','physical');
			$lids = array_keys($result['matches']);
			$list = $this->physicalModel->get_search_list($lids);
			$this->pages = pages($result['total'], $p, $pageSize);
			$this->assign('list',$list);
			$this->assign('keyword',str_replace('|', ' ', $keyword));
			$this->display('list.html');
		}
	}

	public function content()
	{
		$id = sget('id', 'i', 0);
		$print = sget('print', 's', '');

		if( $data = $this->physicalModel->where("lid=$id")->getRow() )
		{
			// p($data);
			$data['uses'] = htmlspecialchars_decode($data['uses']);
			$data['params'] = htmlspecialchars_decode($data['params']);
			$this->assign('data', $data);
			if($print)
			{
				$this->display('print.html');
			}else
			{
				$this->display('content.html');
			}
		}else{
			$this->forward('/physical');
		}
	}


}


 ?>