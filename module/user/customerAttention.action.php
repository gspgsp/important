<?php
/**
*个人中心-关注商家
*/
class customerAttentionAction extends homeBaseAction
{

	public function __init(){
		$this->db = M('public:common')->model('concerned_customer');
	}
	//关注商家
	public function cusAttentionList(){
		$page=sget('page','i',1);
		$size=2;
		$model = 'concerned_customer';
		$data = $this->_getAttentionList($model,$page,$size);
		$this->assign('detail',$data['detail']);
		$this->assign('pages',$data['pages']);
		$this->display('customer_list');
	}
	//添加新的商家关注
	public function addCusAttention(){
		$this->is_ajax=true; //指定为Ajax输出
		//$data = sdata(); //传递的参数:商家名称/联系人/联系电话mobile/备注
		if(empty($_POST)){
			$this->error('请添加关注商家信息');
		}
		/**
		 * 检查该商家是否已经关注过
		 *
		 */
		$customer = M('user:customer')->getCompanyByName($_POST['cus_name']);//根据商家名称查找商家
		if($this->db->model('concerned_customer')->select('customer_id')->where('customer_id='.$customer['c_id'])->getOne()) $this->error('该商家已经关注过');

		$userContact = M('user:customerContact')->getListByUserid($this->user_id);

		$data['user_id'] = $this->user_id;
		$data['user_account_id'] = $userContact['mobile'];
		$data['staff_name'] = $userContact['name'];

		$data['customer_id'] = $customer['c_id'];
		$data['contact_name'] = '';
		$data['comm_phone'] = '';

		$data['customer_name2'] = $_POST['cus_name'];
		$data['contact_name'] = $_POST['contact'];
		$data['mobile_phone'] = $_POST['mobile'];
		$data['remark'] = $_POST['remark'];

		$data['status'] = 1;
		$data['operate'] = 1;
		$data['groupno'] = $customer['grounp_no'];
		$data['input_time'] = CORE_TIME;
		$data['input_admin'] = $_SESSION['name'];
		$data['update_time'] = CORE_TIME;
		$data['update_admin'] = $_SESSION['name'];

		if(!$this->db->model('concerned_customer')->add($data)) $this->error('添加关注失败');
		$this->success('添加关注成功');
	}
	//获取关注的列表
	private function _getAttentionList($model,$page,$size){
		$list = $this->db->model($model)->where('user_id='.$this->user_id)->page($page,$size)
			->order("input_time desc")
			->getPage();
		foreach ($list['data'] as $key => $value) {
			$list['data'][$key]['status'] = L('attention_status')[$value['status']];
			$list['data'][$key]['operate'] = L('operate')[$value['operate']];
			$list['data'][$key]['input_time'] = $value['input_time']>1000 ? date("Y-m-d H:i:s",$value['input_time']):'-';
			$list['data'][$key]['update_time'] = $value['update_time']>1000 ? date("Y-m-d H:i:s",$value['update_time']):'-';
		}
		$pages = pages($list['count'], $page, $size);
		return array('detail'=>$list['data'],'pages'=>$pages);
	}
	//取消或重新关注
	public function changeFocusState(){
		$pid = sget('pId','i');
		$data = $this->db->select('status,operate')->where('id='.$pid)->getRow();
		$data['status'] = $data['status']==1 ? 2 : 1;
		$data['operate'] = $data['status']==1 ? 1 : 2;
		if($this->db->where('id='.$pid)->update($data))
			$this->json_output(array('err'=>0,'msg'=>'关注改变成功','status'=>$data['status']));
	}
	//批量关注
	public function mulLook(){
		$ids = sget('ids','a');
		$newData = array();
		foreach ($ids as $key => $value) {
			$data = $this->db->select('id,status,operate')->where('id='.$value)->getRow();
			$data['status'] = 1;
			$data['operate'] = 1;
			$this->db->where('id='.$value)->update($data);
			$newData[] = $data;
		}
		$this->json_output(array('err'=>0,'msg'=>'关注改变成功','status'=>1,'newData'=>$newData));
	}
	//批量取消
	public function mulQuit(){
		$ids = sget('ids','a');
		$newData = array();
		foreach ($ids as $key => $value) {
			$data = $this->db->select('id,status,operate')->where('id='.$value)->getRow();
			$data['status'] = 2;
			$data['operate'] = 2;
			$this->db->where('id='.$value)->update($data);
			$newData[] = $data;
		}
		$this->json_output(array('err'=>0,'msg'=>'关注改变成功','status'=>2,'newData'=>$newData));
	}
}