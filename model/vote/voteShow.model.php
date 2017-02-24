<?php
/**
*投票人 团队投票状态模型,
*@author gsp <[<email address>]>
*/
class voteShowModel extends Model
{
	/**
	 * 初始化数据模型
	 */
	public function __construct() {
		parent::__construct(C('db_default'), 'vote_show');
	}
	/**
	 * 获取第二屏数据
	 * @author gsp <[<email address>]>
	 * @return [type] [description]
	 */
	public function getSecondData(){
		$result = $this->select("id,show_name,score")->getAll();
		foreach ($result as &$value) {
			$value['show_name'] = L('show_name')[$value['id']];
		}
		return $result;
	}
}