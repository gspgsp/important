<?php
	class testAction extends homeBaseAction{
		protected $db;
		public function __init(){
			$this->db=M('public:common');
		}
		public function test(){
			set_time_limit(0);
			$infos = $this->db->model('admin')->select("admin_id,pid")->getAll();
			$node = $this->get_tree_child($infos,740);
			p($node);
		
		}


		private function get_tree_child($data, $fid) {
		    $result = array();
		    $result[]=$fid;
		    $fids = array($fid);
		    do {
		        $cids = array();
		        $flag = false;
		        foreach($fids as $fid) {
		            for($i = count($data) - 1; $i >=0 ; $i--) {
		                $node = $data[$i];
		                if($node['pid'] == $fid) {
		                    array_splice($data, $i , 1);
		                    $result[] = $node['admin_id'];
		                    $cids[] = $node['admin_id'];
		                    $flag = true;
		                }
		            }
		        }
		        $fids = $cids;
		    } while($flag === true);
		    return implode(',',$result);
		}


}