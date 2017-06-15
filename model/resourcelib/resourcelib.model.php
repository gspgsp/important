<?php
/**
 * 资源库信息
 */
class resourcelibModel extends model{
	public function __construct() {
		parent::__construct(C('db_default'), 'resourcelib');
	}


	public function getList($page=0, $pageSize=0, $type='')
	{
		$where = "";
		if($type!==''){
			$where = "type=$type";
		}else{
			$where = "type in (0,1)";
		}
		$pages = $page*$pageSize;
		$ids = $this->select('id')->where($where)->order('input_time desc')->limit("$pages, $pageSize")->getCol();
		$ids = implode(',', $ids);
		$where = "id in ($ids)";
		return $this->where($where)->order('input_time desc')->getAll();
	}


	public function getSearch($ids)
	{
		$ids = implode(',' ,$ids);
		return $this->where(" id in ($ids)")->order('input_time desc')->getAll();
	}


	/**
	 * 获取今日报价发布总数
	 * @return mixed
	 */
	public function getTotalOne($date1){
		return  $this->from('resourcelib as res')->where("res.type=1 and res.input_time between  $date1 and ".time()." and res.status=0")
			->select('count(res.id) as total')
			->getAll();
	}
	/**
	 * 获取今日求购发布总数
	 * @return mixed
	 */

	public function getTotalTow($date1){
		return 	$this->from('resourcelib as res')->where("res.type=0 and res.input_time between  $date1 and ".time()." and res.status=0")
			->select('count(res.id) as total')
			->getAll();
	}

	/**
	 *获取最新发布一条的资源信息
	 *
	 */

	public function getNew(){
		return $this->from('resourcelib as res')
			->where('res.status=0')
			->order('res.input_time desc')
			->select('res.user_nick,res.content as contents')
			->limit('1')
			->getAll();
	}

	/**
	 *公司门户网站-资源库
	 *
	 */
	public function getResource($page=0, $pageSize=0, $type='',$userId){
		$where = "uid={$userId} ";
		if($type!==''){
			$where.= " AND type=$type";
		}else{
			$where.= " AND type in (0,1)";
		}
		$pages = $page*$pageSize;
		$ids = $this->select('id')->where($where)->order('input_time desc')->limit("$pages, $pageSize")->getCol();
		$ids = implode(',', $ids);
		$where = "id in ($ids)";
		return $this->where($where)->order('input_time desc')->getAll();
	}
	/**
	*资源库-根据牌号搜索
	*/
	public function getSearchResource($place,$province,$PlsticNumber,$start=0,$limit=0,$Time,$ISForward){
		
		$where_gi = "";$where_qi = " qi.Isbuy=0";
		if(!empty($PlsticNumber)){
			$where_gi .= " gi.PlsticNumber = '".$PlsticNumber."'";
			$where_qi .= " AND qi.PlsticNumber = '".$PlsticNumber."'";
			
		}
		$where_qi .= " AND qi.UpdateTime > '".$Time."'";
		
		if(!empty($PlsticNumber) && !empty($province)){
			$where_gi .= " AND";
		}else{
			$where_gi .= "";
		}
		if(!empty($province)){
			$where_gi .= " gi.GoodssPosition in (".$province.")";
		}
		if((!empty($province) || !empty($PlsticNumber)) && !empty($place)){
			$where_gi .=" AND";
		}else{
			$where_gi .= "";
		}
		if(!empty($place)){
			$num = count($place);
			$where_gi .= " ( gi.Production like '%$place[0]%'";
			$where = $where_gi;
			if($num > 1){
				for($i=1;$i<$num;$i++){
					$where_gi .= " OR gi.Production like '%$place[$i]%'";
				}
			}
			$where_gi .= " )";
		}
		if((!empty($place) || !empty($province) || !empty($PlsticNumber)) && !empty($ISForward)){
			$where_gi .= " AND gi.ISForward = '".$ISForward."'";
		}else{
			$where_gi .= "";
		}		
		$goods_company['resource'] = $this->from('goods_information as gi')
							->select('gi.Company,gi.GoodssPosition,gi.PlsticNumber,gi.ISForward,gi.Price,gi.Production')
							->where($where_gi)->group('gi.Company')->limit("$start,$limit")->getAll();
		$gi_company = array();
		if(!empty($goods_company['resource'])){
			foreach($goods_company['resource'] as $key=>$val){
				$arr = array();
				$position = $this->from('goods_information')->select('GoodssPosition')->where("Company='".trim($val['Company'])."'")->getAll();//公司牌号所有的地区
				
				$arr = array_unique(array_column($position,'GoodssPosition'));
				$goods_company['resource'][$key]['GoodssPosition'] = $arr;
				$gi_company[$key] = "'".$val['Company']."'";
			}
			$where_qi .= " AND qi.Company in (".implode(',',$gi_company).")";
		}
		
		$qi_company = $this->from('qq_information as qi')
							->select('qi.QQName,qi.QQNumber,qi.QQImage,qi.Company,qi.UpdateTime')
							->where($where_qi)->getAll();
				//showTrace();
		foreach($goods_company['resource'] as $key=>$val){
			$user_info = $this->user_info($val['Company']);
			if(!empty($user_info)){
				foreach($user_info as $k=>$v){
					$goods_company['resource'][$key]['Iphone_list'][] = trim($v['UserName']).' '.trim($v['Iphone']);
					$goods_company['resource'][$key]['Iphone'] = trim($v['Iphone']);
				}
			}
			
			foreach($qi_company as $k=>$v){
				if(trim($val['Company']) == trim($v['Company'])){
					$goods_company['resource'][$key]['QQName'][] = $v['QQName'];
					$goods_company['resource'][$key]['QQNumber'][] = $v['QQNumber'];
					$goods_company['resource'][$key]['QQImage'][] = $v['QQImage'];
					$goods_company['resource'][$key]['UpdateTime'] = $v['UpdateTime'];
				}
			}
		}
		//p($goods_company);die;
		$goods_company['total'] = $this->from('goods_information as gi')->select('count(*) as total')->where($where_gi)->getOne();
		return $goods_company;
	}
	public function user_info($Company){
		return $this->from('user_information')->select('UserName,Iphone')->where("Company='".trim($Company)."'")->getAll();
	}
}