<?php
/** 
 * 会员日报
 * deray.wang@2014-10-08
 */
class userAction extends adminBaseAction {
	private $title='会员日报'; //
	public function __init(){
		$this->debug = false;
		$this->db=M('public:common')->model('stat_day');
	}

	/**
	 * 会员日报
	 * @access public 
	 * @return html
	 */
	public function init(){
		//取出查询时间
		$where = $this->_range();	
		
		$userDate=$this->db->where($where)->getAll();
		$userList = array('register'=>0,
						'ref_register'=>0,
						'chanel_register'=>0,
						'chanel_register_uapply'=>0,
						'invest_user'=>0,
						'ref_invest_user'=>0,
						'ref_no_register'=>0,
						'ref_no_invest_user'=>0,
						'no_invest_user'=>0,
						'no_ref_invest_user'=>0,
						'ref_noinvest_user'=>0,
						'men_count'=>0,
						'women_count'=>0,
						'men_uapply_count'=>0,
						'women_uapply_count'=>0,
						'unknown_sex'=>0,
						'unknown_uapply_sex'=>0,
						'today_invest_user'=>0,
					);
					
		$user_terminal = array('web'=>0,'touch'=>0,'weixin'=>0,'android'=>0,'ios'=>0,'wp'=>0);
		$uapply_user_terminal = array('web'=>0,'touch'=>0,'weixin'=>0,'android'=>0,'ios'=>0,'wp'=>0);
		$nouapply_user_terminal = array('web'=>0,'touch'=>0,'weixin'=>0,'android'=>0,'ios'=>0,'wp'=>0);
		foreach($userDate as $k=>$v){
			
				$userList['register'] += $v['register'];//注册人数
				$userList['ref_register'] += $v['ref_register'];//注册人数(推荐)
				$userList['chanel_register'] += $v['chanel_register'];//今日渠道注册人数
				$userList['chanel_register_uapply'] += $v['chanel_register_uapply'];//今日注册-投资用户-属于渠道来的用户数量
				
				$userList['invest_user'] += $v['invest_user'];//投资用户
				$userList['ref_invest_user'] += $v['ref_invest_user'];//投资用户(推荐)
				
				$userList['men_count'] += $v['men_count'];//注册人数男
				$userList['women_count'] += $v['women_count'];//注册人数女
				$userList['unknown_sex'] += $v['unknown_sex'];//注册人数未知性别
				$userList['men_uapply_count'] += $v['men_uapply_count'];//投资用户数男
				$userList['women_uapply_count'] += $v['women_uapply_count'];//投资用户数女
				$userList['unknown_uapply_sex'] += $v['unknown_uapply_sex'];//投资用户数未知性别
				
				$userList['today_invest_user'] += $v['today_invest_user'];//今日注册 - 投资的用户
				$userList['today_invest_count'] += $v['today_invest_count'];
				$userList['today_invest_sum'] += $v['today_invest_sum'];
				
				$utData = (array)json_decode($v['user_terminal']);
				foreach($utData as $key=>$val){
					$user_terminal[$key] += $val;
					//$user_terminal['app'] += $val['app'];
				}
				
				$uapply_terminal_data = (array)json_decode($v['uapply_user_terminal']);
				foreach($uapply_terminal_data as $key=>$val){
					$uapply_user_terminal[$key] += $val;
				}
				
				$nouapply_user_terminal_data = (array)json_decode($v['nouapply_user_terminal']);
				foreach($nouapply_user_terminal_data as $key=>$val){
					$nouapply_user_terminal[$key] += $val;
				}
				
				$register_age_data = (array)json_decode($v['register_age']);
				foreach($register_age_data as $key=>$val){
					$register_age[$key] += $val;
				}
				
				$uapply_age_data = (array)json_decode($v['uapply_age']);
				foreach($uapply_age_data as $key=>$val){
					$uapply_age[$key] += $val;
				}
				
				$no_uapply_age_data = (array)json_decode($v['no_uapply_age']);
				foreach($no_uapply_age_data as $key=>$val){
					$no_uapply_age[$key] += $val;
				}
				
				//注册用户的区域分布
				$user_area_data = (array)json_decode($v['user_area']);
				foreach($user_area_data as $key=>$val){
					$user_area[$key] += $val;
				}
				
				//今日注册-投资用户-区域分布情况
				$uapply_user_area_data = (array)json_decode($v['uapply_user_area']);
				foreach($uapply_user_area_data as $key=>$val){
					$uapply_user_area[$key] += $val;
				}
				
				//今日注册-未投资用户-区域分布情况
				$nouapply_user_area_data = (array)json_decode($v['nouapply_user_area']);
				foreach($nouapply_user_area_data as $key=>$val){
					$nouapply_user_area[$key] += $val;
				}
			
		}
		
		//今日注册-注册终端情况
		$str_terminal = "[";
		foreach($user_terminal as $k=>$v){
			if($v){
				$str_terminal .= "['".$k."', ".$v."],";
			}
		}
		$str_terminal = trim($str_terminal,",")."]";
		$userList['user_terminal']=$str_terminal;
		
		//今日注册-投资用户-注册终端情况
		$str_uapply_terminal = "[";
		foreach($uapply_user_terminal as $k=>$v){
			if($v){
				$str_uapply_terminal .= "['".$k."', ".$v."],";
			}
		}
		$str_uapply_terminal = trim($str_uapply_terminal,",")."]";
		$userList['uapply_user_terminal']=$str_uapply_terminal;
		
		//今日注册-未投资用户-注册终端情况
		$str_nouapply_terminal = "[";
		foreach($nouapply_user_terminal as $k=>$v){
			if($v){
				$str_nouapply_terminal .= "['".$k."', ".$v."],";
			}
		}
		$str_nouapply_terminal = trim($str_nouapply_terminal,",")."]";
		$userList['nouapply_user_terminal']=$str_nouapply_terminal;
		
		$age_label = array('age1_count'=>'18岁以下','age2_count'=>'18~24','age3_count'=>'25~29','age4_count'=>'30~34','age5_count'=>'35~39','age6_count'=>'40~49','age7_count'=>'50~59','age8_count'=>'60岁及以上','age_count'=>'未知');
		$age_label1 = array('age1_uapply'=>'18岁以下','age2_uapply'=>'18~24','age3_uapply'=>'25~29','age4_uapply'=>'30~34','age5_uapply'=>'35~39','age6_uapply'=>'40~49','age7_uapply'=>'50~59','age8_uapply'=>'60岁及以上','age_uapply'=>'未知');
		$age_label2 = array('age1_nouapply'=>'18岁以下','age2_nouapply'=>'18~24','age3_nouapply'=>'25~29','age4_nouapply'=>'30~34','age5_nouapply'=>'35~39','age6_nouapply'=>'40~49','age7_nouapply'=>'50~59','age8_nouapply'=>'60岁及以上','age_nouapply'=>'未知');
		$str = "[";
		foreach($register_age as $k=>$v){
			$str .= "['".$age_label[$k]."', ".$v."],";
		}
		$str = trim($str,",")."]";
		$userList['register_age'] = $str;
		
		$str1 = "[";
		foreach($uapply_age as $k=>$v){
			$str1 .= "['".$age_label1[$k]."', ".$v."],";
		}
		$str1 = trim($str1,",")."]";
		$userList['uapply_age'] = $str1;
		
		$str2 = "[";
		foreach($no_uapply_age as $k=>$v){
			$str2 .= "['".$age_label2[$k]."', ".$v."],";
		}
		$str2 = trim($str2,",")."]";
		$userList['no_uapply_age'] = $str2;
		
		//注册用户的区域分布
		$str_area = "[";
		foreach($user_area as $k=>$v){
			$str_area .= "['".$k."', ".$v."],";
		}
		$str_area = trim($str_area,",")."]";
		$userList['user_area'] = $str_area;
		
		//今日注册-投资用户-区域分布情况
		$str_uapply_area = "[";
		foreach($uapply_user_area as $k=>$v){
			$str_uapply_area .= "['".$k."', ".$v."],";
		}
		$str_uapply_area = trim($str_uapply_area,",")."]";
		$userList['uapply_user_area'] = $str_uapply_area;
		
		//今日注册-未投资用户-区域分布情况
		$str_nouapply_area = "[";
		foreach($nouapply_user_area as $k=>$v){
			$str_nouapply_area .= "['".$k."', ".$v."],";
		}
		$str_nouapply_area = trim($str_nouapply_area,",")."]";
		$userList['nouapply_user_area'] = $str_nouapply_area;
		
		if($userList){
			//注册人数(直接)=注册总人数-推荐注册人数-渠道注册人数
			$userList['ref_no_register'] = $userList['register'] - $userList['ref_register'] - $userList['chanel_register'];
			//投资用户(直接)=今天注册并投资总人数-推荐注册投资人数-渠道注册投资人数
			$userList['ref_no_invest_user'] = $userList['today_invest_user'] - $userList['ref_invest_user'] - $userList['chanel_register_uapply'];
			//未投资用户=注册总人数-今天注册并投资总人数
			$userList['no_invest_user'] = $userList['register'] - $userList['today_invest_user'];
			//未投资用户(直接注册)=注册人数(直接)-投资用户(直接)
			$userList['no_ref_invest_user'] = $userList['ref_no_register'] - $userList['ref_no_invest_user'];	
			$userList['ref_noinvest_user'] = $userList['ref_register'] - $userList['ref_invest_user'];	//未投资用户(推荐)
			//未投资用户(渠道注册人数)=
			$userList['no_chanel_register_uapply'] = $userList['chanel_register'] - $userList['chanel_register_uapply'];	
			
			$userList['men_no_uapply_count'] = $userList['men_count'] - $userList['men_uapply_count'];	//未投资用户数男
			$userList['women_no_uapply_count'] = $userList['women_count'] - $userList['women_uapply_count'];	//未投资用户数女
			$userList['unknown_no_uapply_count'] = $userList['unknown_sex'] - $userList['unknown_uapply_sex'];	//未投资用户数性别未知
		}
		$reg_sex = "[";
		if($userList['men_count']){
			$reg_sex .= "['男',".$userList['men_count']."],";
		}
		if($userList['women_count']){
			$reg_sex .= "['女',".$userList['women_count']."],";
		}
		if($userList['unknown_sex']){
			$reg_sex .= "['未知',".$userList['unknown_sex']."],";
		}
		$reg_sex = trim($reg_sex,",")."]";
		$userList['register_sex'] = $reg_sex;
		
		$uapply_sex = "[";
		if($userList['men_uapply_count']){
			$uapply_sex .= "['男',".$userList['men_uapply_count']."],";
		}
		if($userList['women_uapply_count']){
			$uapply_sex .= "['女',".$userList['women_uapply_count']."],";
		}
		if($userList['unknown_uapply_sex']){
			$uapply_sex .= "['未知',".$userList['unknown_uapply_sex']."],";
		}
		$uapply_sex = trim($uapply_sex,",")."]";
		$userList['uapply_user_sex'] = $uapply_sex;
		
		$no_uapply_sex = "[";
		if($userList['men_no_uapply_count']){
			$no_uapply_sex .= "['男',".$userList['men_no_uapply_count']."],";
		}
		if($userList['women_no_uapply_count']){
			$no_uapply_sex .= "['女',".$userList['women_no_uapply_count']."],";
		}
		if($userList['unknown_no_uapply_count']){
			$no_uapply_sex .= "['未知',".$userList['unknown_no_uapply_count']."],";
		}
		$no_uapply_sex = trim($no_uapply_sex,",")."]";

		$userList['nouapply_user_sex'] = $no_uapply_sex;

		$this->assign('user',$userList);
		$this->assign('page_title','会员日报');
		$this->display('user.html');
	}
	
	/**
	 * 会员投资报表
	 * @access public 
	 * @return html
	 */
	public function userInvest(){
		//取出查询时间
		$where = $this->_range();
		
		$userDate=$this->db->model('stat_investday')->where($where)->getAll();
		
		$userList = array('register'=>0,'chanel_register'=>0,'invest_user'=>0,);
		$user_sex = array();		
		$user_chanel = array('regNum'=>0,'chanelNum'=>0,'refNum'=>0);
		$user_terminal = array('web'=>0,'touch'=>0,'weixin'=>0,'android'=>0,'ios'=>0,'wp'=>0);
		$uapply_user_terminal = array('web'=>0,'touch'=>0,'weixin'=>0,'android'=>0,'ios'=>0,'wp'=>0);
		$nouapply_user_terminal = array('web'=>0,'touch'=>0,'weixin'=>0,'android'=>0,'ios'=>0,'wp'=>0);
		foreach($userDate as $k=>$v){
				$userList['invest_user'] += $v['invest_user'];//投资用户
				$userList['ref_invest_user'] += $v['ref_invest_user'];//投资用户(推荐)

				$utData = (array)json_decode($v['terminal']);
				foreach($utData as $key=>$val){
					$user_terminal[$key] += $val;
					//$user_terminal['app'] += $val['app'];
				}
				
				$user_sex_data = (array)json_decode($v['sex']);
				foreach($user_sex_data as $key=>$val){
					$user_sex[$key] += $val;
				}
				
				$register_age_data = (array)json_decode($v['age']);
				foreach($register_age_data as $key=>$val){
					$register_age[$key] += $val;
				}
				
				//投资用户的区域分布
				$user_area_data = (array)json_decode($v['area']);
				foreach($user_area_data as $key=>$val){
					$user_area[$key] += $val;
				}
				
				//投资用户的区域分布
				$chanel_data = (array)json_decode($v['chanel_register']);
				foreach($chanel_data as $key=>$val){
					$user_chanel[$key] += $val;
				}
			
		}
		
		$categories = "[";
		foreach($userDate as $k=>$v){
			//substr($v["day_id"],0,2).
			$dayId = date("Y")."-".substr($v["day_id"],2,2)."-".substr($v["day_id"],4,2);
			$categories .= "['".$dayId."',".$v["invest_user"]."],";

		}
		$v['categories'] = trim($categories,",")."]";
		$this->assign('v', $v);
		
		//今日注册-注册终端情况
		$str_terminal = "[";
		foreach($user_terminal as $k=>$v){
			if($v){
				$str_terminal .= "['".$k."', ".$v."],";
			}
		}
		$str_terminal = trim($str_terminal,",")."]";
		$userList['terminal']=$str_terminal;

		$age_label = array('age1_count'=>'18岁以下','age2_count'=>'18~24','age3_count'=>'25~29','age4_count'=>'30~34','age5_count'=>'35~39','age6_count'=>'40~49','age7_count'=>'50~59','age8_count'=>'60岁及以上','age_count'=>'未知');
		
		$str = "[";
		foreach($register_age as $k=>$v){
			$str .= "['".$age_label[$k]."', ".$v."],";
		}
		$str = trim($str,",")."]";
		$userList['age'] = $str;
		
		//注册用户的区域分布
		$str_sex = "[";
		foreach($user_sex as $k=>$v){
			$str_sex .= "['".$k."', ".$v."],";
		}
		$str_sex = trim($str_sex,",")."]";
		$userList['sex'] = $str_sex;
		
		//注册用户的区域分布
		$str_area = "[";
		foreach($user_area as $k=>$v){
			$str_area .= "['".$k."', ".$v."],";
		}
		$str_area = trim($str_area,",")."]";
		$userList['area'] = $str_area;
		$userList['user_chanel']= $user_chanel;
		
		$this->assign('user',$userList);
		$this->assign('page_title','会员投资报表');

		$this->display('userInvest.html');
	}
	
	/**
	 * 某段时间注册会员的投资情况报表
	 * @access public 
	 * @return html
	 */
	public function userTimeInvest(){
		$starTime=sget('startTime','s',date('Y-m-d',strtotime('-1 days'))); //开始时间
		$endTime=sget('endTime','s',date('Y-m-d'));  //结束时间
		
		if(!empty($starTime) && strlen($starTime)>=10){
			$time_start = strtotime($starTime);
		}
		if(!empty($endTime) && strlen($endTime)>=10){
			$time_end = strtotime($endTime);
		}
		
		$range = sget('range','s','');
		//搜索条件
		$where="1 ";
		switch($range)
		{
			case 'yesterday':
				$time_start = strtotime('-1 days');
				$time_end = strtotime(date('ymd'));
				$starTime = date('Y-m-d',strtotime('-1 days'));
				$endTime  = date('Y-m-d');
			break;
			case 'beforeYesterday':
				$time_start = strtotime('-2 days');
				$time_end = strtotime('-1 days');
				$starTime = date('Y-m-d',strtotime('-2 days'));
				$endTime  = date('Y-m-d',strtotime('-1 days'));
			break;
			case 'week':
				$time_start = strtotime('-7 days');
				$time_end = strtotime(date('ymd'));
				$starTime = date('Y-m-d',strtotime('-7 days'));
				$endTime  = date('Y-m-d');
			break;
			case 'month':
				$time_start = strtotime('-1 months');
				$time_end = strtotime(date('ymd'));
				$starTime = date('Y-m-d',strtotime('-1 months'));
				$endTime  = date('Y-m-d');
			break;
			case 'halfyear':
				$time_start = strtotime('-6 months');
				$time_end = strtotime(date('ymd'));
				$starTime = date('Y-m-d',strtotime('-6 months'));
				$endTime  = date('Y-m-d');
			break;
		}
		
		$this->starTime = $starTime;
		$this->endTime = $endTime;

		$where.=' and reg_time>='.$time_start.' and reg_time<='.$time_end;	
		$data = array();
		//这段时间注册的用户数
		$data['reg_user'] = (int)$this->db->select('count(1)')->from('manager_user')->where($where)->getOne();
		//这段时间投资的用户数
		$where.=' and is_invest =1';
		$data['invest_user'] = (int)$this->db->select('count(1)')->from('manager_user')->where($where)->getOne();

		$this->assign('user',$data);
		$this->assign('page_title','某段时间注册会员的投资情况');

		$this->display('userInvestCount.html');
	}
	
	/**
	 * 会员概况
	 * @access public 
	 * @return html
	 */
	function visiteSurvey(){
		$time_start=date('ym01', strtotime(date("Y-m-d")));
        $time_end = date('ymd', strtotime("$BeginDate +1 month -1 day"));

		$where.='day_id>='.$time_start.' and day_id<='.$time_end;
		$userDate=M('public:common')->model('stat_day')->where($where)->getAll();
		
		if($userDate){
			$categories = "[";
			$register = "[";
			$invest_user = "[";
			foreach($userDate as $k=>$v){
				//substr($v["day_id"],0,2).
				$dayId = date("Y")."-".substr($v["day_id"],2,2)."-".substr($v["day_id"],4,2);
				$categories .= "'".$dayId."',";
				$register .=$v["register"].",";
				$invest_user .=$v["invest_user"].",";
			}
			$v['categories'] = trim($categories,",")."]";
		    $v['register'] = trim($register,",")."]";
			$v['invest_user'] = trim($invest_user,",")."]";
		}
		$this->assign('v', $v);
		$this->assign('page_title','会员概况');
		$this->display('visiteSurvey.html');
	}
	
	private function _range(){
		$starTime=sget('startTime','s',date('Y-m-d',strtotime('-1 days'))); //开始时间
		$endTime=sget('endTime','s',date('Y-m-d'));  //结束时间
		
		if(!empty($starTime) && strlen($starTime)>=10){
			$time_start = substr(str_replace(array('-',' '),'',$starTime),2);
		}
		if(!empty($endTime) && strlen($endTime)>=10){
			$time_end = substr(str_replace(array('-',' '),'',$endTime),2);
		}
		
		$range = sget('range','s','');
		//搜索条件
		$where="1 ";
		switch($range)
		{
			case 'yesterday':
				$time_start = date('ymd',strtotime('-1 days'));
				$time_end = date('ymd');
				$starTime = date('Y-m-d',strtotime('-1 days'));
				$endTime  = date('Y-m-d');
			break;
			case 'beforeYesterday':
				$time_start = date('ymd',strtotime('-2 days'));
				$time_end = date('ymd');
				$starTime = date('Y-m-d',strtotime('-2 days'));
				$endTime  = date('Y-m-d',strtotime('-1 days'));
			break;
			case 'week':
				$time_start = date('ymd',strtotime('-7 days'));
				$time_end = date('ymd');
				$starTime = date('Y-m-d',strtotime('-7 days'));
				$endTime  = date('Y-m-d');
			break;
			case 'month':
				$time_start = date('ymd',strtotime('-1 months'));
				$time_end = date('ymd');
				$starTime = date('Y-m-d',strtotime('-1 months'));
				$endTime  = date('Y-m-d');
			break;
			case 'halfyear':
				$time_start = date('ymd',strtotime('-6 months'));
				$time_end = date('ymd');
				$starTime = date('Y-m-d',strtotime('-6 months'));
				$endTime  = date('Y-m-d');
			break;
		}
		
		$this->starTime = $starTime;
		$this->endTime = $endTime;
		
		$where.=' and day_id>='.$time_start.' and day_id<='.$time_end;
		
		return $where;
	}
}
?>
