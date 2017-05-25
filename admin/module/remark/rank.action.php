<?php
/** 
 * 员工投票结果
 * @author gsp <[<email address>]>
 */
class rankAction extends adminBaseAction {
	public function __init(){
		$this->debug = false;
		$this->db=M('public:common')->model('vote_bystatus');
	}

	/**
	 * 节目投票列表
	 * @access public
	 * @return
	 */
	public function index(){
		//
		$shows = $this->db->model('vote_show')->select('id,show_name,score')->getAll();
		foreach ($shows as $key => $value) {
				$name[] = L('show_name')[$value['show_name']];
				// $name[] = mb_substr(L('show_name')[$value['show_name']],0,3,'utf-8');
				$v_score[] = $value['score'];
		}
		$this->assign('name',json_encode($name));
		$this->assign('v_score',json_encode($v_score));
		$this->display('index');
	}
}


