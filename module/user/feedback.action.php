<?php
/** 
 * 客户留言管理
 */
class feedbackAction extends adminBaseAction {
	public function __init(){
		$this->debug = false;
		$this->db=M('public:common')->model('customer_message');
	}

	/**
	 * 客户留言管理列表
	 * @access public 
	 * @return html
	 */
	public function init(){
		
		$action=sget('action','s');
		$this->user_id=sget('user_id','i',0);
		if($action=='grid'){
			//分页
			$page = sget("pageIndex",'i',0); //页码
			$size = sget("pageSize",'i',20); //每页数
			$sortField = sget("sortField",'s','input_time'); //排序字段
			$sortOrder = sget("sortOrder",'s','desc'); //排序

			$where=" 1 ";
			$sTime = sget("sTime",'s','last_time'); //搜索时间类型
			$where.=getTimeFilter($sTime); //时间筛选
			
			$ac_type = sget("ac_type",'i',''); //状态
			if($ac_type!=''){
				$chk_status = $ac_type-1;
				$where.=" and chk_status='$chk_status' ";
			} 	

			if($this->user_id>0) $where.=" and user_id='$this->user_id' ";	

			//关键词
			$key_type=sget('key_type','s','user_id');
			$keyword=sget('keyword','s');
			
			if(!empty($keyword)){
				if($key_type=='mobile'){
					$exist=$this->db->model('user')->select("user_id")->where("mobile='$keyword'")->getRow();
					$key_type = 'user_id';
					$keyword = $exist['user_id'];
				}
				$where.=" and $key_type='$keyword' ";
			}
			
			$list=$this->db->model('customer_message')->where($where)
						->page($page+1,$size)
						->order("$sortField $sortOrder")
						->getPage();
			
			$msgType=L('msg_type'); //
			$replyWay=L('reply_way'); //
			$replyTime=L('reply_time'); //
			$msgStatus=L('reply_status');

			foreach($list['data'] as $k=>$val){
				if($val['sex']==1){
					$sex = "男";
				}else if($val['sex']==2){
					$sex = "女";
				}
				$list['data'][$k]['msg_type']=$msgType[$val['msg_type']];
				$list['data'][$k]['reply_way']=$replyWay[$val['reply_way']];
				$list['data'][$k]['reply_time']=$replyTime[$val['reply_time']];
				$list['data'][$k]['sex']=$sex;
				if($val['reply_way']==1){
					$chk_status = "无需处理";
				}else{
					$chk_status = $msgStatus[$val['status']];
				}

				$list['data'][$k]['status']=$chk_status;
				$list['data'][$k]['input_time']=$val['input_time']>1000 ? date("Y-m-d H:i:s",$val['input_time']) : '-';
				
			}
			$result=array('total'=>$list['count'],'data'=>$list['data'],'msg'=>'');
			$this->json_output($result);
		}
		
		$this->assign('page_title','银行卡列表');
		$this->display('message.list.html');
	}
	
	/**
     * 客户留言详细信息
     * @access public
     */
	public function view(){
		$id=sget('id','i');
		
		$this->msgType=L('msg_type'); //
		$this->replyWay=L('reply_way'); //
		$this->replyTime=L('reply_time'); //
		$this->msgStatus=L('reply_status');
			
		$user['msg']=$this->db->getPk($id);
	
		$this->assign('user',$user);

		$this->display('message.view.html');
		
    }
	
	/**
	 * 修改客户留言回复状态
	 * @access public 
	 * @return html
	 */
	public function apply(){
		$id=sget('id','s');
		$msgStatus=L('reply_status');
		$this->assign('msgStatus',$msgStatus);
		$this->assign('id',$id);
		$this->display('message.app.html');
	}
	
	/**
	 * 客户留言回复
	 * @access public 
	 * @return html
	 */
	public function replyMessage(){
		$this->is_ajax=true; //指定为Ajax输出
		$dataArr = sdata(); //获取UI传递的参数
		$ids = $dataArr['id'];
		$check_status=$dataArr['status'];
		$reply_content=$dataArr['reply_content'];

		if(empty($ids)){
			$this->error('操作有误');	
		}
		
		$data=array(
			'status'=>$check_status,
			'reply_content'=>$reply_content,
			'act_time'=>CORE_TIME,
			'admin_name'=>$_SESSION['name'],
		);
		$result=$this->db->where("id in ($ids)")->update($data);

		if($result){
			$this->success('操作成功');
		}else{
			$this->error('数据处理失败');
		}
	}
	
	/**
	 * 发送邮件
	 * @access public 
	 * @return html
	 */
	public function sendEmail(){
		$this->is_ajax=true; //指定为Ajax输出
		
		$data = sdata(); //传递的参数
		
		if(empty($data)){
			$this->error('错误的请求');	
		}
		$id=(int)$data['user_id'];	
		M('system:sysMail')->send($id,$data['email'],$data['title'],$data['comments'],$data['user_name']='',5);
		$this->success('邮件发送成功！');
	}
}
?>
