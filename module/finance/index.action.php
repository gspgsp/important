<?php
/**
 * Created by PhpStorm.
 * User: yuanjiaye
 * Date: 2016/7/8
 * Time: 14:13
 */

class indexAction extends  homeBaseAction{
	protected $db;
	public function __init()
	{
		$this->db=M('public:common');
	}

	public function init(){
	    $this->product_type=L('product_type');//产品类型
	    $this->stores=$this->db->model('store')->select("id,store_name")->getAll();//获取当前所有仓库信息
		$this->seo = array(
			'title'=>'塑料金融',
			'keywords'=>'众塑宝，塑料金融，塑料融资，供应链金融，我的塑料网金融，我的塑料网供应链金融',
			'description'=>'众塑宝基于我的塑料网客户信用体系为您提供仓单融资，订单融资服务。塑料交易先提货，再付款，轻松做买卖',
			'status'=>6
			);
		$this->display('finance');
	}
	//模糊匹配查询牌号
	public function getmodel(){
	    if($_GET){
	        $keyword=sget('keyword','s','');
	        $model=$this->db->model('product');
	        $list=$model->where("model like '$keyword%'")->select('model')->group('model')->limit('10')->getAll();
	        json_output($list);
	    }
	}
	
	//获取品种类型和厂家
	public function getfactory(){
	    if($_POST){
	        $keyword=spost('model','s','');
	        $row=$this->db->model('product')->where("model = '$keyword'")->select('product_type,f_id')->getRow();
	        $product_type_name=L('product_type')[$row['product_type']];
	        $factory=$this->db->from('factory aa')
	        ->leftjoin('product bb','aa.fid=bb.f_id')
	        ->select('aa.fid,aa.f_name')
	        ->where("bb.model='{$keyword}' AND aa.status=1")
	        ->getAll();     
	        json_output(array('err'=>0,'product_type'=>array('name'=>$product_type_name,'value'=>$row['product_type']),'factory'=>$factory));
	    }
	}
	
	//缓存当前提交录入页面信息
	public function SetSession(){
	    $sid=$GLOBALS['CORE_SESS']->getSid();
	    if($_POST){
	        $data=spost('data','s','');
	        $data = str_replace("\\", "", $data);
	        $_SESSION[$sid.'_trycalc']=$data;
// 	        p($_SESSION[$sid.'_trycalc']);
// 	        die;
	        if(empty($data)){
	            $this->error('操作失败');
	        }else{
	            $arr = (Array)json_decode($data);
	            //1代采 2白条 3仓单融资
	            $_SESSION[$sid.'_trycalc_'.$arr['finance_type']]=$data;
	        }
	        $this->success('操作成功');
	    }
	}
}