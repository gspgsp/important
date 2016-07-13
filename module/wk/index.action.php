<?php

class indexAction extends adminBaseAction{

	protected $model,$uname,$today,$db,$adminid;
	public function __init(){
		$this->adminid=$_SESSION['adminid'];
		$this->uname=$_SESSION['username'];
		$this->db=M("public:common");
		$this->model=M('wk:share');
		$this->today=strtotime(date('Y-m-d',time()));

	}
	public function init()
	{
		//最近发布时间
		$times=$this->model->where("is_stock=0 and status='上架'")->order("input_time desc")->select('input_time')->getOne();
		$this->assign('time', $times);

		// 库存信息
		$stockList=$this->model->where("is_stock=1")->order("input_time desc")->getAll();
		$this->assign('stockList',$stockList);
		$this->display('index');
	}

	//发布
	public function addInfo()
	{
		if($_POST){

			$id=sget('iid','i',0);
			$content=trim($_POST['content']);

			if($content==''){
				$_data=$_POST;
				$_data['ship_type']=isset($_POST['ship_type'])?'自提':'配送';
				$_data['true_price']=isset($_POST['true_price'])?'实价':'可议价';
				$_data['stock']=isset($_POST['stock'])?'期货':'现货';
			}else{
				$_data['content']=$content;
			}
			$_data['type']=isset($_POST['type'])?'求购':'供应';
			$_data['input_time']=time();
			$_data['date']=date('m-d H:i', time());
			$_data['uid']=$this->adminid;
			$_data['uname']=$this->uname;
			$_data['status']='上架';

			//规范牌号厂家输入，拦截基础数据库中不存在的牌号厂家
			$grade=$_data['grade'];
			$factory=$_data['factory'];
			if(!M('product:product')->where("model='{$grade}'")->select('model')->getOne()) $this->error('添加失败，基础数据库中不存在此牌号');
			if(!M('product:factory')->where("f_name='{$factory}'")->select('f_name')->getOne()) $this->error('添加失败，基础数据库中不存在此厂家');

			if($id){
				$this->model->where("id=$id")->update($_data);
			}else{
				if(!$this->model->add($_data)) exit(json_encode(array('err'=>1,'msg'=>'系统错误，发布失败。code:101')));
			}
			exit(json_encode(array('err'=>0,'data'=>$_data)));
		}
	}



	public function loadMore()
	{

		$type=sget('type');
		$p=sget('p','i',1);
		$size=200;
		if($type=='') return;
		$list=$this->model->where("type='{$type}' and is_stock=0 and status='上架' and input_time>$this->today")->page($p,$size)->order('input_time desc')->getPage();
		
		if($list['data']){
			foreach ($list['data'] as $key => $value) {
				$list['data'][$key]['date']=date('m-d H:i',$value['input_time']);
			}
		}else{
			$list['data']=array();
		}
		exit(json_encode(array('list'=>$list['data'],'p'=>$p+1)));
	}

	// 置顶定时加载
	public function topTimer()
	{
		$list=$this->model->where("is_top=1 and status='上架' and type='供应' and input_time>$this->today")->order("input_time desc")->getAll();
		if($list){
			foreach ($list as $key => $value) {
				$list[$key]['date']=date('m-d H:i',$value['input_time']);
			}
		}else{
			$list=array();
		}
		exit(json_encode(array('list'=>$list)));
	}

	//加载库存
	public function loadstock(){
		$list=$this->model->where("is_stock=1")->order("input_time desc")->getAll();
		if($list){
			foreach ($list as $key => $value) {
				$list[$key]['date']=date('m-d H:i',$value['input_time']);
			}
		}else{
			$list=array();
		}
		exit(json_encode(array('list'=>$list)));
	}

	//加载我的报价
	public function myload()
	{
		$p=sget('p','i',1);
		$size=200;

		$list=$this->model->where("is_stock=0 and uname='{$this->uname}'")->order('input_time desc')->page($p,$size)->getAll();
		if($list){
			foreach ($list as $key => $value) {
				$list[$key]['date']=date('m-d H:i',$value['input_time']);
			}
		}else{
			$list=array();
		}
		exit(json_encode(array('list'=>$list,'p'=>$p+1)));
	}

	// 双击获取相同牌号报价
	public function get_grade_list()
	{
		$grade=sget('grade','s','');
		$type=sget('type','s','');
		$list=$this->model->where("is_stock=0 and status='上架' and grade='{$grade}' and type='{$type}' and input_time>{$this->today}")->order("price asc")->getAll();
		
		if($list){
			foreach ($list as $key => $value) {
				$list[$key]['date']=date('m-d H:i',$value['input_time']);
			}
		}else{
			$list=array();
		}
		exit(json_encode($list));
	}
	// 上下架
	public function changeStatus()
	{
		$id=sget('id','i',0);
		if(!$data=$this->model->getPk($id)) return;
		$status=$data['status']=='上架'?'下架':'上架';
		$this->model->where("id=$id")->update(array('status'=>$status));
		echo 1;exit;
	}
	// 置顶
	public function chengeTop()
	{
		$id=sget('id','i',0);
		if(!$data=$this->model->getPk($id)) return;
		$is_top=$data['is_top']==0?1:0;
		$this->model->where("id=$id")->update(array('is_top'=>$is_top));
		echo 1;exit;
	}

	// 获取我的发布信息编辑
	public function get_info()
	{
		$id=sget('id','i',0);
		if(!$data=$this->model->getPk($id)) return;
		$data['input_time']=date('m-d H:i',$data['input_time']);
		exit(json_encode($data));
	}

	// 重新发布
	public function repup2()
	{
		$data=$_POST['data'];
		foreach ($data as $key => $value) {
			$value['input_time']=time();
			$this->model->where("id=$key")->update($value);
		}
		echo 1;exit;
	}

	// 报价上传
	public function offerUpload()
	{

		$this->is_ajax=true;
		$url=sget('url','s','');//上传文件名
		$savePath=C('upload_local.path');//文件保存路径
		$path=$savePath.$url;//文件路径
		if(!is_file($path)) $this->error('文件不存在');
		$excelData = $this->read($path);

		foreach ($excelData as $key => $value) {
			$_data['type']=$value['类型'];
			$_data['grade']=$value['牌号'];
			$_data['factory']=$value['厂家'];
			$_data['num']=$value['数量'];
			$_data['price']=$value['价格'];
			$_data['true_price']=$value['实价'];
			$_data['store']=$value['仓库'];
			$_data['stock']=$value['期货'];
			$_data['remark']=$value['备注'];
			$_data['supplier']=$value['供应商'];
			$_data['uname']=$this->uname;
			$_data['uid']=$this->adminid;
			$_data['input_time']=time();
			$_data['is_stock']=0;
			$this->model->add($_data);
		}
		$this->success('导入成功');
	}

	//库存导入
	public function stockUpload()
	{

		$this->is_ajax=true;
		$url=sget('url','s','');//上传文件名
		$savePath=C('upload_local.path');//文件保存路径
		$path=$savePath.$url;//文件路径
		if(!is_file($path)) $this->error('文件不存在');
		$excelData = $this->read($path);

		$this->model->where("is_stock=1")->delete();
		foreach ($excelData as $key => $value) {
			$_data['pay']=$value['付款'];
			$_data['grade']=$value['牌号'];
			$_data['factory']=$value['厂家'];
			$_data['num']=$value['吨数'];
			$_data['cost']=$value['成本'];
			$_data['price']=$value['售价'];
			$_data['true_price']='实价';
			$_data['ship_type']='自提';
			$_data['store']=$value['仓库'];
			$_data['stock']=$value['现货/期货'];
			$_data['remark']=$value['备注'];
			$_data['uname']=$value['业务员'];
			$_data['input_time']=time();
			$_data['is_stock']=1;
			$this->model->add($_data);
		}
		$this->success('导入成功');

	}

	//excel 导入 --lixian
	protected function read($filename,$encode='utf-8'){
		E('PHPExcel',APP_LIB.'extend');//引入phpexcel类

		$ext = substr(strrchr($filename, '.'), 1);
		if($ext == 'xls'){
			//xls
		$objReader = PHPExcel_IOFactory::createReader('Excel5');
		}else{
			//xlsx
			$objReader = PHPExcel_IOFactory::createReader('Excel2007');
		}
		$objReader->setReadDataOnly(true);
		$objPHPExcel = $objReader->load($filename);
		$objWorksheet = $objPHPExcel->getActiveSheet();
		$highestRow = $objWorksheet->getHighestRow();
		$highestColumn = $objWorksheet->getHighestColumn();
		$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
		$excelData = array();
		for ($row = 1; $row <= $highestRow; $row++) {
			for ($col = 0; $col < $highestColumnIndex; $col++) {
				$excelData[$row][] =(string)$objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
			}
		}
		$field = array_shift($excelData);
		foreach ($excelData as $key => $value) {
			$excelData[$key] = array_combine($field, $value);
		}
		return $excelData;
	}


	// 报价单导出
	public function excelOutput()
	{
		$start=sget('start','s','');
		$end=sget('end','s','');

		if($start){
			$start=strtotime($start);
		}else{
			$start=strtotime(date('Y-m-d',time()));
		}
		if($end){
			$end=strtotime($end)+60*60*24;
		}else{
			$end=$start+60*60*24;
		}

		$list=$this->model->where("input_time between $start and $end and is_stock=0")->order("input_time asc,uname desc")->select("type,grade,factory,num,price,store,ship_type,true_price,uname,stock,FROM_UNIXTIME(input_time,'%Y/%m/%d') as time")->getAll();
		$excle=array('类型','牌号','厂家','数量','价格','仓库','配送','是否实价','发布者','期/现','发布时间');
		$this->exportexcel($list,$excle,"offers-".date("md",$start)."-".date("md",$end-60*60*24));
	}


	/**
	    * 导出数据为excel表格
	    *@param $data    一个二维数组,结构如同从数据库查出来的数组
	    *@param $title   excel的第一行标题,一个数组,如果为空则没有标题
	    *@param $filename 下载的文件名
	    *@examlpe 
	    *$stu = M ('User');
	    *$arr = $stu -> select();
	    *exportexcel($arr,array('id','账户','密码','昵称'),'文件名!');
	*/
	protected function exportexcel($data=array(),$title=array(),$filename='report')
	{
	    header("Content-type:application/octet-stream");
	    header("Accept-Ranges:bytes");
	    header("Content-type:application/vnd.ms-excel");  
	    header("Content-Disposition:attachment;filename=".$filename.".xls");
	    header("Pragma: no-cache");
	    header("Expires: 0");
	    //导出xls 开始
	    if (!empty($title)){
	        foreach ($title as $k => $v) {
	            $title[$k]=iconv("UTF-8", "GB2312",$v);
	        }
	        $title= implode("\t", $title);
	        echo "$title\n";
	    }
	    if (!empty($data)){
	        foreach($data as $key=>$val){
	            foreach ($val as $ck => $cv) {
	                $data[$key][$ck]=iconv("UTF-8", "GB2312", $cv);
	            }
	            $data[$key]=implode("\t", $data[$key]);
	            
	        }
	        echo implode("\n",$data);
	    }
	}

	// 牌号搜索
	public function searchs()
	{
		$where=1;
		$timeline=sget('timeline','i','0');
		$keyword=sget('keyword','s','');

		$p=sget('p','i',1);
		$size=1;
		if($keyword==''||!$timeline) return;

		$where.=" and is_stock=0 and status='上架' and grade like '%{$keyword}%'";
		$timeline=$timeline==1?null:($timeline==2?'供应':'求购');
		if($timeline){
			$where.=" and type='{$timeline}'";
		}
		$list=$this->model->where($where)->page($p,$size)->order("input_time desc")->getPage();
		if($list['data']){
			foreach ($list['data'] as $key => $value) {
				$list['data'][$key]['date']=date('m-d H:i',$value['input_time']);
			}
		}else{
			$list=array();
		}
		$allpage=ceil($list['count']/$size);
		exit(json_encode(array('list'=>$list['data'],'count'=>$allpage,'p'=>$p)));
	}


}