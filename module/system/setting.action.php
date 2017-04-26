<?php
/** 
 * 系统设置
 */
class settingAction extends adminBaseAction {
	public function __init(){
		$this->action = sget('action','');
		$this->db=M('public:common');
		$this->sms_channels = L('sms_channels');
	}
	
	/**
	 * 查看系统设置
	 * @access public 
	 * @return html
	 */
	public function init(){
		$this->site();
	}
	
	/**
	 * 网站设置
	 * @access public 
	 * @return html
	 */
	public function site(){
		$db=$this->db->model('setting');
		
		$action=sget('action','s');
		if($action=='submit'){
			$_data=array();
			foreach ($_POST as $k => $v) {
				if(is_array($v)) $v = addslashes(json_encode($v));
				$_data[] = "('{$k}','{$v}')";
			}
			$db->execute("replace into ".$db->ftable." (code,value) values ".join(',',$_data));
			$this->clearMCache('setting');
			$this->success('更新成功');
		}

		$cfg=array();
		$setting=$db->getAll();
		foreach($setting as $k=>$v){
			$data = $v['value']{0} == '{' ? json_decode($v['value'],TRUE) : $v['value'];
			$cfg[$v['code']]=$data;
		}

		//红包
		$ucoin_rules = $this->db->model('ucoin_rules')->getAll();
		foreach($ucoin_rules as &$r){
			$r['name'] = "[{$r['id']}][{$r['amount']}元]".$r['name'];
		}
		$this->assign('ucoin_rules',arrayKeyValues($ucoin_rules,'id','name'));

		$this->assign('theme_types',L('theme_types'));
		$this->assign('cfg',$cfg);
		$this->assign('page_title','系统设置');
		$this->display('setting.site.html');
	}


	/**
	 * 模块设置
	 * @access public 
	 * @return html
	 */
	public function model(){
		//存在的模块
		$model=array('info'=>'信息');
		
		$db=$this->db->model('setting');
		
		$action=sget('action','s');
		if($action=='submit'){
			$model_img=array();
			$types=array('img','thumb','mini'); //几种规格
			foreach($model as $k=>$v){
				$_data=$_POST[$k];
				$thumb=array(); 
				foreach($types as $s){
					$_w=(int)$_data[$s]['w'];
					$_h=(int)$_data[$s]['h'];
					if($_w>0 && $_h>0){
						$thumb[]=$_w.'x'.$_h;
						$model_img[$k][$s]=array('w'=>$_w,'h'=>$_h);
					}
				}
				if($thumb){
					$model_img[$k]['string']=join(',',$thumb);
				}
			}
			
			$db->execute("replace into ".$db->ftable." (code,value) values ('model_img','".json_encode($model_img)."')");
			$this->clearMCache('setting');
			$this->success('更新成功');
		}
		
		$cfg=array();
		$model_img=$db->select('value')->where("code='model_img'")->getOne();
		if(!empty($model_img)){
			$model_img=json_decode($model_img,true);
		}else{
			$model_img=array();
		}
		$this->assign('imgs',$model_img);
		
		$this->assign('model',$model);
		$this->assign('page_title','模块设置');
		$this->display('setting.model.html');
	}

	/**
	 * 交易设置
	 * @access public 
	 * @return html
	 */
	public function trade(){
		$db=$this->db->model('setting');
		
		$action=sget('action','s');
		if($action=='submit'){
			$_data=array();
			
			$keys=array('gateway_fee','trade_fee','assure_fee','credit_level','prepay_fee','penalty_fee','transfer_fee');
			foreach ($keys as $v) {
				if(isset($_REQUEST[$v])){
					$_data[$v] =$_REQUEST[$v] ;
				}
			}
			$db->execute("replace into ".$db->ftable." (code,value) values ('param_fee','".addslashes(json_encode($_data))."')");
			$this->clearMCache('setting');
			$this->success('更新成功');
		}
		
		$cfg=array();
		$param_fee=$db->select('value')->where("code='param_fee'")->getOne();
		
		if(!empty($param_fee)){
			$param_fee=json_decode($param_fee,true);
		}else{
			$param_fee=array();
		}
		$this->assign('fee',$param_fee);
		$this->assign('page_title','交易设置');
		$this->display('setting.trade.html');
	}


	/**
	 * 塑料圈更新设置
	 * @access public
	 * @return html
	 */
	public function qapp_update(){
		$db=$this->db->model('setting');

		$action=sget('action','s');
		if($_POST){
			$qapp_newest_url=stripslashes($_POST['qapp_newest_url']);
			$qapp_newest_tip=stripslashes($_POST['qapp_newest_tip']);
			$qapp_newest_version=stripslashes($_POST['qapp_newest_version']);

			$_data = array(
				'qapp_newest_url'=>json_encode(array(
					'ios'=>$_POST['qapp_newest_url_ios'],
					'android'=>$_POST['qapp_newest_url_android'],
				)),
				'qapp_newest_tip'=>json_encode(array(
					'ios'=>$_POST['qapp_newest_tip_ios'],
					'android'=>$_POST['qapp_newest_tip_android'],
				)),
				'qapp_newest_version'=>$_POST['qapp_newest_version']
			);
			$db->execute("replace into ".$db->ftable." (code,value) values ('qapp_newest_url','".$_data['qapp_newest_url']."')");
			$db->execute("replace into ".$db->ftable." (code,value) values ('qapp_newest_tip','".$_data['qapp_newest_tip']."')");
			$db->execute("replace into ".$db->ftable." (code,value) values ('qapp_newest_version','".$_data['qapp_newest_version']."')");

			$this->clearMCache('setting');
			$this->success('更新成功');
		}

		$setting=M('system:setting')->getSetting();
		if(!empty($setting)){
			$qapp_newest_url=$setting['qapp_newest_url'];
			$qapp_newest_tip=$setting['qapp_newest_tip'];
			$qapp_newest_version=$setting['qapp_newest_version'];
		}else{
			$qapp_newest_url=array();
			$qapp_newest_tip=array();
			$qapp_newest_version='';
		}
		$this->assign('qapp_newest_url',$qapp_newest_url);
		$this->assign('qapp_newest_tip',$qapp_newest_tip);
		$this->assign('qapp_newest_version',$qapp_newest_version);
		$this->assign('page_title','塑料圈更新');
		$this->display('setting.qapp_update.html');
	}
	/**
	 * 其他设置项目
	 * @return html
	 */
	public function _null($code){
		$db=$this->db->model('setting');
		
		if($_POST){
			M('system:setting')->add(array('code'=>$code,'value'=>addslashes(json_encode($_POST))),TRUE);
			$this->clearMCache('setting_'.$code);
			$this->clearMCache('setting');
			$this->success('更新成功');
		}
		
		$this->assign((array)M('system:setting')->get($code));
		$this->display('setting.'.$code);
	}
}
?>
