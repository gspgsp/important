<?php
/**
 * 产品信息管理
 */
class followAction extends adminBaseAction {
	public function __init(){
		$this->db=M('public:common')->model('follow_up');//客户跟进信息表
		$this->assign('follow_up_way',L('follow_up_way'));//跟进方式语言包
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
			if($id>0){
				$contracts = arrayKeyValues(M('user:customerContact')->getListByCid($id),'user_id','name');
				$this->assign('arr',$contracts);
			}
		
		$this->assign('c_id',$id);
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

		$c_id=sget('c_id','i',0);
		if($c_id !=0)  $where.=" and `c_id` =".$c_id;
		//关键词
		$key_type=sget('key_type','s','c_name');
		$keyword=sget('keyword','s');
		if(!empty($keyword)){
			if($key_type == 'c_name'){
				$c_id =M('user:customer')->getInfoByCname($key_type,$keyword);
				$str=implode(',',$c_id);
				$where.=" and `c_id` in ($str)";
			}elseif($key_type == 'name'){
				$user_id =M('user:customerContact')->getCidByName($keyword);
				$str=implode(',',$user_id);
				$where.=" and `user_id` in ($str)";
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
			$list['data'][$k]['c_name'] = M('user:customer')->getColByName($v['c_id']);
			$list['data'][$k]['name'] = M('user:customerContact')->getColByName($v['user_id']);
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
		$data = sdata(); //传递的参数
		if(empty($data)) $this->error('错误的请求');
    		$data['follow_time']=strtotime($data['follow_time']);
    		$data['next_follow_time']=strtotime($data['next_follow_time']);
		if($data['cid'] > 0) $data=(array('c_id'=>$data['cid'],)+$data);
		$result = $this->db->add($data+array('input_time'=>CORE_TIME, 'input_admin'=>$_SESSION['name'],'customer_manager'=>$_SESSION['adminid'],));
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
	 * 根据公司获取联系人的信息，勿删
	 * @access public
	 */
	public function get_my_contact(){
		$this->is_ajax=true;
		$c_id=sget('c_id','i');
		$contact=M('user:customerContact')->getMyListByCid($c_id);
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
		$result=$this->db->where("id in ($ids)")->delete();
		if($result){
			$cache=cache::startMemcache();
			$cache->delete('follow_up_way');
			$this->success('操作成功');
		}else{
			$this->error('删除操作失败');
		}
	}


}