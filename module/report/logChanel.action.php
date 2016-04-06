<?php
/** 
 * 渠道访问日志
 * Andy@2013-01-21
 */
class logChanelAction extends adminBaseAction {	
	public function __init(){
		$this->db=M('public:common')->model('log_chanel');
	}
	
	/**
	 * 渠道日志列表
	 * @access public 
	 * @return html
	 */
	public function init(){
		//获取列表数据
		$action=sget('action');
		if($action=='grid'){
			$page = sget("pageIndex",'i',0); //页码
			$size = sget("pageSize",'i',20); //每页数
			$sortField = sget("sortField",'s','input_time'); //排序字段
			$sortOrder = sget("sortOrder",'s','desc'); //排序
			
			//搜索条件
			$where="1 ";
			
			//交易时间筛选
			$where.=getTimeFilter('input_time');
			//关键词
			$key_type=sget('key_type','s','chanel_id');
			$keyword=sget('keyword','s');
			if(!empty($keyword)){
				if($key_type=='chanel'){
					$chanel_id=$this->db->model('chanel')->select('chanel_id')->where("name='$keyword'")->getOne();
					if(empty($chanel_id)) $chanel_id=-1;
					$where.=" and chanel_id='$chanel_id' ";	
				}else{
					$where.=" and $key_type='$keyword' ";	
				}
			}
			
			$logs=$this->db->model('log_chanel')
						->where($where)
						->page($page+1,$size)
						->order("$sortField $sortOrder")
						->getPage();
			
			$chanels=M('system:chanel')->getChanels();
			$chanels=array_flip($chanels);
			foreach($logs['data'] as $k=>$v){
				$logs['data'][$k]['input_time']=date("Y-m-d H:i:s",$v['input_time']);
				$logs['data'][$k]['chanel']=$chanels[$v['chanel_id']];
			}
	
			$msg="";
			$result=array('total'=>$logs['count'],'data'=>$logs['data'],'msg'=>$msg);
			$this->json_output($result);
		}

		$this->assign('page_title','渠道访问日志');
		$this->display('logChanel.html');
	}
}
?>
