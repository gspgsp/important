<?php
/**
 *个人中心-关注产品
 */
class productAttentionAction extends userBaseAction
{
	public function __init(){
		$this->db = M('public:common')->model('concerned_product');
	}
	//关注产品
	public function proAttentionValue(){
		$page=sget('page','i',1);
		$size=2;
		$model = 'concerned_product';
		$data = $this->_getAttentionvalue($model,$page,$size);
		$this->assign('detail',$data['detail']);
		$this->assign('pages',$data['pages']);
		$this->display('product_list');
	}
	//添加新的产品关注
	public function addProAttention(){
		$this->is_ajax=true; //指定为Ajax输出
		// $dataP = sdata(); //传递的参数:产品类别/产品牌号/产地/备注
		if(empty($_POST)){
			$this->error('请添加关注产品信息');
		}
		/**
		 * 检查该产品是否已经关注过
		 *
		 */
		$fid = M('product:factory')->getIdsByName($_POST['address']);//根据厂家查出fid
		$pid = M('product:product')->getPidByModel($_POST['num'], $fid[0]);//根据牌号和fid查出产品id
		if($this->db->model('concerned_product')->select('product_id')->where('product_id='.$pid)->getOne()) $this->error('该产品已经关注过');

		$userContact = M('user:customerContact')->getListByUserid($this->user_id);
		$company = M('user:customer')->getCinfoById($userContact['c_id']);

		$data['user_id'] = $this->user_id;
		$data['product_name'] = L('factory_name')[$_POST['kid']];
		$data['model'] = $_POST['num'];
		$data['factory_name'] = $_POST['address'];
		$data['remark'] = $_POST['remark'];
		$data['user_account_id'] = $userContact['mobile'];
		$data['staff_name'] = $userContact['name'];
		$data['customer_name'] = $company['c_name'];
		$data['product_id'] = $pid;
		$data['status'] = 1;
		$data['operate'] = 1;
		$data['groupno'] = $company['grounp_no'];
		$data['input_time'] = CORE_TIME;
		$data['input_admin'] = $_SESSION['name'];
		$data['update_time'] = CORE_TIME;
		$data['update_admin'] = $_SESSION['name'];

		if(!M('user:account')->add($data)) $this->error('添加关注失败');
		$this->success('添加关注成功');
	}
	//获取关注的列表
	private function _getAttentionvalue($model,$page,$size){
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
		//$this->json_output($newData);
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
		//$this->json_output($newData);
		$this->json_output(array('err'=>0,'msg'=>'关注改变成功','status'=>2,'newData'=>$newData));
	}
	//ajax联动操作
	public function getModelByCla(){
        $this->is_ajax=true; //指定为Ajax输出
        $kid = sget('kind','i');//12345
        $models = $this->db->model('product')->select('model')->where('product_type='.$kid)->order('input_time desc')->limit('0,20')->getAll();
        if(!$models)
            $this->json_output(array('err'=>2,'msg'=>'没有相关牌号结果'));
            $this->json_output(array('err'=>0,'models'=>$models));
    }
    //ajax联动操作
    public function getFactoryByMod(){
        $this->is_ajax=true; //指定为Ajax输出
        $model = sget('model','s');
        $facId = $this->db->model('product')->select('f_id')->where("model='{$model}'")->order('input_time desc')->limit('0,20')->getAll();
        $factorys = array();
        foreach ($facId as $key => $value) {
            $f_name = $this->db->model('factory')->select('f_name')->where('fid='.$value['f_id'])->getOne();
            $factorys[$key] = $f_name;
        }
        if(!$factorys)
            $this->json_output(array('err'=>2,'msg'=>'没有相关厂家结果'));
            $this->json_output(array('err'=>0,'factorys'=>$factorys));
    }
}