<?php
/**
*  用户操作记录
 * @auth gsp
*/
class orderDoAction extends adminBaseAction
{
	/**
	 * 初始化方法
	 * @author gsp <[<email address>]>
	 * @return [type] [description]
	 */
	public function __init(){
		$this->debug = false;
		$this->db=M('public:common')->model('order');
	}
	public function init(){
		$action=sget('action','s');
		if($action=='grid'){ //获取列表
			$this->_grid();exit;
		}
		$this->assign('page_title','资源库列表');
		$this->display('order_log');
	}
		/**
	 * Ajax获取列表内容
	 * @access private
	 * @return html
	 */
	private function _grid(){
		$page = sget("pageIndex",'i',0); //页码
		$size = sget("pageSize",'i',20); //每页数
		$sortField = sget("sortField",'s','ord.input_time'); //排序字段
		$sortOrder = sget("sortOrder",'s','desc'); //排序

		$res = $this->db->model('log_admin')
		->select('concat_ws("/",remark,conti_time) rc')
		->where("action = '/application/order/info' and conti_time > 0")
		->getAll();
		// $remarks = array_column($res,'rc');
		$remarks = $this->get_column($res);
		$replaces = array('&o_type=1/','&o_type=2/','&o_type=undefined/');
		
		foreach ($remarks as $key => $value) {
			$n_value[] = str_replace($replaces,"/",$value);
		}
		foreach ($n_value as $k => $v) {
			if(preg_match('/^(oid=)(\d{1,}\/\d{1,})$/',$v,$matchs)){
				$ids[] = substr($matchs[0],4);
			}
		}
		$where = "1 and ord.order_sn !='' ";
		$keyword = sget("keyword",'s');
		if(!empty($keyword)) $where .=" and ord.order_sn like '%$keyword%'";
		$list = $this->db->select('ord.o_id,ord.order_sn')
		->from('order ord')
		->page($page+1,$size)
		->where($where)
		->order("$sortField $sortOrder")
		->getPage();
		$temp = array();
		// /application/order/info oid=33206&o_type=2 oid=32056&o_type=undefined
		foreach ($list['data'] as &$value) {
			foreach ($ids as $k => $v) {
				$o_id = split('/',$v);
				if($value['o_id'] == $o_id[0]){
					$value['conti_t'] += $o_id[1];
					$value['chkco'] += 1;
				}
			}
			if(sizeof($value) == 2){
				$value['conti_t'] = 0;
				$value['chkco'] = 0;
				$temp['data'][] = $value;
			}
		}
		//求差(去重)
		$arr1 = $list['data'];
		$arr2 = $temp['data'];
		$list['data'] = array_filter($arr1, function($v) use ($arr2) { return ! in_array($v, $arr2);});
		$list['count'] = count($arr1) - count($arr2);
		$arr3 = array();
		foreach ($list['data'] as $key => $value) {
			$arr3[] = $value;
		}
		//去重
		// function array_unique_fb($array2D){
		//     foreach ($array2D as $v){
		//         $v=join(',',$v);//降维,也可以用implode,将一维数组转换为用逗号连接的字符串
		//         $temp[]=$v;
		//     }
		//     $temp=array_unique($temp);//去掉重复的字符串,也就是重复的一维数组

		//     foreach ($temp as $k => $v){

		//         $temp[$k]=explode(',',$v);//再将拆开的数组重新组装

		//     }
		//     return $temp;
		// }
		// $arr3 = array_unique_fb($arr3);
		//
		// $temp = array();
		// foreach ($arr3 as $ke => $val) {
		// 	$temp[]['o_id'] = $val[0];
		// 	$temp[]['order_sn'] = $val[1];
		// 	$temp[]['chkco'] = $val[2];
		// 	$temp[]['conti_t'] = $val[3];
		// }
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
		$o_id =sget('o_id','i');
		$action = sget('action','s');

		if($action == 'show'){
			$page = sget("pageIndex",'i',0); //页码
			$size = sget("pageSize",'i',20); //每页数
			$sortField = sget("sortField",'s','la.input_time'); //排序字段
			$sortOrder = sget("sortOrder",'s','desc'); //排序

			$res = $this->db->model('log_admin')
				->select('id,remark')
				->where("action = '/application/order/info' and conti_time > 0")
				->getAll();
			foreach ($res as $key => $value) {
				if(preg_match('/^(oid=)(\d{1,})/',$value['remark'],$matchs)){
					if($o_id == intval($matchs[2])){
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
		$this->assign('o_id',$o_id);
		$this->display('order_log_detail.html');
	}
}