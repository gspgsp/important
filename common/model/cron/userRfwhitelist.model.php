<?php
/**
 * 推荐用户白名单
 */
class userRfwhitelistModel extends model{
	public function __construct() {
		parent::__construct(C('db_default'), 'user_rfwhitelist');
	}

	/**
	 * 根据推荐人ID，限额，取已奖励的数据
	 * @param int $item_id 项目ID
	 * @param array  $data   项目资料
	 * @return bool
	 */
	public function getRfwhiteByUser($user_id=0,$limit_amount=0){
		$where = array('AND');
		if($user_id){
			$where[] = 'user_id='.intval($user_id);
		}

		if($limit_amount > 0){
			$where[] = 'reward_account<'.intval($limit_amount);
		}

		return $this->select('user_id')->where($where)->getOne();
	}
	
	/**
	 * 根据用户ID取奖励的数据
	 * @param int $item_id 项目ID
	 * @param array  $data   项目资料
	 * @return array
	 */
	public function getRfwhiteByUserId($user_id=0){
		$where = array('AND');
		if($user_id){
			$where[] = 'user_id='.intval($user_id);
			return $this->select('user_id,reward_num')->where($where)->getRow();
		}else{
			return false;
		}
	}

	/**
	 * 更新推荐人已奖励的数据
	 * @param int $item_id 项目ID
	 * @param array  $data   项目资料
	 * @return bool
	 */
	public function setRfwhitelist($user_id,array $data){
		return $this->model('user_rfwhitelist')->where('user_id='.intval($user_id))->update($data);
	}
	
	/**
	 * 添加推荐人到推荐白名单
	 * @param int $item_id 项目ID
	 * @param array  $data   推荐人
	 * @return bool
	 */
	public function addRfwhitelist(array $data){
		$result = $this->model('user_rfwhitelist')->add($data);
		if($result){
			return true;
		}else{
			return false;	
		}
	}

}