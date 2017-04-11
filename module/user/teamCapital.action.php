<?php
/**
 * 销售战队每月所用金额配比
 */
class teamCapitalAction extends adminBaseAction {
	public function __init(){
		$this->assign('team',L('team')+array('1'=>'其他')); //战队名称
		$this->db=M('public:common')->model('team_capital');//战队配资表
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
		$this->assign('page_title','客户强开规则列表');
		$this->display('teamCapital.list.html');
	}
	//添加规则列表
	public function info(){
		$this->assign('action','add');
		$this->display('teamCapital.add.html');
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

		$where=" 1 ";
		$list=$this->db->where($where)
					->page($page+1,$size)
					->order("$sortField $sortOrder")
					->getPage();
		foreach($list['data'] as $k=>$val){
				//请求时间
				$list['data'][$k]['input_time']=$val['input_time']>1000 ? date("Y-m-d H:i:s",$val['input_time']) : '-';
				$list['data'][$k]['input_date']=$val['input_date']>1000 ? date("Y-m",$val['input_date']) : '-';
		}
		$result=array('total'=>$list['count'],'data'=>$list['data']);
		$this->json_output($result);
	}
	/**
	 * 添加战队配资
	 * @access public
	 */
	public function ajaxSave(){
		$this->is_ajax=true; //指定为Ajax输出
		$data = sdata(); //传递的参数
		if(empty($data)) $this->error('错误的请求');
		unset($data['id']);
		$input_date = date("Y-m",strtotime($data['input_date']));
		$data['input_date'] = strtotime($input_date);
		$last_month = getLastMonthStartTime(date("m",$data['input_date']));
		//添加判断 当前战队本月是否已经设置，如设置过，则不能再设置
		$find_team = $this->db->model('team_capital')->select('team_id')->where('team_id = '.$data['team_id'].' and input_date = '.$data['input_date'])->getOne();
		if($find_team){
			$this->error('添加失败,本月该战队数据已设置过');
		}
		//获取战队名称
		if($data['team_id'] == 1){
			$data['name'] = '其他';
		}else{
			$team_name = $this->db->model('adm_role')->select('name')->where('id='.$data['team_id'])->getOne();
			$data['name'] = $team_name;
		}
		//继承上月额度，查询上月结业可用余额，然后添加到当月,如果上月没指标，
		$last_team_data= $this->db->model('team_capital')->select('id,total_money,used_money,available_money')->where('team_id = '.$data['team_id'].' and input_date = '.$last_month)->getRow();
		if($last_team_data['id']){
			$now_available_money = $data['total_money']-$last_team_data['total_money']+$last_team_data['available_money'];
			$now_used_money = $last_team_data['used_money'];
		}else{
			$now_available_money = $data['total_money'];
			$now_used_money = 0;
		}
		$result = $this->db->model('team_capital')->add($data+array('available_money'=>$now_available_money,'used_money'=>$now_used_money,'input_time'=>CORE_TIME, 'input_admin'=>$_SESSION['name']));
		if(!$result) $this->error('添加失败');
		$this->success('操作成功');
	}
	public function update(){
		$id = sget('id','i');
		if(empty($id)) $this->error('错误的请求');
		$info = $this->db->where('id = '.$id)->getRow();
		$info['input_date'] = date('Y-m',$info['input_date']);
		$this->assign('info',$info);
		$this->assign('action','update');
		$this->display('teamCapital.add.html');
	}
		/**
	 * 修改战队配资
	 * @access public
	 */
	public function updateSave(){
		$this->is_ajax=true; //指定为Ajax输出
		$data = sdata(); //传递的参数
		if(empty($data)) $this->error('错误的请求');
		$input_date = date("Y-m",strtotime($data['input_date']));
		$data['input_date'] = strtotime($input_date);
		//更新数据时，检查当前战队+所选月份，是否有重复
		$find_team = $this->db->model('team_capital')->select('team_id')->where('team_id = '.$data['team_id'].' and input_date = '.$data['input_date'].' and id <>'.$data['id'])->getOne();
		if($find_team){
			$this->error('修改失败,本月该战队数据已设置过');
		}
		if($data['team_id'] == 1){
			$data['name'] = '其他';
		}else{
			$team_name = $this->db->model('adm_role')->select('name')->where('id='.$data['team_id'])->getOne();
			$data['name'] = $team_name;
		}
		$used_money = $this->db->model('team_capital')->select('used_money')->where('id = '.$data['id'])->getOne();
		$data['available_money'] = $data['total_money'] - $used_money;
		$res = $this->db->model('team_capital')->where('id = '.$data['id'])->update($data+array('input_time'=>CORE_TIME, 'input_admin'=>$_SESSION['name']));
		if(!$res) $this->error('操作失败');
		$this->success('操作成功');
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
		//如果该战队配额已用，则不能删除
		$res = $this->db->model('team_capital')
				->select('used_money')->where("id=".$ids)->getOne();
		if($res !='0.00'){
			$this->error('删除失败，该战队所配资金已使用过');
		}
		$result=$this->db->model('team_capital')->where("id in ($ids)")->delete();
		if($result){
			$this->success('操作成功');
		}else{
			$this->error('删除操作失败');
		}
	}
}