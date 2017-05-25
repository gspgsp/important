<?php
/**
*  投票人模型，只有中晨员工才有资格
*  @author gsp <[<email address>]>
*/
class contactModel extends Model
{
	/**
	 * 初始化数据模型
	 */
	public function __construct() {
		parent::__construct(C('db_default'), 'vote_contact');
	}
	/**
	 * 获取投票人
	 * @author gsp <[<email address>]>
	 * @return array [description]
	 */
	public function getVoteContact($value){
		if(gettype($value) == 'string'){
			$where = " mobile = $value ";
		}elseif (gettype($value) == 'integer') {
			$where = " id = $value ";
		}
		return $this->where($where)->getRow();
	}
	/**
	 * 获取第一屏数据
	 * @author gsp <[<email address>]>
	 * @return array [description]
	 */
	public function getFirstData(){
		$result = $this->select("id,name,ots_employee,bel_sh,sal_director,dep_meneger,bst_ncomer")->getAll();
		$data = array();
		foreach ($result as &$value) {
			$value['v_score'] = $this->getVoteScore($value['id'])['v_score'];//分
			$value['percent'] = $this->getVoteScore($value['id'])['percent'];//比例
			if($value['ots_employee'] == 1){
				$data['ots_employee'][] = $value;
			}elseif ($value['sal_director'] == 1) {
				$data['sal_director'][] = $value;
			}elseif ($value['dep_meneger'] == 1) {
				$data['dep_meneger'][] = $value;
			}elseif ($value['bst_ncomer'] == 1) {
				$data['bst_ncomer'][] = $value;
			}
		}
		//所有团队
		$data['bst_team'] = $this->getBeastTeam();
		return $data;
	}
	/**
	 * 获取最佳团队
	 * @author gsp <[<email address>]>
	 * @return [type] [description]
	 */
	public function getBeastTeam(){
		$result = $this->model('vote_team')->where("team_name !=1 ")->select("id,team_type,team_name,vote_count")->getAll();
		foreach ($result as &$value) {
			$value['percent'] = sprintf('%.2f',(float)$value['vote_count']/168);
		}
		return $result;
	}
	/**
	 * 获取用户分数
	 * @return [type] [description]
	 */
	public function getVoteScore($userid){
		$data = $this->model('vote_bystatus')->select("v_score")->where("userid = ".$userid)->getRow();
		$data['percent'] = sprintf('%.2f',(float)$data['v_score']/168);
		return $data;
	}
}