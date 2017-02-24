<?php
/**
*投票人 被投票状态模型,
*@author gsp <[<email address>]>
*/
class voteByStatusModel extends Model
{
	/**
	 * 初始化数据模型
	 */
	public function __construct() {
		parent::__construct(C('db_default'), 'vote_bystatus');
	}
	/**
	 * 获取被投票数
	 * @return [type] [description]
	 */
	public function getVoteByStatus($userid){
		return $this->where("userid = $userid")->getRow();
	}
}