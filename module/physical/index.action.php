<?php 
class indexAction extends homeBaseAction{


	protected $physicalModel;

	public function __init()
	{
		$this->physicalModel = M('physical:physical');
	}
	public function init()
	{
		$this->seo = array(
			'title'=>'物性表',
			'keywords'=>'塑料物性，塑料牌号，塑料原料物性表，塑料牌号物性表',
			'description'=>'我的塑料网物性表提供近90000种塑料原料物性查询。物性表参数包含塑料融指、硬度、熔体流动速率、塑料原料规格用途等。可直接在线搜索预览或打印',
			'status'=>7
			);
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
		$this->seo = array('title'=>$keyword.'物理特性详情表-物性表',);
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
			$data['uses'] = htmlspecialchars_decode($data['uses']);
			$data['params'] = htmlspecialchars_decode($data['params']);
			$this->assign('data', $data);
			if($print)
			{
				$this->seo = array('title'=>$data['type'].'/'.$data['name'].'物性表打印-物性表',);
				$this->display('print.html');
			}else
			{	
				$this->seo = array('title'=>$data['type'].'/'.$data['name'].'物性指标详情-物性表',);
				$this->display('content.html');
			}
		}else{
			$this->forward('/physical');
		}
	}


}


 ?>