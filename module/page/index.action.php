<?php 
class indexAction extends homeBaseAction{

	protected $db;
	public function __init(){
		$this->db=M('public:common');
	}

	public function init()
	{
		$model=M('system:info');
		//帮助中心
		$cate=M('system:cate')->getCateBySpell('help');

		foreach ($cate as $key => $value) {
			$cate[$key]['son']=$model->getListByCate($value['cate_id']);
		}
		if(!$id=sget('id','i',0)){
			$id=$cate[0]['son'][0]['id'];
		}


		$this->data=sstripslashes($model->getInfoById($id));
		$this->assign('cate',$cate);
		$this->assign('id',$id);
		$this->display('index.html');
	}

	public function about()
	{
		$this->display('about.html');
	}

	public function mdownload(){

		$this->display('mdownload');
	}

	//意见反馈
	public function feedBack(){
		//var_dump($_SESSION);EXIT;
		$cid=17;
		$this->assign('id',$cid);
		$this->display('feedback.html');
	}
	//添加意见
	public function addAdvise(){
		if($_SESSION['userid']==0){
			$this->forward('/user/login');
			exit;
		}
		$type=spost('type','i');
		$message=htmlspecialchars(spost('advise','s'));
		$advise=array(
			'user_id'=>isset($_SESSION['userid'])?(int)$_SESSION['userid']:'',
			'user_name'=>isset($_SESSION['uinfo']['name'])?$_SESSION['uinfo']['name']:'',
			'msg_type'=>isset($type)?$type:'',
			'message'=>isset($message)?$message:'',
			'email'	=>isset($_SESSION['uinfo']['email'])?$_SESSION['uinfo']['email']:'',
			'mobile'=>isset($_SESSION['uinfo']['mobile'])?$_SESSION['uinfo']['mobile']:'',
			'input_time'=>time(),
		);
		try{
			if(!$this->db->model('customer_message')->add($advise)) throw new Exception("留言失败...");
		}catch(Exception $e){
			$this->error($e->getMessage());
			exit;
		}
		$this->success('留言成功！');

	}
}


