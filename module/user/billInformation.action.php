<?php
/**
*财务中心-开票资料
*/
class billInformationAction extends userBaseAction
{
	private $cus_ct;
	public function __init(){
		$this->db = M('public:common')->model('customer_billing');
		$this->cus_ct = M('user:customerContact')->getListByUserid($this->user_id);
	}
	//开票详情页
	public function billInfo(){
		$this->is_ajax = true;
		if($this->user_id<0) $this->error('账户错误');
//		$where = " cbil.c_id={$this->$_SESSION['union']['c_id']} and cbil.display_status=1";
		$data = $this->db->from('customer as c')
		->join('customer_billing as cb','c.c_id=cb.c_id')
		->where("user_id=$this->user_id  and cb.display_status=1")
		->select("cb.id,c.c_name,c.legal_person,cb.tax_id,cb.invoice_address,cb.invoice_tel,cb.invoice_bank, invoice_account,cb.status")
		->getRow();
		$data['invoice_account']= str_pad(substr($data['invoice_account'],0,10), strlen($data['invoice_account']), '*');
//		$data['invoice_account'] =desDecrypt($data['invoice_account']);//解密

		$this->assign('detail',$data);
		$this->display('billInfo');
	}
	//修改开票信息
	public function changeBill(){
		$this->is_ajax = true;
		$data = array();
		if($_POST){
			$id = $_POST['id'];
			$status=$this->db->model('customer_billing')->select('status')->where('id='.$id)->getOne();
			if($status==1) $this->json_output(array('err'=>1,'msg'=>'系统错误'));

			$data['tax_id'] = $_POST['tax_id'];                      //识别号码
			$data['invoice_address'] = $_POST['invoice_address'];    //开票地址
			$data['invoice_tel'] = $_POST['invoice_tel'];            //开票电话
			$data['invoice_bank'] = $_POST['invoice_bank'];          //开户银行
			$data['invoice_account'] = ($_POST['invoice_account']);  //银行账号
//			$data['invoice_account'] = desEncrypt($_POST['invoice_account']);//加密
			$data['status']=0;                                       //状态
			if($this->db->where('id='.$id)->update($data)) $this->json_output(array('err'=>0,'msg'=>'更新成功,等待审核'));
		}
	}
	//删除某个开票
	// public function deleteOneBill(){
	// 	$bId = sget('bId','i');
	// 	if($this->db->where('id='.$bId)->delete())
	// 		$this->json_output(array('err'=>0,'msg'=>'删除成功'));
	// }
	//是否设为默认
	// public function IsSetDefault(){
	// 	$bSt = sget('bSt','i');
	// 	$data = $this->db->select('invoice_default')->where('id='.$bSt)->getRow();
	// 	$all = $this->db->select('id,invoice_default')->getAll();
	// 	foreach ($all as $key => $value) {
	// 		if($value['invoice_default'] == 1)
	// 			$this->db->where('id='.$bSt)->update(array('invoice_default'=>1));
	// 	}
	// 	if($data == 2){
	// 		$this->db->where('id='.$bSt)->update(array('invoice_default'=>1));
	// 	}
	// }
	//新增开票
	// public function addNewBill(){
	// 	$this->is_ajax = true;
	// 	$tt = sget('tt','s');
	// 	$address = sget('address','s');
	// 	$a_perTel = explode("/",sget('perTel','s'));
	// 	//三表查询
	// 	$c_id = $this->db->model('customer_contact')->where('user_id='.$this->user_id)->select('c_id')->getOne();
	// 	$cus = M('user:customer')->getCinfoById($c_id);

	// 	$data['user_id'] = $this->user_id;
	// 	$data['customer_id'] = $c_id;
	// 	$data['customer_name'] = $cus['c_name'];
	// 	$data['invoice_name'] = $tt;
	// 	$data['invoice_address'] = $address;
	// 	$data['invoice_receive'] = $a_perTel[0];
	// 	$data['invoice_recieve_tel'] = $a_perTel[1];
	// 	$data['invoice_default'] = 2;
	// 	$data['remark'] = "挺好";
	// 	$data['grounp_no'] = $cus['grounp_no'];
	// 	$data['input_time'] = CORE_TIME;
	// 	$data['input_admin'] = $_SESSION['name'];
	// 	$data['update_time'] = CORE_TIME;
	// 	$data['update_admin'] = $_SESSION['name'];

	// 	if($this->db->model('invoice_account')->add($data)) $this->json_output(array('err'=>0,'msg'=>'增加成功'));
	// }
	//返回开票详情数据
	// public function getBillInfo(){
	// 	$this->is_ajax = true;
	// 	if($this->user_id<0) $this->error('账户错误');
	// 	$page=sget('page','i',1);
	// 	$size=3;
	// 	//分页获取对应用户开票详情
	// 	$list = $this->db->select('id,invoice_name,invoice_address,invoice_receive,invoice_recieve_tel,invoice_default')->where('user_id='.$this->user_id)
	// 		->page($page,$size)
	// 		->order("input_time desc")
	// 		->getPage();
	// 	foreach ($list['data'] as $key => $value) {
	// 		$list['data'][$key]['invoice_default'] = L('is_invoice_default')[$value['invoice_default']];
	// 	}
	// 	$pages = pages($list['count'], $page, $size);
	// 	$this->json_output(array('detail'=>$list['data'],'pages'=>$pages));
	// }
}