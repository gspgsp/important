<?php
class indexAction extends homeBaseAction{

	protected $sourceModel;
	public function __init()
	{
		$this->sourceModel = M('resourcelib:resourcelib');
		$this->customerContactModel=M('user:customerContact');
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
		$this->points=M('system:setting')->get('points')['points_pc'];//查询配置项PC端积分
		$p = sget('page', 'i', 1);
		$pageSize = 10;
		$keyword = trim(sget('keyword', 's', ''));
		$type=trim(sget('type','s','0'));
		$res=intval(M('system:setting')->get('points')['points_pc']['search']);//查询配置项收索积分
		if($keyword){
			if(!$this->is_search){
//				$res=intval(M('system:setting')->get('points')['pc_points']['search']);//查询配置项收索积分
				$billModel->addPoints($res,$this->user_id,10);
			}
			$sphinx = new SphinxClient;
			$sphinx->SetServer('localhost',9312);
			$sphinx->SetMatchMode(SPH_MATCH_PHRASE);   //将整个查询看作一个词组，要求按顺序完整匹配;
//			$sphinx->SetMatchMode(SPH_MATCH_BOOLEAN);
			$sphinx->SetSortMode(SPH_SORT_EXTENDED, "input_time DESC" );//收索(收索模式)结果按时间倒序
			$sphinx->setLimits(abs($p-1)*$pageSize ,$pageSize ,1000);
			$result = $sphinx->query('*'."$keyword".'*','resourcelib');//resourcelib 与配置文件 数据源想关联
			$ids = array_keys($result['matches']);
			$list = $this->sourceModel->getSearch ($ids);
			foreach ($list as &$v) {
				$v['content'] = str_replace($keyword, '<font style="color:#F00">'.$keyword.'</font>',$v['content']);
			}
			$this->pages = pages($result['total'], $p, $pageSize);
			$this->assign('list', $list);
		}else{
			$type = sget('type', 's', '');
			$list = $this->sourceModel->getList(abs($p-1), $pageSize, $type);
			$count = $type == '' ? $this->countall : ($type == 1 ? $this->count1 : $this->count2);
			$this->pages = pages($count, $p, $pageSize);
			$this->assign('list', $list);
		}
		$this->info=$this->_getInfo();
		$this->seo = array(
			'title'=>'资源库',
			'keywords'=>'塑料资源，塑料交易信息，塑料原料交易信息',
			'description'=>'我的塑料网资源库栏目运用互联网和大数据基础实时收录海量塑料原料交易信息，免费提供塑料原料交易信息查询',
			'status'=>4
			);
		$this->display('index.html');
	}

    //资源发布
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
			preg_match("/[1][0-9]{10}/", $content,$arr);
			if($arr[0]){
				$res=M('public:common')->model('customer_contact')->where("mobile = $arr[0]  and chanel=6")->getOne();
				if(!$res){
					$info=M('public:common')->model('potential_customers')->where("phone_number = $arr[0] ")->getOne();
					if(!$info){
						$res['phone_number']=$arr[0];
						M('public:common')->model('potential_customers')->add($res);
					}
				}
			}
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
			$res=intval(M('system:setting')->get('points')['points_pc']['pub']);//查询配置项资源发布积分
			if(!$is_pup){
				$billModel->addPoints($res,$this->user_id,9);
			}
			json_output(array('err'=>0,'msg'=>'发布成功'));
		}
	}


	//可能感兴趣的产品
	private function _getInfo(){
		$where="pur.type=2 and pur.shelve_type=1 and pur.status in (2)";
		$status=1;
		$limit=1;
		$info=M('product:concernedProduct')->getConcernedList($this->user_id,$status=1,$limit=1);
		foreach($info as $k=>$v){
			$arr['model']=$v['product_model'];
		}
		if($arr['product_name']){
			$where.=" and pro.model={$arr}";
		}
		return $list=M('product:purchase')->getInfos($where);

	}
}