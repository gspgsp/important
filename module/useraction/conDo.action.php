<?php
/**
*  用户操作记录
 * @auth gsp
*/
class conDoAction extends adminBaseAction
{
	/**
	 * 初始化方法
	 * @author gsp <[<email address>]>
	 * @return [type] [description]
	 */
	public function __init(){
		$this->debug = false;
		$this->db=M('public:common')->model('admin');
	}
	public function init(){
		$action=sget('action','s');
		if($action=='grid'){ //获取列表
			$this->_grid();exit;
		}
		$this->assign('page_title','资源库列表');
		$this->display('admin_log');
	}
		/**
	 * Ajax获取列表内容
	 * @access private
	 * @return html
	 */
	private function _grid(){
		$page = sget("pageIndex",'i',0); //页码
		$size = sget("pageSize",'i',20); //每页数
		$sortField = sget("sortField",'s','admin_id'); //排序字段
		$sortOrder = sget("sortOrder",'s','asc'); //排序
		$where = "1";
		$list = $this->db->select('a.admin_id,a.name,count(la.id) chkco,sum(la.conti_time) conti_t')
		->from('admin a')
		->leftjoin('log_admin la','la.admin_id = a.admin_id')
		->page($page+1,$size)
		->where($where)
		->group('a.admin_id')
		->order("$sortField $sortOrder")
		->getPage();
		foreach ($list['data'] as &$value) {
			$value['conti_t'] = empty($value['conti_t'])?0:$value['conti_t'];

		}
		$result=array('total'=>$list['count'],'data'=>$list['data']);
		$this->json_output($result);
	}
	/**
	 * 获取节点
	 * @return [type] [description]
	 */
	public function getNode($action){
		$title = $this->db->model('adm_node')->where("name = '{$action}' and ntype in(1,2)")->select('title')->group('name')->getOne();
		return empty($title)?'':$title;
	}
	/**
	 * 显示用户日志
	 */
	public function showUserLogs(){
		$admin_id =sget('admin_id','i');
		$action = sget('action','s');

		if($action == 'show'){
			$page = sget("pageIndex",'i',0); //页码
			$size = sget("pageSize",'i',20); //每页数
			$sortField = sget("sortField",'s','la.input_time'); //排序字段
			$sortOrder = sget("sortOrder",'s','desc'); //排序
			                                           //
			$where = " la.admin_id = $admin_id ";
			$sTime = sget("sTime",'s','la.input_time'); //搜索时间类型
			$where.= getTimeFilter($sTime); //时间筛选

			$list = $this->db->select('la.*,a.name')
				->from('log_admin la')
				->leftjoin('admin a','a.admin_id = la.admin_id')
				->where($where)
				->page($page+1,$size)
				->order("$sortField $sortOrder")
				->getPage();
			foreach ($list['data'] as &$value) {
				$value['input_time']=$value['input_time']>1000 ? date("Y-m-d H:i:s",$value['input_time']) : '-';
				$value['title'] = $this->getNode($value['action']);
				if(preg_match('/^(pageIndex)/',$value['remark'],$matches)){
					$value['acname'] = '翻页操作';
				}elseif (preg_match('/^(startTime)|(sTime)|(status)/',$value['remark'],$matches)) {
					$value['acname'] = '搜索操作';
				}elseif (preg_match('/^(do=search)/',$value['remark'],$matches)) {
					$value['acname'] = '查看操作';
				}else{
					$value['acname'] = '';
				}
			}
			$result=array('total'=>$list['count'],'data'=>$list['data']);
			$this->json_output($result);
			}
		$this->assign('page_title','用户操作记录');
		$this->assign('admin_id',$admin_id);
		$this->display('admin_log_detail.html');
	}
}