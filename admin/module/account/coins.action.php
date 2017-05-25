<?php
class coinsAction extends adminBaseAction {	
	public function __init(){
		$this->db=M('public:common');
	}

	/**
	 * 红包列表
	 */
	public function init(){
		$rules = array();
		$rows = $this->db->model('ucoin_rules')->select('id,name,amount')->order('id desc')->getAll();
		foreach($rows as  $r){
			$rules[$r['id']] = "{$r['id']} [{$r['amount']}元] ".$r['name'];
		}
		$this->rules = $rules;
		$this->display('coins.lists');
	}

	/**
	 * 红包列表-获取数据
	 */
	public function lists(){
		$page = sget("pageIndex",'i',0); //页码
		$size = sget("pageSize",'i',20); //每页数
		$sortField = sget("sortField",'s','id'); //排序字段
		$sortOrder = sget("sortOrder",'s','desc'); //排序
		$keyword = sget("keyword",'s','');
		$keywordField = sget("keywordField",'s','name');

		$where = 'id > 0';
		if($keyword && $keywordField) $where = "$keywordField like '%$keyword%'";
		$coins=$this->db->model('ucoin_rules')
					->where($where)
					->page($page+1,$size)
					->order("$sortField $sortOrder")
					->getPage();
		
		foreach($coins['data'] as $k=>$v){
			$coins['data'][$k]['input_time']=$v['input_time']>1000 ? date("Y-m-d H:i:s",$v['input_time']) : '-';
			$coins['data'][$k]['combinable']=$coins['data'][$k]['combinable']?'是':'否';
		}
		$result=array('total'=>$coins['count'],'data'=>$coins['data']);
		$this->json_output($result);
	}

	/**
	 * 新增红包
	 */
	public function add(){
		$item = array();
		$id = sget('id','i',0);
		$this->db->model('ucoin_rules');

		if($id){
			$item = $this->db->getPk($id) or $this->error('红包信息有误');
		}else{
			$item['pre_no'] = randstr(3,'NUMBER');
		}

		if($_POST){
			$coin_rule = array();
			$coin_rule['name'] = spost('name','s') or $this->error('请填写红包名称');
			$coin_rule['pre_no'] = spost('pre_no','s') or $this->error('请填写编号前缀');
			$coin_rule['amount'] = spost('amount','f') or $this->error('请填写红包金额');
			$coin_rule['limit_percent'] = spost('limit_percent','f',0);
			if($coin_rule['limit_percent'] < 0) $this->error('使用金额占比不能小于零');
			$coin_rule['limit_amount'] = spost('limit_amount','f',0);
			if($coin_rule['limit_amount'] < 0) $this->error('最低投资金额不能小于零');
			$coin_rule['active_start_time'] = strtotime(spost('active_start_time'));
			$coin_rule['active_end_time'] = strtotime(spost('active_end_time'));

			$coin_rule['use_start_time'] = strtotime(spost('use_start_time'));
			$coin_rule['use_end_time'] = strtotime(spost('use_end_time'));
			$coin_rule['use_days'] = spost('use_days','i');
			if(!($coin_rule['use_start_time'] && $coin_rule['use_end_time'] || $coin_rule['use_days'])) $this->error('请选择有效期限');
			if($coin_rule['use_end_time']) $coin_rule['use_end_time'] += 86399;
			if($coin_rule['active_end_time']) $coin_rule['active_end_time'] += 86399;

			$coin_rule['remark'] = spost('remark','s');
			$coin_rule['combinable'] = spost('combinable','i',0);
			$coin_rule['sms'] = spost('sms','s');
			$coin_rule['input_time'] = CORE_TIME;

			$exists_id = $this->db->select('id')->where("pre_no='{$coin_rule['pre_no']}'")->getOne();
			if(($id && $exists_id != $id) || (!$id && $exists_id)) $this->error('红包编号已存在');

			($id > 0 ? $this->db->where('id='.$id)->update($coin_rule) : $this->db->add($coin_rule))
			?
			$this->success()
			:
			$this->error('插入数据库失败:'.$this->db->getDbError())
			;
		}

		$this->assign('item',$item);
		$this->assign('page_title','红包信息');
		$this->display();
	}

	/**
	 * 红包报表
	 */
	public function report(){
		$coin_status = L('coin_status');
		$coin_rule_id = sget('id','i');
		if($coin_rule_id)
			$coin_rule = $this->db->model('ucoin_rules')->getPk($coin_rule_id) or $this->error('rule id 错误');

		if($output=sget('output','s')){
			if($output == 'json'){
				$page = sget("pageIndex",'i',0); //页码
				$size = sget("pageSize",'i',20); //每页数
				$sortField = sget("sortField",'s','id'); //排序字段
				$sortOrder = sget("sortOrder",'s','desc'); //排序
				$status = strlen(trim($_POST['status'])) ? intval($_POST['status']) : -2;
				$keyword = sget("keyword",'s','');
				$keywordField = sget("keywordField",'s','name');

				$where = array('AND');
				if($coin_rule) $where[] = 'rule_id = '.$coin_rule['id'];
				if($keyword && $keywordField) $where[] = "$keywordField like '%$keyword%'";
				if($status >= -1) $where[] = "status = $status";
				$coins=$this->db->model('user_coins')
							->where($where)
							->page($page+1,$size)
							->order("$sortField $sortOrder")
							->getPage();
				
				foreach($coins['data'] as $k=>$v){
					$coins['data'][$k]['user_id']=$v['user_id']>0 ? $v['user_id'] : '-';
					$coins['data'][$k]['input_time']=$v['input_time']>1000 ? date("Y-m-d H:i:s",$v['input_time']) : '-';
					$coins['data'][$k]['used_time']=$v['used_time']>1000 ? date("Y-m-d H:i:s",$v['used_time']) : '-';
					$coins['data'][$k]['actived_time']=$v['actived_time']>1000 ? date("Y-m-d H:i:s",$v['actived_time']) : '-';
					$coins['data'][$k]['status']=$coin_status[$v['status']];
				}
				$result=array('total'=>$coins['count'],'data'=>$coins['data']);
				$this->json_output($result);
			}elseif($output == 'excel'){
				$resultPHPExcel = E('PHPExcel',APP_LIB.'extend');
				$resultPHPExcel->getActiveSheet()->setCellValue('A1', '编号'); 
				$resultPHPExcel->getActiveSheet()->setCellValue('B1', '密码'); 
				$resultPHPExcel->getActiveSheet()->setCellValue('C1', '金额'); 
				$resultPHPExcel->getActiveSheet()->setCellValue('D1', '状态'); 
				$resultPHPExcel->getActiveSheet()->setCellValue('E1', '用户'); 
				$resultPHPExcel->getActiveSheet()->setCellValue('F1', '生成时间'); 
				$resultPHPExcel->getActiveSheet()->setCellValue('G1', '激活时间'); 
				$resultPHPExcel->getActiveSheet()->setCellValue('H1', '使用时间'); 
				$i = 2; 

				$coins=$this->db->model('user_coins')
							->where('rule_id = '.$coin_rule['id'])->getAll();
				foreach($coins as $item){ 
					$resultPHPExcel->getActiveSheet()->setCellValueExplicit('A' . $i,$item['no'],PHPExcel_Cell_DataType::TYPE_STRING);
					$resultPHPExcel->getActiveSheet()->setCellValue('B' . $i, $item['passwd']); 
					$resultPHPExcel->getActiveSheet()->setCellValue('C' . $i, $item['amount']); 
					$resultPHPExcel->getActiveSheet()->setCellValue('D' . $i, $coin_status[$item['status']]); 
					$resultPHPExcel->getActiveSheet()->setCellValue('E' . $i, $item['user_id']>0 ? $item['user_id'] : '-'); 
					$resultPHPExcel->getActiveSheet()->setCellValue('F' . $i, $item['input_time']>1000 ? date("Y-m-d H:i:s",$item['input_time']) : '-'); 
					$resultPHPExcel->getActiveSheet()->setCellValue('G' . $i, $item['actived_time']>1000 ? date("Y-m-d H:i:s",$item['actived_time']) : '-'); 
					$resultPHPExcel->getActiveSheet()->setCellValue('H' . $i, $item['used_time']>1000 ? date("Y-m-d H:i:s",$item['used_time']) : '-'); 
					$i++; 
				}
				$resultPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20); 
				$resultPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25); 
				$resultPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(30); 
				$resultPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(30); 
				$resultPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(30);
				$outputFileName = 'coins_'.$coin_rule['pre_no'].'.xls'; 
				$xlsWriter = new PHPExcel_Writer_Excel5($resultPHPExcel); 
				header("Content-Type: application/octet-stream"); 
				header('Content-Disposition:inline;filename="'.$outputFileName.'"'); 
				header("Content-Transfer-Encoding: binary"); 
				header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
				header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
				header("Pragma: no-cache"); 
				$xlsWriter->save( "php://output" );
			}
		}

		$this->assign('id',$coin_rule_id);
		$this->assign('coin_status',$coin_status);
		$this->display();
	}

	/**
	 * 发放红包
	 */
	public function send(){
		$coin_rule_id = sget('id',i);
		$coin_rule = $this->db->model('ucoin_rules')->getPk($coin_rule_id) or $this->error('rule id 错误');

		if($_POST){
			$number = spost('number','i',0);
			$user_ids = spost('user_ids','s');

			if($user_ids){
				$user_ids = explode(',',$user_ids);
				$number = count($user_ids);
			}else{
				$user_ids = array();
				if(spost('user_filter','i')){
					$sql = $this->_getFilterSql(strim($_POST));
					$rows = $this->db->getAll($sql);
					if($rows){
						foreach($rows as $r){
							$user_ids[] = $r['user_id'];
						}
						$number = count($user_ids);
					}
				}
			}

			$passwd_all = $this->db->model('user_coins')->select('passwd')->where("passwd <> '' AND status > -1")->getCol();

			if($number < 1 && empty($user_ids)) $this->error('参数错误');

			$mobiles = array();//用户手机号码
			$count_actived=0;
			$this->db->startTrans();
			$this->db->model('user_coins');
			$insert_sql = 'INSERT INTO '.$this->db->table('user_coins').'(user_id,rule_id,no,passwd,amount,limit_amount,combinable,status,remark,actived_time,start_time,end_time,input_time) VALUES';
			for($i=0;$i<$number;$i++){
/*				$insert_sql .= $this->db->addSql(array('user_id'=>'0','rule_id'=>$coin_rule_id,'no'=>$coin_rule['pre_no'].randstr(10,'NUMBER'),'passwd'=>randstr(20),
													'amount'=>$coin_rule['amount'],'limit_amount'=>$coin_rule['limit_amount'],'combinable'=>$coin_rule['combinable'],'remark'=>$coin_rule['remark'],
													'status'=>0,'input_time'=>CORE_TIME,)) . ";";*/
				$user_id = isset($user_ids[$i]) ? intval($user_ids[$i]) : 0;
				$no = $coin_rule['pre_no'].randstr(6,'NUMBER');
				do {//判断是否重复
					$passwd = randstr(20,'NUPPER');
				} while (in_array($passwd, $passwd_all));
				$passwd_all[] = $passwd;
				
				$status = 0;
				$actived_time = 0;
				$end_time = $coin_rule['use_end_time'];
				$start_time = $coin_rule['use_start_time'];
				if($user_id){
					$passwd = '';
					$actived_time = CORE_TIME;
					$status = 1;
					$count_actived++;

					$user = M('system:sysUser')->getPk($user_id);
					$mobiles[] = array('user_id'=>$user['user_id'],'mobile'=>$user['mobile']);

					//激活的时候才计算有效期
					if(!($start_time && $end_time) && $coin_rule['use_days']){
						$start_time = CORE_TIME;
						$end_time = strtotime("+{$coin_rule['use_days']} days 23:59:59");
					}
				}
				$insert_sql .= "({$user_id},'{$coin_rule_id}','{$no}','{$passwd}','{$coin_rule['amount']}','{$coin_rule['limit_amount']}','{$coin_rule['combinable']}',{$status},'{$coin_rule['remark']}',{$actived_time},{$start_time},{$end_time},".CORE_TIME."),";
			}
			$this->db->execute(rtrim($insert_sql,','));
			$this->db->model('ucoin_rules')->wherePK($coin_rule_id)->update(array('count_send'=>'+='.$number,'count_actived'=>'+='.$count_actived));

			if($this->db->commit()){
				if($user_ids){
					//更新融币账户统计
					$this->db->execute('update `'.$this->db->table('uaccount').'` ua right join (SELECT sum(amount) a,count(*) c,user_id FROM `'.$this->db->table('user_coins').'` WHERE user_id in('.implode(',',$user_ids).') AND status = 1  group by user_id) co on ua.user_id = co.user_id set ua.coin = co.a,ua.coin_count = co.c');
					if($coin_rule['sms']){//发送短信通知
						M('system:sysSMS')->sendBatch($mobiles,$coin_rule['sms'],8);
					}
				}
				$this->success('生成成功');
			}else{
				$this->db->rollback();
				$this->error('生成失败:'.$this->db->getDbError());
			}
		}
		$this->display();
	}

	/**
	 * 红包发放-筛选用户
	 */
	public function users(){
		$this->channels = L('user_chanel');
		if($_POST){
			$where=1;
			$page = sget("pageIndex",'i',0); //页码
			$size = sget("pageSize",'i',20); //每页数
			$key_type=sget('key_type','s','co.mobile'); //搜索类型

			if($keyword=sget('keyword','s','')){ //关键字
				$where.=" and $key_type='{$keyword}'";
			}
			
			$list=$this->db->from('customer_contact co')
				->join('customer cu','co.c_id=cu.c_id')
				->select("co.user_id,co.name,co.mobile,FROM_UNIXTIME(co.last_login,'%y-%m-%d %H:%i:%s') as last_login,co.visit_count,cu.c_name")
				->where($where)
				->order("co.user_id desc")
				->page($page,$size)
				->getPage();

			$result=array('total'=>$list['count'],'data'=>$list['data'],'msg'=>'');
			$this->json_output($result);
		}
		
		$this->allow_select_all = sget('allow_select_all','i',1);
		$this->display();
	}

	private function _getFilterSql($data){
		$having = array();
		$where="utype=1";
		if($data['chanel']) $where.=" and chanel='{$data['chanel']}' ";

		foreach(array('reg_days','cash_surplus','invest_sum') as $k){
			$arr = $data[$k];
			if($arr){
				$arr = array_map('intval', $arr);
				list($start,$end) = $arr;
				if($start){
					$having[] =" `{$k}`>='$start'";	
				}
				if($end){
					$having[] =" `{$k}`<='$end' ";	
				}
			}
		}
		$having_sql = '';
		if($having) $having_sql = 'having '.implode('AND',$having);
		
		//关键词
		if(!empty($data['keyword'])){
			$where.=" and {$data['key_type']}='{$data['keyword']}' ";
		}

		//SELECT u.user_id,mobile,email,real_name,visit_count,chanel,chk_idcard,chk_bank,chk_safe,last_login,reg_time,ROUND((1407290906-reg_time)/86400) reg_days,a1.surplus cash_surplus,a1.all_in cash_all,sum(a2.surplus) invest_surplus,sum(a2.all_in) invest_sum,sum(a2.n_in) invest_count FROM `ss_user` u left join `ss_user_info` i on u.user_id = i.user_id left join `ss_usaccount` a1 on a1.user_id = u.user_id AND a1.ac_type=1 left join `ss_usaccount` a2 on a2.user_id = u.user_id AND a2.ac_type in(2,3) group by u.user_id
		return "SELECT u.user_id,mobile,email,real_name,visit_count,chanel,chk_idcard,chk_bank,chk_safe,last_login,reg_time,ROUND((".CORE_TIME."-reg_time)/86400) reg_days,a1.surplus cash_surplus,a1.all_in cash_all,sum(a2.surplus) invest_surplus,sum(a2.all_in) invest_sum,sum(a2.n_in) invest_count FROM `ss_user` u left join `ss_user_info` i on u.user_id = i.user_id left join `ss_usaccount` a1 on a1.user_id = u.user_id AND a1.ac_type=1 left join `ss_usaccount` a2 on a2.user_id = u.user_id AND a2.ac_type in(2,3)
			 where {$where} 
			 group by u.user_id $having_sql";
	}
}
?>
