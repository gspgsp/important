<?php
/**
 * 站内信消息列表
 */
class msgAction extends homeBaseAction{

	protected $db;
	public function __init()
	{
		$this->db=M('public:common');
	}

	public function init()
	{
		$this->act="msg";
		$this->msg_status=L('msg_status');
		$this->msg_type=L('msg_type');

		$where="user_id=$this->user_id";
		//已读状态筛选
		if($is_read=sget('status','i',0)){
			$this->assign('is_read',$is_read);
			$where.=" and is_read=$is_read";
		}
		//信息分类筛选
		if($type=sget('type','i',0)){
			$this->assign('type',$type);
			$where.=" and type=$type";
		}

		$page=sget('page','i',1);
		$size=10;
		$list=$this->db->model('user_msg')
			->where($where)
			->order("input_time desc")
			->page($page,$size)
			->getPage();
		$this->pages=pages($list['count'],$page,$size);
		$this->assign('list',$list);
		$this->display('msg');
	}

	public function readMsg()
	{
		if($_POST)
		{
			$this->is_ajax=true;
			$id=sget('id','i',0);
			$model=$this->db->model('user_msg');
			$where="id=$id and user_id=$this->user_id";
			if(!$model->where($where)->getRow()) $this->error('消息不存在');
			$model->where($where)->update("is_read=2");
			$this->success('操作成功');
		}
	}

	public function readAll()
	{
		if($_POST)
		{
			$model=$this->db->model('user_msg');
			$ids=sget('ids');
			if(!$model->where("id in (".implode(',',$ids).") and user_id=$this->user_id")->update("is_read=2")) $this->error('消息不存在');
			$this->success('操作成功');
		}
	}
}