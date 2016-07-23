<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2016/6/27
 * Time: 11:47
 */

class concernedProductModel extends Model{
	public function __construct() {
		parent::__construct(C('db_default'), 'concerned_product');
	}

	/**关注列表
	 * 根据user_id查询用户关注信息
	 * @param $where
	 */
	public function getConcernedList($uid=0,$status=1){
		return  $this->from('concerned_product')->where("user_id = $uid and status = $status")->select('id, product_id,model,factory_name')->getAll();

	}


}