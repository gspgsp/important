<?php 

class physicalModel extends model{
	public function __construct() {
		parent::__construct(C('db_default'), 'physical');
	}

	public function get_search_list($lids){
		$lids = implode(',', $lids);
		return $this->where("lid in ($lids)")->select('lid,type,name,company')->getAll();
	}
}


 ?>