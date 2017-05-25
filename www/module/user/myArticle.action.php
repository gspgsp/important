<?php
/**
 *个人中心-我的资讯
 */
class myArticleAction extends userBaseAction
{
	public function __init(){
		$this->db = M('public:common')->model('cop_article');
	}
	//获取资讯列表
	public function getMyArticle(){
		$this->act='myArticle';
		//分页
		$page=sget('page','i',1);
		$page_size=10;
		//获取资讯分页列表
		$list = $this->db->where('cid='.$this->uinfo['c_id'])->order('input_time desc')->page($page,$page_size)->getPage();
		foreach ($list['data'] as &$value) {
			$value['type']=L('article_kind')[$value['type']];
			$value['status']=L('publish_status')[$value['status']];
			$value['operate']=L('publish_operate')[$value['operate']];
		}
		$this->pages = pages($list['count'], $page, $page_size);
		$this->assign('list',$list['data']);
		$this->display('myArticle');
	}

	// 添加我的资讯
	public function addArticle(){
		$this->cate=L('article_kind');
		$this->display('addArticle');
	}

	public function saveArticle(){
		if($_POST){
			$this->is_ajax=true;
			$data=saddslashes($_POST);
			$data['cid']=$this->uinfo['c_id'];
			$data['user_id']=$this->user_id;
			$data['status']=1;
			$data['operate'] = 1;
			$data['input_time']=CORE_TIME;
			$this->db->add($data);
			$this->success('提交成功');
		}
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