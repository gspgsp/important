<?php
/**
*个人中心-主营产品
*/
class mainProductAction extends userBaseAction
{
	public function __init(){
		$this->db = M('public:common')->model('cop_product');
	}
	//显示主营产品
	public function getMainProduct(){
		$this->act='myProduct';

		$this->product_kind=L('product_kind');
		//分页
		$page=sget('page','i',1);
		$page_size=10;
		//获取资讯分页列表
		$mainPro = $this->db->where('cid='.$this->uinfo['c_id'])->order("input_time desc")->page($page,$page_size)->getPage();
		foreach ($mainPro['data'] as $key => $value) {
			$mainPro['data'][$key]['status'] = L('publish_status')[$value['status']];
			$mainPro['data'][$key]['input_time'] = $value['input_time']>1000 ? date("Y-m-d H:i:s",$value['input_time']):'-';
			$mainPro['data'][$key]['update_time'] = $value['update_time']>1000 ? date("Y-m-d H:i:s",$value['update_time']):'-';
			$mainPro['data'][$key]['operate'] = L('publish_operate')[$value['operate']];
		}
		// p($mainPro);
		$this->pages = pages($mainPro['count'], $page, $page_size);
		$this->assign('detail',$mainPro['data']);
		$this->display('mainPro');
	}
	//取消发布或重新发布
	public function changeFocusState(){
		$pid = sget('pId','i');
		$data = $this->db->select('status,operate')->where('id='.$pid)->getRow();
		$data['status'] = $data['status']==1 ? 2 : 1;
		$data['operate'] = $data['status']==1 ? 1 : 2;
		if($this->db->where('id='.$pid)->update($data))
			$this->json_output(array('err'=>0,'msg'=>'关注改变成功','status'=>$data['status']));
	}
	//批量发布
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
		foreach ($ids as $key => $value) {
			$data = $this->db->select('id,status,operate')->where('id='.$value)->getRow();
			$data['status'] = 2;
			$data['operate'] = 2;
			$this->db->where('id='.$value)->update($data);
			$newData[] = $data;
		}
		$this->json_output(array('err'=>0,'msg'=>'关注改变成功','status'=>2,'newData'=>$newData));
	}
	//添加主营产品
	public function addMainProduct(){
		if($_POST){
			$this->is_ajax=true;
			$product['user_id'] = $this->user_id;
			$product['cid'] = $this->uinfo['c_id'];
			$product['image'] = $_POST['pro_img'];
			$product['name'] = $_POST['pro_name'];
			$product['infos'] = $_POST['pro_inf'];
			$product['type']=$_POST['pro_type'];
			$product['status'] = 1;
			$product['operate'] = 1;
			$product['input_time'] = CORE_TIME;
			$product['update_time'] = CORE_TIME;
			if(!$this->db->model('cop_product')->add($product))
				$this->error('添加资讯失败');
			 $this->success('添加资讯成功');
		}
	}
}