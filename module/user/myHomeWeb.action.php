<?php
/**
*个人中心-我的门户网站
*/
class myHomeWebAction extends userBaseAction
{
	public function __init(){
		$this->db = M('public:common')->model('customer');
	}
	//进入我的门户网站
	public function getMyWeb(){
		$this->act='myHomepage';

        $data = $this->_aboutUs();
        $contact = $this->_linkUs($data);
        $this->assign('data',$data);
        $this->assign('contact',$contact);
        $this->display('my_web');
	}
	//点击进入我的网站
	public function clickMyWeb(){
		//客户id
		$c_id = sget('id','i');
		p($c_id);die;
		//关于我们
		$data = $this->_aboutUs();
		//最新资讯
		$page=1;
		$size=10;
		$articles = $this->_newInfo($page, $size);
		//产品展示
		$products = $this->_showProduct();
		//产品分类展示
		$kindPro = $this->_showKindProduct();
		//联系我们
		$contact = $this->_linkUs($data);

		$this->assign('data',$data);
		$this->assign('page',$page);
		$this->assign('size',$size);
		$this->assign('info',$articles['info']);
		$this->assign('products',$products);
		$this->assign('kindPro',$kindPro);
		$this->assign('contact',$contact);
		ob_end_clean();//清空缓冲区
        ob_start();//开启缓存
		$this->display('en_my_site');
		$data = ob_get_contents();
		file_put_contents('./company/'.$c_id.'.html',$data);
		//ob_end_clean();//清空缓冲区

	}
	//修改个人信息
	public function editUserInfo(){
		$data = $this->_aboutUs();
        $contact = $this->_linkUs($data);
        $this->assign('data',$data);
        $this->assign('contact',$contact);
        $this->display('edit_user_info');
	}
	//确认修改个人信息
	public function saveChange(){
		if($_POST){
			$this->is_ajax=true;
			//$data=saddslashes($_POST);
			$where = "user_id=$this->user_id";
			$data1['name'] = trim($_POST['name']);
			$data1['tel'] = trim($_POST['tel']);
			$data1['email'] = trim($_POST['email']);
			$data1['qq'] = trim($_POST['qq']);
			$data1['fax'] = trim($_POST['fax']);
			$data1['mobile'] = trim($_POST['mobile']);
			$data1['update_time'] = CORE_TIME;
			$data1['update_admin'] = trim($_POST['name']);

			$data2['c_name'] = trim($_POST['c_name']);
			$data2['com_logo'] = trim($_POST['image']);
			$data2['com_intro'] = trim($_POST['companyIntro']);
			$data2['address'] = trim($_POST['address']);
			$data2['type'] = trim($_POST['classify']);
			$data2['update_time'] = CORE_TIME;
			$data2['update_admin'] = trim($_POST['name']);

			$customer_contact = M('user:customerContact');
			// $result = $customer_contact->checkEditInfo($data1);
			// if($result['err']>0) $this->err('信息格式不正确');
			$c_id = $customer_contact->select('c_id')->where($where)->getOne();
			$customer = M('user:customer');
			$customer->startTrans();
			try {
				if(!$customer_contact->where($where)->update($data1)) throw new Exception("保存失败1", 1);

				if(!$customer->where('c_id='.$c_id)->update($data2)) throw new Exception("保存失败2", 1);
			} catch (Exception $e) {
				$customer->rollback();
				$this->error($e->getMessage());
			}
			$customer->commit();
			$this->success('保存成功');
		}
	}
	//关于我们
	private function _aboutUs(){
		// if(!$data=$this->db->model('customer')->where('c_id='.$this->user_id)->select('c_name,address,type,contact_id,com_intro,com_logo')->getRow()) $this->error('没有客户信息');
		$data=$this->db->model('customer')->select('c_name,address,type,contact_id,com_intro,com_logo')->where('c_id='.$this->uinfo['c_id'])->getRow();
		return $data;
	}
	//联系我们
	private function _linkUs($data){
		if(!$contact=$this->db->model('customer_contact')->where('user_id='.$data['contact_id'])->select('name,tel,fax,mobile,qq,email')->getRow()) $this->error('没有联系人信息');
		return $contact;
	}
	//最新资讯
	private function _newInfo($page, $size){
		if(!$info=$this->db->model('cop_article')->where('cid='.$this->user_id)->page($page,$size)->order('input_time desc')->getPage()) $this->error('没有最新资讯');
		foreach ($info['data'] as $key => $value) {
			$info['data'][$key]['input_time'] = $value['input_time']>1000 ? date("Y-m-d",$value['input_time']):'-';
		}
		return array('info'=>$info['data']);
	}
	//产品展示
	private function _showProduct(){
		if(!$products=$this->db->model('cop_product')->where('cid='.$this->user_id)->order('input_time desc')->getAll()) $this->error('没有产品资料');
		return $products;
	}
	//获取资讯详情
	public function getArticleDetail(){
		$id = sget('id','i');
		if($data = $this->db->model('cop_article')->where('id='.$id)->getRow())
			$this->json_output(array('err'=>0,'msg'=>'找到相关资讯','data'=>$data));
	}
	//获取更多资讯详情
	public function getMoreArticleDetail(){
		$page = sget('page','i');
		$size = sget('size','i');
		$articles = $this->_newInfo($page, $size);
		$this->json_output(array('err'=>0,'msg'=>'加载更多成功','info'=>$articles['info']));
	}
	//产品分类展示
	private function _showKindProduct(){
		$product1=$this->_getKindProduct(1);
		$product2=$this->_getKindProduct(2);
		$product3=$this->_getKindProduct(3);
		return array('product1'=>$product1,'product2'=>$product2,'product3'=>$product3);
	}
	//产品分类展示(封装)
	private function _getKindProduct($type){
		$product = $this->db->model('cop_product')->where("cid=$this->user_id and type=$type")->order('input_time desc')->limit('0,3')->getAll();
		return $product;
	}
}