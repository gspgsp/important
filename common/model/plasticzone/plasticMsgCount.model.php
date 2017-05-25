<?php
/**
*消息数量-gsp
*/
class plasticMsgCountModel extends model
{

	public function __construct() {
		parent::__construct(C('db_default'), 'weixin_plasticzone');
	}
	public function getMsgCount($user_id,$type){
		$where = " is_read = 0 ";
		switch ($type) {
			case 1:
				$model = "weixin_plasticzone";
				$where .= " and send_id = $user_id ";
				return $this->_getModelData($model,$where);
			case 2:
				$model = "weixin_msg";
				$where .= " and user_id = $user_id ";
				return $this->_getModelData($model,$where);
		}
	}
	private function _getModelData($model,$where){
		return count($this->model($model)->where($where)->getAll());
	}
}