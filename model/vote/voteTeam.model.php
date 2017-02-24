<?php
/**
*投票人 团队投票状态模型,
*@author gsp <[<email address>]>
*/
class voteTeamModel extends Model
{
	/**
	 * 初始化数据模型
	 */
	public function __construct() {
		parent::__construct(C('db_default'), 'vote_team');
	}
}