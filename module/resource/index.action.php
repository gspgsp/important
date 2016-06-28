<?php
class indexAction extends homeBaseAction{

	protected $sourceModel;
	public function __init()
	{
		$this->sourceModel = M('resourcelib:resourcelib');
	}

	public function init()
	{
		//用户信息
		if($userInfo=M('user:customerContact')->getUserInfoByid($this->user_id))
		{
		    $this->assign('userInfo',$userInfo);
		}
		//积分任务完成状态
		$billModel=M('points:pointsBill');
		$today=strtotime(date('Y-m-d',time()));
		$this->is_sign=$billModel->where("uid={$this->user_id} and type=1 and addtime>{$today}")->getRow();
		$this->is_pup=$billModel->where("uid={$this->user_id} and type=9 and addtime>{$today}")->getRow();
		$this->is_search=$billModel->where("uid={$this->user_id} and type=10 and addtime>{$today}")->getRow();

		$this->count1 = $this->sourceModel->select('count(*)')->where("type=1")->getRow()['count(*)'];
		$this->count2 = $this->sourceModel->select('count(*)')->where("type=0")->getRow()['count(*)'];
		$this->countall = $this->count1 + $this->count2;
		$p = sget('page', 'i', 1);
		$pageSize = 10;
		$keyword = trim(sget('keyword', 's', ''));
		if($keyword){
			if(!$this->is_search){
				$billModel->addPoints(50,$this->user_id,10);
			}
			$sphinx = new SphinxClient;
			$sphinx->SetServer('localhost',9312);
			$sphinx->SetMatchMode(SPH_MATCH_PHRASE);
			$sphinx->setLimits(abs($p-1)*$pageSize ,$pageSize ,1000);
			$result = $sphinx->query($keyword,'resourcelib');
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
			$this->is_ajax=true;
			if($this->user_id <= 0) json_output(array('err'=>1,'msg'=>'未登录'));
			$content = trim(sget('content', 's', ''));
			$type=sget('type','i',0);
			if($content=='') json_output(array('err'=>2,'msg'=>'请输入要发布的内容'));
			$uinfo=$_SESSION['uinfo'];
			$_data=array(
				'uid'=>$this->user_id,
				'type'=>$type,
				'content'=>$content,
				'input_time'=>CORE_TIME,
				'realname'=>$uinfo['name'],
				'user_qq'=>$uinfo['qq'],
				);
			$this->sourceModel->add($_data);
			$billModel=M('points:pointsBill');
			$today=strtotime(date('Y-m-d',time()));
			$is_pup=$billModel->where("uid={$this->user_id} and type=9 and addtime>$today")->getRow();
			if(!$is_pup){
				$billModel->addPoints(100,$this->user_id,9);
			}
			json_output(array('err'=>0,'msg'=>'发布成功'));
		}
	}
}


