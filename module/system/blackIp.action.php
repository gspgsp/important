<?php
/** 
 * IP黑名单
 */
class blackIpAction extends adminBaseAction {
	public function __init(){
		$this->debug = false;
		$this->action = sget('action','');
		$this->db=M('public:common')->model('black_ip');
	}
	
	/**
	 * IP黑名单列表
	 * @access public 
	 * @return html
	 */
	public function init(){
		$action=sget('action','s');
		if($action=='grid'){
			$page = sget("pageIndex",'i',0); //页码
			$size = sget("pageSize",'i',20); //每页数
			$sortField = sget("sortField",'s','input_time'); //排序字段
			$sortOrder = sget("sortOrder",'s','desc'); //排序

			//搜索条件
			$where=" 1 ";
			
			$list=$this->db->where($where)
						->page($page+1,$size)
						->order("$sortField $sortOrder")
						->getPage();
			foreach($list['data'] as $k=>$v){
				$list['data'][$k]['input_time']=$v['input_time']>1000 ? date("Y-m-d H:i:s",$v['input_time']) : '-';
				$list['data'][$k]['expiration']=$v['expiration']>1000 ? date("Y-m-d H:i:s",$v['expiration']) : '-';
				$ip=$v['ip1'].'.'.$v['ip2'].'.'.$v['ip3'].'.'.$v['ip4'];
				$list['data'][$k]['ip']=str_replace('-1','*',$ip);
			}
			$result=array('total'=>$list['count'],'data'=>$list['data']);
			$this->json_output($result);
		}
		
		$this->assign('page_title','IP黑名单');
		$this->display('black_ip.html');
	}

	/**
	 * IP黑名单保存数据
	 * @access public 
	 * @return html
	 */
	public function ajaxSave(){
		$this->is_ajax=true; //指定为Ajax输出
		$data = sdata(); //获取UI传递的参数
		$sql=array();
		if(empty($data)){
			$this->error('操作数据为空');
		}
		
		//自身IP
		$ip=explode('.',get_ip());
		$own = 0;
		for($i=1;$i<=4;$i++){
			$_ip=$data['ip'.$i];
			if(!is_numeric($_ip) || $_ip<0){
				$_ip=-1;
				$own++;
			}elseif($_ip==$ip[$i-1]){
				$own++;
			}
			$data['ip'.$i]=(int)$_ip;
		}
		if($own == 4){
			$this->error('不能将自己的IP进入黑名单');	
		}
		
		//检查是否存在
		$exist=$this->db->select('id')->where('ip1='.$data['ip1'].' and ip2='.$data['ip2'].' and ip3 in (-1,'.$data['ip3'].') and ip4 in (-1,'.$data['ip4'].')')->getOne();
		if($exist){
			$this->error('此IP已经在黑名单中，请重新输入');	
		}
		$data['input_time']=CORE_TIME;
		$data['admin']=$_SESSION['name'];
		$data['expiration']=CORE_TIME+$data['day']*86400;
		unset($data['day']);
		
		$result=$this->db->add($data);
		if($result){
			$this->clearMCache('blackIp');
			$this->success('操作成功');
		}else{
			$this->error('数据处理失败');
		}
	}

	/**
	 * IP黑名单删除
	 * @access public 
	 */
	public function remove(){
		$this->is_ajax=true; //指定为Ajax输出
		$ids=sget('ids','s');
		if(empty($ids)){
			$this->error('操作有误');	
		}
		$result=$this->db->where("id in ($ids)")->delete();
		if($result){
			$this->clearMCache('blackIp');
			$this->success('操作成功');
		}else{
			$this->error('数据处理失败');
		}
	}
}
?>
