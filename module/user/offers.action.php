<?php
/**
 * 报价发布
 */
class offersAction extends homeBaseAction{

	protected $db;
	public function __init()
	{
		$this->db=M('public:common');
	}
	public function init()
	{
		$this->display('offers');
	}
}