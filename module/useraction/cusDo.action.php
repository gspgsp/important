<?php
/**
*  用户操作记录
 * @auth gsp
*/
class cusDoAction extends adminBaseAction
{
	/**
	 * 初始化方法
	 * @author gsp <[<email address>]>
	 * @return [type] [description]
	 */
	public function __init(){
		$this->debug = false;
		$this->db=M('public:common')->model('customer');
	}
	public function init(){
		$action=sget('action','s');
		if($action=='grid'){ //获取列表
			$this->_grid();exit;
		}
		$this->assign('page_title','资源库列表');
		$this->display('cus_log');
	}
		/**
	 * Ajax获取列表内容
	 * @access private
	 * @return html
	 */
	private function _grid(){
		$page = sget("pageIndex",'i',0); //页码
		$size = sget("pageSize",'i',20); //每页数
		$sortField = sget("sortField",'s','c_id'); //排序字段
		$sortOrder = sget("sortOrder",'s','asc'); //排序

		$res = $this->db->model('log_admin')
		->select('concat_ws("/",remark,conti_time) rc')
		->where("action = '/user/customer/info' and conti_time > 0")
		->getAll();
		// $remarks = array_column($res,'rc');
		$remarks = $this->get_column($res);
		// $all_time = array_sum(array_column($res,'conti_time'));
		foreach ($remarks as $key => $value) {
			if(preg_match('/^(id=)(\d{1,}\/\d{1,})$/',$value,$matchs)){
				$ids[] = substr($matchs[0],3);
			}
		}
		// $ids_count = array_count_values($ids);
		// $ids = implode(',',$ids);
		$where = "1";
		$keyword = sget("keyword",'s');
		if(!empty($keyword)) $where .=" and cus.c_name like '%$keyword%' ";
		$list = $this->db->select('cus.c_id,cus.c_name')
		->from('customer cus')
		->page($page+1,$size)
		->where($where)
		->order("$sortField $sortOrder")
		->getPage();
		$temp = array();
		///user/customer/info id=50308
		foreach ($list['data'] as &$value) {
			foreach ($ids as $k => $v) {
				$c_id = split('/',$v);
				if($value['c_id'] == $c_id[0]){
					$value['conti_t'] += $c_id[1];
					$value['chkco'] += 1;
				}
			}
			if(sizeof($value) == 2){
				$value['conti_t'] = 0;
				$value['chkco'] = 0;
				$temp['data'][] = $value;
			}
		}
		//
		$arr1 = $list['data'];
		$arr2 = $temp['data'];
		$list['data'] = array_filter($arr1, function($v) use ($arr2) { return ! in_array($v, $arr2);});
		$list['count'] = count($arr1) - count($arr2);
		$arr3 = array();
		foreach ($list['data'] as $key => $value) {
			$arr3[] = $value;
		}
		//
		$result=array('total'=>$list['count'],'data'=>$arr3);
		$this->json_output($result);
	}
	/**
	 * 获取某一列的结果集
	 * @return [type] [description]
	 */
	public function get_column($arr){
		foreach ($arr as $key => $value) {
			$res[] = $value['rc'];
		}
		return $res;
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
		$c_id =sget('c_id','i');
		$action = sget('action','s');

		if($action == 'show'){
			$page = sget("pageIndex",'i',0); //页码
			$size = sget("pageSize",'i',20); //每页数
			$sortField = sget("sortField",'s','la.input_time'); //排序字段
			$sortOrder = sget("sortOrder",'s','desc'); //排序

			$res = $this->db->model('log_admin')
				->select('id,remark')
				->where("action = '/user/customer/info' and conti_time > 0")
				->getAll();
			foreach ($res as $key => $value) {
				if(preg_match('/^(id=)(\d{1,})$/',$value['remark'],$matchs)){
					if($c_id == intval(substr($matchs[0],3))){
						$ids[] = $value['id'];
					}
				}
			}

			$where = "la.id in (".implode(',',$ids).") and la.conti_time > 0";
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
		$this->assign('page_title','客户操作记录');
		$this->assign('c_id',$c_id);
		$this->display('cus_log_detail.html');
	}
}