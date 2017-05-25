<?php
/**
 * Created by PhpStorm.
 * User: yuanjiaye
 * Date: 2016/7/8
 * Time: 14:13
 */

class financeCalcAction extends  homeBaseAction{
	protected $db;
	public function __init()
	{
		$this->db=M('public:common');
	}

	public function init(){
	    if($this->user_id<=0) $this->forward('/user/login');
        $row = $_SESSION[$this->user_id.'_trycalc'];
        if(empty($row)){
            $this->row=null;
            $this->finance_type = 1;
        }else{
            $arr = (Array)json_decode($row);
//             $arr['total']=round($arr['amount']*$arr['price'],2);
            $this->row= $arr;
            $this->finance_type = $arr['finance_type'];//1代采 2白条 3仓单融资
            $sid=$GLOBALS['CORE_SESS']->getSid();
            $_SESSION[$sid.'_trycalc_'.$arr['finance_type']]="";
        }
		$this->seo = array('title'=>'塑料金融',);
		$this->display('financeCalc');
	}
	
	//获取品种类型和厂家
	//写入session
	public function SetSession(){
	    if($_POST){
	        $data=spost('data','s','');
	        $data = str_replace("\\", "", $data);
	        $_SESSION[$this->user_id.'_trycalc']=$data;
	        $this->success('操作成功');
	    }
	}
	
	//提交数据到后台金融申请表里
	public function Apply(){
	    if($_POST){
	        $data=spost('data','s','');
	        $data = str_replace("\\", "", $data);
	        $this->db->startTrans();
	        if(empty($data)){
	            $this->finance_type = 1;
	        }else{
	            $arr = (Array)json_decode($data);
	            foreach ($arr as $key=>$value){
	                $this->finance_type = $value->finance_type;
	                break;
	            };
	            //通过类型获取finance_product主键id
	            $product_id = $this->db->model('finance_product')->where('product_id='.$this->finance_type)->select('id')->getOne();
	            $_apply=array(
	                'product_id'=>$product_id,
	                'c_id'=>$_SESSION['uinfo']['c_id'],
	                'create_date'=>date("Y-m-d H:i:s"),
	                'contact_id'=>$this->user_id,//交易员
	                'create_user'=>$this->user_id,
	                'info'=>$data,
	                'status'=>'1',
	            );
	            $this->db->model('finance_apply')->add($_apply);
	        }
	        if($this->db->commit()){
	            $this->success('提交成功');
	        }else{
	            $this->db->rollback();
	            $this->error("提交失败");
	        }
	    }
	}
	
	//删除相关session数据
	public function DelSession(){
        $_SESSION[$this->user_id.'_trycalc']="";
        $this->success('操作成功');
	}
}