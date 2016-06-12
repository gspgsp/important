<?php
/**
*财务中心-我的积分
*/
class myPointsModel extends model
{
	public function __construct() {
		parent::__construct(C('db_default'), 'points_bill');
	}
	//分页获取对应用户的积分明细
	public function getCreditDetail($user_id,$page,$size,$sortField='addtime',$sortOrder='desc'){
		$list = $this->model('points_bill')->select('id,points,type,addtime')->where('uid='.$user_id)
			->page($page,$size)
			->order("$sortField $sortOrder")
			->getPage();
		foreach ($list['data'] as $key => $value) {
            $list['data'][$key]['addtime'] = $value['addtime']>1000 ? date("Y-m-d H:i:s",$value['addtime']):'-';
            $list['data'][$key]['type'] = L('cre_detail_type')[$value['type']];
        }
		return $list;
	}
	//分页获取对应用户的兑换记录
	public function getCreditRecord($user_id,$page,$size,$sortField='create_time',$sortOrder='desc'){
		$list = $this->model('points_order')->select('order_id,create_time,status,update_time,usepoints,uid,goods_id')->where('uid='.$user_id)
			->page($page,$size)
			->order("$sortField $sortOrder")
			->getPage();
		$pages = pages($list['count'], $page, $size);
		//取出对应的goods
		foreach ($list['data'] as $value2) {
			$goods = $this->model('points_goods')->select('thumb,name')->where('id='.$value2['goods_id'])->getRow();
			$tData['order_id'] = $value2['order_id'];
			$tData['create_time'] = $value2['create_time'];
			$tData['status'] = $value2['status'];
			$tData['update_time'] = $value2['update_time'];
			$tData['usepoints'] = $value2['usepoints'];
			$tData['uid'] = $value2['uid'];
			$tData['thumb'] = $goods['thumb'];
			$tData['name'] = $goods['name'];
			$pushData[] = $tData;
			unset($tData);
		}
		return array('pages'=>$pages,'pushData'=>$pushData);
	}
	//分页获取条件查询结果
	public function getCreditDetailByOp($user_id,$optionVal,$page,$size,$sortField='addtime',$sortOrder='desc'){
		if($optionVal == 2){//等于2的时候获取支出(type=5)的积分
			$list = $this->model('points_bill')->select('id,points,type,addtime')->where("uid=$user_id and type=5")
			->page($page,$size)
			->order("$sortField $sortOrder")
			->getPage();
			foreach ($list['data'] as $key => $value) {
	            $list['data'][$key]['addtime'] = $value['addtime']>1000 ? date("Y-m-d H:i:s",$value['addtime']):'-';
	            $list['data'][$key]['type'] = L('cre_detail_type')[$value['type']];
        	}
			return $list;
		}elseif($optionVal == 1){//获取积分
			$list = $this->model('points_bill')->select('id,points,type,addtime')->where("uid=$user_id and type!=5")
			->page($page,$size)
			->order("$sortField $sortOrder")
			->getPage();
			foreach ($list['data'] as $key => $value) {
	            $list['data'][$key]['addtime'] = $value['addtime']>1000 ? date("Y-m-d H:i:s",$value['addtime']):'-';
	            $list['data'][$key]['type'] = L('cre_detail_type')[$value['type']];
        	}
			return $list;
		}
	}
}