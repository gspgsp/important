	<?php 
/**
* 数据报表
*/
class indexAction extends adminBaseAction
{
	public function init()
	{
		//接收参数
		$start_time = spost('start_time','s','');
		$end_time = spost('end_time','s','');
		$time_type = spost('time_type','s','');
		$team_id = spost('team_id','i','');
		
		if(empty($start_time) && empty($end_time) && empty($time_type)){
			$start_time = date('Y-m-d H:i:s',strtotime(date('Y-m-d', time())));
			// $start_time=date('Y-m-d H:i:s',mktime(0,0,0,date('m'),date('d')-1,date('Y'))); //起始日期昨天0点
			$end_time = date('Y-m-d H:i:s',strtotime('now'));
		}
		if(empty($time_type)){
			$time_type = 'default';
		}
		$team = $this->db=M('public:common')->model('adm_role')->where('pid=22')->getAll();
		if(empty($team_id)){
			$team_id = $team[0]['id'];
		}
		$this->assign('team',$team);
		$this->assign('order_type',1);
		$this->assign('start_time',$start_time);
		$this->assign('end_time',$end_time);
		$this->assign('time_type',$time_type);
		$this->assign($time_type.'_selected','selected="selected"');
		$this->display('index.list.html');
	}
	public function purchase()
	{
		//接收参数
		$start_time = spost('start_time','s','');
		$end_time = spost('end_time','s','');
		$time_type = spost('time_type','s','');
		$team_id = spost('team_id','i','');
		
		if(empty($start_time) && empty($end_time) && empty($time_type)){
			$start_time = date('Y-m-d H:i:s',strtotime(date('Y-m-d', time())));
			//$start_time=date('Y-m-d H:i:s',mktime(0,0,0,date('m'),date('d')-1,date('Y'))); //起始日期昨天0点
			$end_time = date('Y-m-d H:i:s',strtotime('now'));
		}
		// p($start_time,$end_time);die();
		if(empty($time_type)){
			$time_type = 'default';
		}
		$team = $this->db=M('public:common')->model('adm_role')->where('pid=22')->getAll();
		if(empty($team_id)){
			$team_id = $team[0]['id'];
		}
		$this->assign('order_type',2);
		$this->assign('team',$team);
		$this->assign('start_time',$start_time);
		$this->assign('end_time',$end_time);
		$this->assign('time_type',$time_type);
		$this->assign($time_type.'_selected','selected="selected"');
		$this->display('index.list.html');
	}
	public function getTeamData(){
		$start_time = strtotime(spost('start_time','s',''));
		$end_time = strtotime(spost('end_time','s',''));
		$time_type = spost('time_type','s','');
		$order_by_time_field = spost('sTime','s','input_time');
		$order_type = spost('order_type','s');
		if(!empty($start_time) && !empty($end_time)){
			$type = 'user_defined_time';
		}
		if(!empty($time_type)){
			$type = $time_type;
		}
		//根据条件读数据 参数1:采购/销售;参数2:时间类型;参数3：开始时间;参数4：结束时间;参数5：团队/个人;参数6：查询order字段参数7：团队id(查询团队不用传);	
		$return_data = $this->data = M('order:reportView')->getDataByTime($order_type,$type,$start_time,$end_time,'team',$order_by_time_field);
		// p($return_data);die();
		$data = $return_data['data'];
		foreach ($data as $key => $value) {
			if(empty($value['role_id'])){
				unset($data[$key]);
			}
		}
		foreach ($data as $key => $value) {
			$team[] = $value['name'];
			$price_data[] = $value['price']/10000;
			$num_data[] = $value['num']/1;
		}
		$time_min = date('Y-m-d',$return_data['time'][0]);
		$time_max = date('Y-m-d',$return_data['time'][1]);
		$title = $time_min.'~'.$time_max;
		$subtitle = '所有团队战况图';

		$option_arr = array(
			'chart'=>array('type'=>'column','plotBorderWidth'=>1),
			'colors'=>array('#8bbc21','#126AED'),
			'title'=>array('text'=>$title,'margin'=>30),
			'subtitle'=>array('text'=>$subtitle,'style'=>array('color'=>'#FF00FF','fontSize'=>'25',)),
			'xAxis'=>array('categories'=>$team,'title'=>array('text'=>null)),
			'yAxis'=>array(
				'min'=>0,
				'title'=>array('text'=>'万元/吨','align'=>'high'),
				'labels'=>array('overflow'=>'justify')),
			'tooltip'=>array('valueSuffix'=>''),
			'plotOptions'=>array('bar'=>array('dataLabels'=>array('enabled'=>true))),
			'legend'=>array('layout'=>'vertical','align'=>'left','verticalAlign'=>'top','floating'=>true,'backgroundColor'=>'#FFFFFF','borderWidth'=>0,'shadow'=>true),
			'credits'=>array('enabled'=>false),
        	'series'=>array(array('name'=>'总额','data'=>$price_data),array('name'=>'总吨数','data'=>$num_data)),
		);
		$this->ajaxReturn('1',json_encode($option_arr));
	}
	public function getPersonData(){
		$start_time = strtotime(spost('start_time','s',''));
		$end_time = strtotime(spost('end_time','s',''));
		$time_type = spost('time_type','s','');
		$team_id = spost('team_id','i','');
		$team_name = spost('team_name','s','');
		$show_type = spost('show_type','s','');
		$order_by_time_field = spost('sTime','s','input_time');
		$order_type = spost('order_type','s');

		if(!empty($start_time) && !empty($end_time)){
			$type = 'user_defined_time';
		}
		if(!empty($time_type)){
			$type = $time_type;
		}
		//根据条件读数据 参数1:采购/销售;参数2:时间类型;参数3：开始时间;参数4：结束时间;参数5：团队/个人;参数6：查询order字段参数7：团队id(查询团队不用传);	
		$return_data = $this->data = M('order:reportView')->getDataByTime($order_type,$type,$start_time,$end_time,'person',$order_by_time_field,$team_id);
		// p($return_data);die();
		$data = $return_data['data'];
		foreach ($data as $key => $value) {
			$name[] = $value['name'];
			$price_data[] = $value['price']/10000;
			$num_data[] = $value['num']/1;
		}
		$time_min = date('Y-m-d',$return_data['time'][0]);
		$time_max = date('Y-m-d',$return_data['time'][1]);
		$title = $time_min.'~'.$time_max;
		if($show_type == 'pie_num'){
			$subtitle = $team_name .'个人订单总吨数贡献率';
			$pie_data = array();
			foreach ($data as $key => $value) {
				$pie_data[] = array($value['name'],(int)$value['num']);
			}
			$option_arr = array(
				'chart'=>array('plotBackgroundColor'=>null,'plotBorderWidth'=>null,'plotShadow'=>false),
				'title'=>array('text'=>$title,'margin'=>30),
				'subtitle'=>array('text'=>$subtitle,'style'=>array('color'=>'#FF00FF','fontSize'=>'25',)),
				'tooltip'=>array('pointFormat'=>'{series.name}: <b>{point.percentage:.1f}%</b>'),
				'plotOptions'=>array('pie'=>array('allowPointSelect'=>true,'cursor'=>'pointer',
					'dataLabels'=>array('enabled'=>true,'color'=>'#000000','connectorColor'=>'#000000','format'=>'<b>{point.name}</b>: {point.percentage:.1f} %'))),
       	 		'series'=>array(array('type'=>'pie','name'=>'总吨数','data'=>$pie_data)),
			);
		}elseif($show_type == 'pie_price'){
			$subtitle = $team_name .'个人订单总额贡献率';
			$pie_data = array();
			foreach ($data as $key => $value) {
				$pie_data[] = array($value['name'],(int)$value['price']);
			}
			$option_arr = array(
				'chart'=>array('plotBackgroundColor'=>null,'plotBorderWidth'=>null,'plotShadow'=>false),
				'title'=>array('text'=>$title,'margin'=>30),
				'subtitle'=>array('text'=>$subtitle,'style'=>array('color'=>'#FF00FF','fontSize'=>'25',)),
				'tooltip'=>array('pointFormat'=>'{series.name}: <b>{point.percentage:.1f}%</b>'),
				'plotOptions'=>array('pie'=>array('allowPointSelect'=>true,'cursor'=>'pointer',
					'dataLabels'=>array('enabled'=>true,'color'=>'#000000','connectorColor'=>'#000000','format'=>'<b>{point.name}</b>: {point.percentage:.1f} %'))),
       	 		'series'=>array(array('type'=>'pie','name'=>'订单总额','data'=>$pie_data)),
			);
		}else{
			$subtitle = $team_name .'个人战况图';
			$option_arr = array(
				'chart'=>array('type'=>'bar','plotBorderWidth'=>1),
				'colors'=>array('#CD7B00','#8085E9'),
				'title'=>array('text'=>$title,'margin'=>30),
				'subtitle'=>array('text'=>$subtitle,'style'=>array('color'=>'#FF00FF','fontSize'=>'25',)),
				'xAxis'=>array('categories'=>$name,'title'=>array('text'=>null)),
				'yAxis'=>array(
					'min'=>0,
					'title'=>array('text'=>'万元/吨','align'=>'high'),
					'labels'=>array('overflow'=>'justify')),
				'tooltip'=>array('valueSuffix'=>''),
				'plotOptions'=>array('bar'=>array('dataLabels'=>array('enabled'=>true))),
				'legend'=>array('layout'=>'vertical','align'=>'left','verticalAlign'=>'top','floating'=>true,'backgroundColor'=>'#FFFFFF','borderWidth'=>0,'shadow'=>true),
				'credits'=>array('enabled'=>false),
        		'series'=>array(array('name'=>'总额','data'=>$price_data),array('name'=>'总吨数','data'=>$num_data)),
			);
		}
		$this->ajaxReturn('1',json_encode($option_arr));
	}
}
?>