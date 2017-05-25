<?php
/**
 * 附件 
 */
class attachmentModel extends model{
	public function __construct() {
		parent::__construct(C('db_default'), 'attachment');
	}
	
	/*
	 * 获取所有附件信息
	 * @access public
     * @return array
	 */
	public function getAtt(){
		$arr=$this->select('*')->getAll();
		return $arr;
	}
	
	public function addAtt($data){
		return $this->model('attachment')->add($data);
	}
}
?>