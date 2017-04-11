<?php
/**
 * 我的求购信息--黎贤
 */
class mypurchaseAction extends userBaseAction{


	protected $db;
	public function __init()
	{
		$this->db=M('public:common');
	}

	public function init()
	{
		$this->act='mypurchase';
		$this->name="求购发布";
		$this->type=1;
		
		$this->product_type=L('product_type');//产品类型
		$this->period=L('period');//期货周期
		$this->process_level=L('process_level');//加工级别
		$this->area=M('system:region')->get_regions(1);//地区
		$this->display('mypurchase');
	}

	// 我的采购列表
	public function lists()
	{
		$this->act="purchaselist";
		$this->type=1;
		$this->name="求购管理";

		if($id=$_SESSION['msg_pid']){
			$_SESSION['msg_pid']=null;
			$size=10;
			$this->db->query('set @mytemp = 0');
			$result=$this->db->query("select nu from (select (@mytemp:=@mytemp+1) as nu,id from p2p_purchase where user_id=$this->user_id and type=$this->type order by input_time desc) as A where A.id=$id");
			$row=mysql_fetch_assoc($result);
			$this->page=ceil($row['nu']/$size);
			if(empty($row)) $id=0;
			$this->assign('id',$id);
		}

		$this->product_type=L('product_type');//产品类型
		$this->shelve_type=L('shelve_type');//上下架状态
		$this->cargo_type=L('cargo_type');//现货期货
		$this->display('mypurchase.list');
	}


	//下架操作
	public function offshelf(){
		if($_POST)
		{
			$this->is_ajax=true;
			if(!$ids=sget('ids')) $this->error('信息不存在');
			$this->db->model('purchase')
			->where("user_id=$this->user_id and id in (".implode(',',$ids).")")
			->update(array('shelve_type'=>2,'update_time'=>CORE_TIME));
			$this->success('操作成功');
		}
	}

	//重新上架
	public function reshelf()
	{
		$this->is_ajax=true;
		$type=sget('type','i');

		if($data=$_POST['data'])
		{
			$this->db->startTrans();//开启事务
			try {
				$model=$this->db->model('purchase');
				foreach ($data as $key => $value) {
					if($value['on']){
						$_data=$model->getPk($value['on']);
						$_data['supply_count']=0;
						$_data['last_buy_sale']=0;
						$_data['input_time']=CORE_TIME;
						$_data['update_time']=CORE_TIME;
						$_data['shelve_type']=1;
						$_data['number']=$value['num'];
						$_data['unit_price']=$value['price'];
						$_data['status']=$type==1?1:2;//报价直接审核通过，采购需要后台审核(1:采购 2:报价)
						$model->where("id=".$_data['id'] ." and user_id=$this->user_id")->update($_data);
					}
				}
			} catch (Exception $e) {
				$this->db->rollback();
				$this->error($e->getMessage());
			}
			$this->db->commit();
			$this->success('操作成功');
		}else{
			$this->error('信息不存在');
		}
	}

	//通过牌号 获取加工级别
	public function getLevel()
	{
		if($_POST)
		{
			$this->is_ajax=true;
			$model=sget('model','s','');
			$process_type=$this->db->model('product')
				->where("model='{$model}'")
				->select('process_type')
				->getOne();
			if(empty($process_type)) $this->error('无数据');
			$_data=array(
				'id'=>$process_type,
				'name'=>setOption('process_level',$process_type),
			);
			$this->success($_data);
		}
	}

	//采购发布(报价发布)
	public function pub()
	{
		if($_SESSION['userid']<1) $this->error('您还未登录，请登录后从试该操作');
		if($data=$_POST['data'])
		{	
			$this->is_ajax=true;
			$cargo_type=sget('cargo_type','i',1);     //现货、期货
			$type=sget('type','i',1);                 //采购1、报价2
			$pur_model=M('product:purchase');
			$fac_model=M('product:factory');
			$pro_model=M('product:product');
			$data=saddslashes($data);
			foreach ($data as $key => $value) {
				if($value['number']==0) $this->error('发布数量不能为0!');
				if($value['price']==0) $this->error('发布价格不能为0!');
				if($value['product_type']==null || $value['product_type']==0) $this->error('该品种不可以,请重新选择');
				if($value['process_level']==null || $value['process_level']==0) $this->error('该加工级别不可以,请重新选择');
				$_data=array(
					'user_id'=>$this->user_id,                                 //用户id
					'c_id'=>$_SESSION['uinfo']['c_id'],                        //客户id
					'customer_manager'=>$_SESSION['uinfo']['customer_manager'],//交易员
					'number'=>$value['number'],                                //吨数
					'unit_price'=>$value['price'],                             //单价
					'provinces'=>$value['provinces'],                          //省份id
					'origin'=>$value['provinces'].'|'.$value['provinces'],     //后台显示交货地用
					'store_house'=>$value['store_house'],                      //仓库(交货地)
					'cargo_type'=>$cargo_type,                                 //现货期货
					'period'=>$value['period'],                                //期货周期
					'bargain'=>$value['bargain'],                              //是否实价
					'type'=>$type,                                             //采购、报价
					'status'=>$type==1?1:2,                                    //状态，报价不需要审核，采购需要审核
					'input_time'=>CORE_TIME,                                   //创建时间
				);

					$pur_model->startTrans();
					try {
						$_product=array(
							'model'=>$value['model'],                   //牌号
							'product_type'=>$value['product_type'],     //产品类型
							'process_type'=>$value['process_level'],    //加工级别
							'f_id'=>$value['f_name'],                   //厂家id
							'input_time'=>CORE_TIME,                    //创建时间
							'status'=>1,                                //审核状态
						);
						if(!$pro_model->add($_product)) throw new Exception("系统错误 pubpur:102");
						$pid=$pro_model->getLastID();
						$_data['p_id']=$pid;
						if(!$pur_model->add($_data)) throw new Exception("系统错误 pubpur:103");
					} catch (Exception $e) {
						$pur_model->rollback();
						$this->error($e->getMessage());
					}
					$pur_model->commit();
			}
			$this->success('提交成功');
		}
	}

	public function tables()
	{
		$type=sget('type','i',1);//1 采购 2报价
		$this->assign('type',$type);
		$where="pur.user_id=$this->user_id and type=$type";

		if($keyword=sget('keyword','s','')){
			$where.=" and (pro.model='{$keyword}' or fa.f_name='{$keyword}')";
		}
		if($product_type=sget('product_type','i',0)){
			$where.=" and pro.product_type={$product_type}";
		}
		if($shelve_type=sget('shelve_type','i',0)){
			$where.=" and pur.shelve_type={$shelve_type}";
		}
		if($cargo_type=sget('cargo_type','i',0)){
			$where.=" and pur.cargo_type={$cargo_type}";
		}

		$page=sget('page','i',1);
		$size=10;
		$list=M('product:purchase')->getPurPage($where,$page,$size);
		foreach($list['data'] as $key=>$v ){
			$info=$this->db->from('purchase pur')
				->join('sale_buy sb','pur.id=sb.p_id')
				->join('customer cus','sb.c_id=cus.c_id')
				->leftjoin('lib_region r','sb.delivery_place=r.id')
				->where("sb.p_id={$v['id']} and sb.status in(2,3,5)")
				->select('pur.last_buy_sale,sb.id,sb.number,sb.price,sb.delivery_date,sb.delivery_place,sb.ship_type,sb.input_time,sb.remark,cus.c_name,r.name as delivery_place')
				->getAll();

			$list['data'][$key]['counts']=count($info);
			$list['data'][$key]['city']= (!empty($v['region_name']))?$v['region_name']:$v['store_house'];
		}

		$this->assign('list',$list);
		$this->assign('page',$page);
		$this->assign('count',ceil($list['count']/$size));
		$this->display('mypurchase.table');
	}


	// 获得我的洽谈、报价列表
	public function get_offers()
	{
		if($_POST){
			$id=sget('id','i',0);
			$page=sget('page','i',1);
			$size=10;
			$list=$this->db->from('purchase pur')
				->join('sale_buy sb','pur.id=sb.p_id')
				->join('customer cus','sb.c_id=cus.c_id')
				->join('customer_contact as con ','sb.c_user_id=con.user_id')
				->leftjoin('lib_region r','sb.delivery_place=r.id')
				->where("sb.p_id=$id and sb.status in(2,3,4)")
				->select('pur.last_buy_sale,sb.id,sb.number,sb.price,sb.status,sb.delivery_date,sb.delivery_place,sb.ship_type,sb.input_time,sb.remark,cus.c_name,r.name as delivery_place,con.name,con.mobile')
				->getAll();
			$this->assign('list',$list);
			$this->display('offerlist');
		}
	}

	// 选定报价
	public function selected()
	{
		if($_POST)
		{
			$this->is_ajax=true;
			$model=$this->db->model('sale_buy');
			$id=sget('id','i',0);//sale_buy的报价id
			$price=sget('price');//成交价格
			if( !$data=$model->where("id=$id")->getRow() ) $this->error('信息不存在');//根据id信息未找到
			$purModel=M('product:purchase');
			if( !$purData=$purModel->where("id={$data['p_id']} and user_id=$this->user_id")->getRow() ) $this->error('信息不存在');                           //报价表与用户不匹配
			if($purData['last_buy_sale']) $this->error('不能重复选定');
			$orderModel=M('product:unionOrder');
			$this->db->startTrans();
			$arrs=array(
				'price'=>$price,
				'update_time'=>CORE_TIME,
			);
				if(!$this->db->model('sale_buy')->where('id='.$id)->update($arrs)) $this->error('修改价格失败');
				$purModel->where("id={$data['p_id']} and user_id=$this->user_id")->update(array('last_buy_sale'=>$id,'update_time'=>CORE_TIME,'status'=>3));
				$orderData=array();
				$orderData['deal_price']=$price;                   //成交价格
				$orderData['total_price']=($price*$data['number']);//总金额
				$orderData['order_status']=2;                           // 订单状态  2、审核通过
				$info=$orderModel->where('p_sale_id='.$id)->update($orderData);
				if(!$info) $this->error('订单选中失败，请重试一次');
				$var=$orderModel->select('id')->where('p_sale_id='.$id)->getOne();
				$orderDetail=M('product:unionOrderDetail');
				$detail_data=array(
					'o_id'=>$var,
					'p_id'=>$purData['p_id'],
					'number'=>$data['number'],
					'unit_price'=>$orderData['deal_price'],
					'input_time'=>CORE_TIME,
				);

				if(!$orderDetail->where('o_id='.$var)->update($detail_data)) $this->error('订单明细更新失败，请重试');
				$model->where("p_id={$data['p_id']}")->update(array('status'=>8,'update_time'=>CORE_TIME));//更新其他报价为未选中
				$model->where("id=$id")->update(array('status'=>3,'update_time'=>CORE_TIME));//更新选中状态

				$modelName=$this->db->model('product')->where("id={$purData['p_id']}")->select('model')->getOne();
				$msg=L('msg_template.union_order');
				$msg=sprintf($msg,$modelName,$price,$var);
				M("system:sysMsg")->sendMsg($data['user_id'],$msg,5);//联营订单站内信

			if($this->db->model('sale_buy')->commit()){
				$this->success('操作成功');
			}else{
				$this->db->rollback();
				$this->error('操作失败');
			}
		}
	}

	/**
	 * 我的购货
	 *
	 */

	public function wantBuy(){

		$this->act='wantBuy';

		$this->display('wantbuy');
	}

	/**采购table
	 *
	 */
	public function buyTable(){

		$type=1;//采购
		$where="s.user_id=$this->user_id and pur.type=$type";
		//收索条件
		if($status=sget('status','s','')){
			$where.=" and s.status={$status} ";
		}
		//P($where);
		$page=sget('page','i',1);
		$size=10;
		$list=M('product:purchase')->getWantBuy($where,$page,$size);

		//p($list);die;
		$this->assign('list',$list);
		$this->assign('page',$page);
		$this->assign('count',ceil($list['count']/$size));
		$this->display('buytable');
	}



}