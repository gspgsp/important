<?php
	class testAction extends homeBaseAction{
		protected $db;
		public function __init(){
			$this->db=M('public:common');
		}
		// public function test(){
		// 	set_time_limit(0);
		// 	$infos = $this->db->model('ouser')->getAll();
		// 	foreach ($infos as $k => $v) {
		// 		$aid =  $this->db->model('lib_region')->select('id')->where("name like '{$v['s_county']}'")->getOne();
		// 		$this->db->model('ouser')->where("user_id = {$v['user_id']}")->update(array('s_co'=>$aid));
		// 	}
		
		// }
		public function update(){
			set_time_limit(0);
			$info = $this->db->model('info')->getAll();
			foreach ($info as $k => $v) {
				$content = preg_replace('/height=\"[\d]*?\"/','',$v['content']);
				$this->db->model('info')->where("id = {$v['id']}")->update(array('content'=>$content));
			}
		}
}