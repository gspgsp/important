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
}


