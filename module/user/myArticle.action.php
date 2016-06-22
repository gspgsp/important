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
		//分页
		$page=sget('page','i',1);
		$page_size=5;
		//获取资讯分页列表
		$myArticle = $this->db->where('cid='.$this->user_id)->page($page,$page_size)->getPage();
		foreach ($myArticle['data'] as $key => $value) {
			$myArticle['data'][$key]['status'] = L('publish_status')[$value['status']];
			$myArticle['data'][$key]['type'] = L('article_kind')[$value['type']];
			$myArticle['data'][$key]['input_time'] = $value['input_time']>1000 ? date("Y-m-d H:i:s",$value['input_time']):'-';
			$myArticle['data'][$key]['update_time'] = $value['update_time']>1000 ? date("Y-m-d H:i:s",$value['update_time']):'-';
			$myArticle['data'][$key]['operate'] = L('publish_operate')[$value['operate']];
		}
		$this->pages = pages($myArticle['count'], $page, $page_size);
		$this->assign('detail',$myArticle['data']);
		$this->display('myArticle');
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
	//新增资讯
	public function addMyArticle(){
		if($_POST){
			$this->is_ajax=true;
			$article['cid'] = $this->user_id;
			$article['title'] = $_POST['tt'];
			$article['content'] = $_POST['art_info'];
			$article['type'] = $_POST['kid'];
			$article['image'] = $_POST['img'];
			$article['status'] = 1;
			$article['operate'] = 1;
			$article['input_time'] = CORE_TIME;
			$article['update_time'] = CORE_TIME;

			if(!$this->db->model('cop_article')->add($article)) $this->error('添加资讯失败');
			$this->success('添加资讯成功');
		}
	}
}