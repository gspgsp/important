<?php
/**
 * 积分表单
 */
class pointsBillModel extends Model{

	public function __construct() {
		parent::__construct(C('db_default'), 'points_bill');
	}


	// 减少积分 type 来源 5 积分兑换 
	public function decPoints($num=0, $uid=0, $type=0, $source=0,$gid=0){
		$user = M('public:common')->model('contact_info');
		$goods = M('points:pointsGoods');
		if( $info=$user->where("user_id=$uid")->getRow() ){
			if($source == 1){
				if(!$user->where("user_id=$uid")->update("quan_points=quan_points-$num")) return false;
				if(!$this->add( array('addtime' => time(), 'uid' => $uid, 'points' => -$num, 'type' => $type, 'gid' => $gid) )) return false;
				if(!$goods->where("id=$gid")->update("num=num-1")) return false;

			}elseif ($source == 0) {
				if(!$user->where("user_id=$uid")->update("points=points-$num")) return false;
				if(!$this->add( array('addtime' => time(), 'uid' => $uid, 'points' => -$num, 'type' => $type, 'gid' => $gid) )) return false;
			}
			return true;
		}else{
			return false;
		}
	}

	// 增加积分 type 来源 5 积分兑换 
	public function addPoints($num=0, $uid=0, $type=0,$source=0){
		$user = M('public:common')->model('contact_info');
		if( $info=$user->where("user_id=$uid")->getRow() ){
			if($source == 1){//塑料圈的积分
				if(!$user->where("user_id=$uid")->update("quan_points=quan_points+$num")) return false;
				if(!$this->add( array('addtime' => time(), 'uid' => $uid, 'points' => $num, 'type' => $type) )) return false;
				return true;
			}elseif ($source == 0) {//pc 或app
				if(!$user->where("user_id=$uid")->update("points=points+$num")) return false;
				if(!$this->add( array('addtime' => time(), 'uid' => $uid, 'points' => $num, 'type' => $type) )) return false;
				return true;
			}
		}else{
			return false;
		}
	}
	//获取用户总的积分
	public function getUerPoints($uid){
		$list = $this->model('contact_info')->select('quan_points')->where('user_id='.$uid)->getOne();
		return $list;
}
}