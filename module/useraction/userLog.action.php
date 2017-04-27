<?php
/**
*  用户行为操作记录
 * @auth gsp
*/
class userLogAction extends adminBaseAction
{
	/**
	 * 初始化方法
	 * @author gsp <[<email address>]>
	 * @return [type] [description]
	 */
	public function __init(){
		$this->debug = false;
		$this->db=M('public:common')->model('chk_node');
	}
	public function init(){
		$action=sget('action','s');
		if($action=='grid'){ //获取列表
			$this->_grid();exit;
		}
		$this->assign('page_title','资源库列表');
		$this->display('user_log');
	}
		/**
	 * Ajax获取列表内容
	 * @access private
	 * @return html
	 */
	private function _grid(){
		// p(gethostbyaddr(get_ip()));
		// p($this->getBrowser());
		// p($this->getBrowserVer());die;
		$page = sget("pageIndex",'i',0); //页码
		$size = sget("pageSize",'i',20); //每页数
		$sortField = sget("sortField",'s','id'); //排序字段
		$sortOrder = sget("sortOrder",'s','asc'); //排序
		$where = "1";
		$list = $this->db->where($where)
				->select('id,name,url,pid')
				->page($page+1,$size)
				->order("$sortField $sortOrder")
				->getPage();
		foreach ($list['data'] as &$value) {
			$value['chkco'] = $this->getNode("action = '{$value['url']}'",1);
			// $value['urls'] = json_encode(array_filter(explode('/',$value['url'])));

		}
		$result=array('total'=>$list['count'],'data'=>$list['data']);
		$this->json_output($result);
	}
	/**
	 * 获取节点
	 * @return [type] [description]
	 */
	public function getNode($where = '1',$act = 0){
		$nodes = $this->db->model('log_admin')->where($where)->getAll();
		switch ($act) {
			case 0:
				return $nodes;
			case 1:
				return count($nodes);
		}
	}
	/**
	 * 显示用户日志
	 */
	public function showUserLogs(){
		$pid=sget('pid','i');
		$action = sget('action','s');

		if($action == 'show'){
			$page = sget("pageIndex",'i',0); //页码
			$size = sget("pageSize",'i',20); //每页数
			$sortField = sget("sortField",'s','id'); //排序字段
			$sortOrder = sget("sortOrder",'s','desc'); //排序


			$adminList = $this->db->model('admin')->select('admin_id,username')->getAll();
			foreach($adminList as $k=>$v){
				$adminArr[$v['admin_id']]=$v['username'];
			}

			$list = $this->db->from('adm_node an')
				->leftjoin('log_admin la','an.name = la.action')
				->select('la.*,an.title')
				->where("an.id = $pid")
				->page($page+1,$size)
				->order("$sortField $sortOrder")
				->getPage();
			foreach ($list['data'] as &$value) {
				$value['username']=$adminArr[$value['admin_id']];
				$value['input_time']=$value['input_time']>1000 ? date("Y-m-d H:i:s",$value['input_time']) : '-';
			}
			$result=array('total'=>$list['count'],'data'=>$list['data']);
			$this->json_output($result);
			}
		$this->assign('page_title','管理员操作日志');
		$this->assign('pid',$pid);
		$this->display('user_log_detail.html');
	}
	/**
	 * 新增需要检测的节点
	 * @return [type] [description]
	 */
	public function editLogNode(){
		if(sget('action','s') == 'add'){
			$this->assign('etype',1);
			$this->display('user_log_add.html');
		}
	}
	/**
	 * 节点数据操作
	 */
	public function editLogData(){
		$this->ajax = true;
		if(sget('action','s') == 'save' && sget('type','i') == 1){
			$name = sget('name','s');
			$url = sget('url','s');
			if($pid = $this->getAdmNode($name,$url)){
				$arr = array(
					'pid' => $pid,
					'name' => $name,
					'url' => $url,
					'input_time' => CORE_TIME
					);
				if($this->db->model('chk_node')->where("pid = $pid")->getRow()) $this->json_output(array('err'=>4,'msg'=>'新增节点已存在'));
				if($this->db->model('chk_node')->add($arr)){
					$this->json_output(array('err'=>0,'msg'=>'新增成功'));
				}else{
					$this->json_output(array('err'=>2,'msg'=>'新增失败'));
				}
			}else{
				$this->json_output(array('err'=>3,'msg'=>'新增节点不在节点表内'));
			}
		}elseif (sget('action','s') == 'remove') {
			$ids = trim(sget('ids','s'));
			if(!empty($ids)){
				if($this->db->model('chk_node')->where("id in ($ids)")->delete()){
					$this->json_output(array('err'=>0,'msg'=>'删除成功'));
				}else{
					$this->json_output(array('err'=>2,'msg'=>'删除失败'));
				}
			}else{
				$this->json_output(array('err'=>3,'msg'=>'没有可删除数据'));
			}
		}
	}
	/**
	 * 获取父级节点
	 * [getAdmNode description]
	 * @return [type] [description]
	 */
	public function getAdmNode($name,$url){
		$id = $this->db->model('adm_node')->where("title = '{$name}' and name ='{$url}' and ntype = 1")->select('id')->getOne();
		return $id;
	}

	public function getRealIpAddr()
	{
	if (!empty($_SERVER['HTTP_CLIENT_IP']))
	{
	$ip=$_SERVER['HTTP_CLIENT_IP'];
	}
	elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
	//检查IP是从代理传递
	{
	$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
	}
	else
	{
	$ip=$_SERVER['REMOTE_ADDR'];
	}
	echo  $ip;
	}
}