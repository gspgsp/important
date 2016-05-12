<?php
/**
 * 产品信息管理
 */
class followAction extends adminBaseAction {
	public function __init(){
		//客户跟进信息表
		$this->db=M('public:common')->model('follow_up');
		//跟进方式语言包
		$this->assign('follow_up_way',L('follow_up_way'));	
	}
	/**
	 *
	 * @access public
	 * @return html
	 */
	public function init(){
		$action=sget('action','s');
		if($action=='grid'){ //获取列表
			$this->_grid();exit;
		}
		$this->assign('page_title','客户跟进信息列表');
		$this->display('follow.list.html');
	}

	public function info(){
		$id=sget('id','i');
		$c_name =M('user:customer')->getColByName($value=$id,$col='c_name',$condition='c_id');
		$mes   =M('user:customerContact')->getListByCid($id);
		foreach ($mes as $key => $value) {
					$arr[$value[user_id]]=$value[name];
				}
		$this->assign('arr',$arr);
		$this->assign('c_name',$c_name);
		$this->assign('cid',$id);
		$this->display('follow.add.html');
	}

	/**
	 *
	 * @access private
	 * @return html
	 */
	private function _grid(){
		$page = sget("pageIndex",'i',0); //页码
		$size = sget("pageSize",'i',20); //每页数
		$sortField = sget("sortField",'s','input_time'); //排序字段
		$sortOrder = sget("sortOrder",'s','desc'); //排序
		//筛选显示类别
		$where=" 1 ";
		//关键词
		$key_type=sget('key_type','s','c_name');
		$keyword=sget('keyword','s');
		if(!empty($keyword)){
			if($key_type == 'c_name'){
				$c_id =M('user:customer')->getInfoByCname($condition='c_id',$key_type,$keyword);
				foreach ($c_id as $key => $value) {
					$arr[]=$value[c_id];
				}
				$arr=implode(',',$arr);
				$where.=" and `c_id` in ({$arr})";
			}else{
				$where.=" and $key_type like '%$keyword%'";
			}
		}
		$list=$this->db->where($where)
					->page($page+1,$size)
					->order("$sortField $sortOrder")
					->getPage();
		foreach($list['data'] as $k=>$v){
			$list['data'][$k]['follow_time']=$v['follow_time']>1000 ? date("Y-m-d H:i:s",$v['follow_time']) : '-';
			$list['data'][$k]['next_follow_time']=$v['next_follow_time']>1000 ? date("Y-m-d H:i:s",$v['next_follow_time']) : '-';
			$list['data'][$k]['input_time']=$v['input_time']>1000 ? date("Y-m-d H:i:s",$v['input_time']) : '-';
			$list['data'][$k]['update_time']=$v['update_time']>1000 ? date("Y-m-d H:i:s",$v['update_time']) : '-';
			$list['data'][$k]['follow_up_way'] = L('follow_up_way')[$v['follow_up_way']]; 
			$list['data'][$k]['c_name'] = M('user:customer')->getColByName($value=$list['data'][$k]['c_id'],$col='c_name',$condition='c_id');
		}
		$result=array('total'=>$list['count'],'data'=>$list['data']);
		$this->json_output($result);
	}
	/**
	 * 添加跟进信息
	 * @access public
	 */
	public function ajaxSave(){
		$this->is_ajax=true; //指定为Ajax输出
		$action = sget('action','s');
		$arr = sdata(); //传递的参数
		if(empty($arr)) $this->error('错误的请求');
		//根据联系人id查出联系人信息
		$m=M('user:customerContact')->getListByUserid($arr['name']);
		$data=array(
    		'user_id'=>$arr['name'],
    		'name'=>$m['name'],
    		'customer_manager'=>$_SESSION['adminid'],
    		'follow_time'=>strtotime($arr['follow_time']),
    		'follow_up_way'=>$arr['follow_up_way'],
    		'remark'=>$arr['remark'],
    		'next_follow_time'=>strtotime($arr['next_follow_time']),
    		);
		if(empty($arr[cid])){
			$data=($data+array('c_id'=>$arr['c_name'],));
		}else{
			$data=($data+array('c_id'=>$arr[cid],));
		}
		$result = $this->db->add($data+array('input_time'=>CORE_TIME, 'input_admin'=>$_SESSION['username'],));
		if(!$result) $this->error('操作失败');
		$this->success('操作成功');
	}
	
	/**
	 * 获取联系人信息
	 * @access public
	 */
	public function get_contact_list(){
		$this->is_ajax=true;
		$c_id=sget('c_id','i');
		$contact=M('user:customerContact')->getListByCid($c_id);
		$this->json_output($contact);
	}
	/**
	 * 删除数据
	 * @access public
	 */
	public function remove(){
		$this->is_ajax=true; //指定为Ajax输出
		$ids=sget('ids','s');
		if(empty($ids)){
			$this->error('操作有误');
		}
		$ids=explode(',', $ids);
		foreach ($ids as $k => $v) {
			$result=$this->db->where("id = ($v)")->delete();
		}
		if($result){
			$cache=cache::startMemcache();
			$cache->delete('follow_up_way');
			$this->success('操作成功');
		}else{
			$this->error('删除操作失败');
		}
	}


}