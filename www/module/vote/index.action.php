<?php
/**
* 投票系统
* @author gsp <[<email address>]>
*/
class indexAction extends homeBaseAction
{
	/**
	 * 入口页面
	 * @return [type] [description]
	 */
	public function index(){
		$this->display('index');
	}
	/**
	 * 本公司员工验证
	 * @return [type] [description]
	 */
	public function checkPrizeUser(){
		$this->is_ajax = true;
		$mobile=sget('mobile','s');
		if(!$this->_chkmobile($mobile)) $this->error($this->err);
		$mcode=sget('code','s');
		$result=M('system:sysSMS')->chkDynamicCode($mobile,$mcode);
		if($result['err']>0){
			$this->error($result['msg']);
		}
		$vote_contact = M('vote:contact')->getVoteContact($mobile);
		if(empty($vote_contact)) $this->error('非本公司员工不能操作');
		$id = $vote_contact['id'];
		$type = sget('type','i',0);//类型 0所有人操作 1高管操作
		if($type == 1){
			$high_manager = $vote_contact['high_manager'];
			if($high_manager > 0) {
				$this->json_output(array('err'=>0,'msg'=>'验证通过','userid'=>$id));
			}else{
				$this->error('无权限操作');
			}
		}
		$this->json_output(array('err'=>0,'msg'=>'验证通过','userid'=>$id));
	}
	/**
     * 验证手机号码
     * @access private
     * @return bool
     */
	private function _chkmobile($value=''){
		if(!is_mobile($value)){
			if(empty($value)){
				$this->err='请输入手机号码';
			}else{
				$this->err='错误的手机号码';
			}
			return false;
		}
		return true;
	}
	/**
     * 发送手机验证码
     * @access public
     * @return html
     */
	public function sendmsg(){
		$this->is_ajax=true;
		//验证手机
		$mobile=sget('mobile','s');
		if(!$this->_chkmobile($mobile)){
			$this->error($this->err);
		}
		$sms=M('system:sysSMS');
		//请求动态码
		$result=$sms->genDynamicCode($mobile);
		if($result['err']>0){ //请求错误
			$this->error($result['msg']);
		}
		$msg=$result['msg']; //短信内容
		//发送手机动态码
		$sms->send(0,$mobile,$msg,10);
		$this->success('发送成功');
	}
	/**
	 *  保存答案
	 * @return [type] [description]
	 */
	public function saveAnswer(){
		$this->is_ajax=true;
		$type = sget('type','i',0);//类型 1人 2 节目
		$userid = sget('userid','i',0);
		$contactModel = M('vote:contact');
		if($type == 1){
			$ots_employee = sget('ots_employee','a');
			$bst_team = sget('bst_team','a');//最佳优秀团队
			$bst_sup = sget('bst_sup','a');//最佳优销售支持
			$sal_dir_sh = sget('sal_dir_sh','a');//上海公司
			$sal_dir_ot = sget('sal_dir_ot','a');//其它分公司
			$dep_meneger = sget('dep_meneger','a');
			$bst_ncomer = sget('bst_ncomer','a');
			//当前操作人信息
			$vote_contact = M('vote:contact')->getVoteContact($userid);
			if( $vote_contact['v_count'] > 0 ) $this->json_output(array('err'=>2,'msg'=>'您已经投过票啦'));
			//所用模型
			$voteByStatus = M('vote:voteByStatus');
			$voteTeam = M('vote:voteTeam');
			//开启事物操作
			$contactModel->startTrans();
			try{
				if(!$contactModel->where("id = ".$userid)->update("v_count=v_count+1")) throw new  Exception('系统错误,110');
				if(!$voteByStatus->where("userid = ".$ots_employee['id'])->update("v_score=v_score+1")) throw new  Exception('系统错误,111');
				if(!$voteTeam->where("id = ".$bst_team['id'])->update("vote_count=vote_count+1")) throw new  Exception('系统错误,112');
				if(!$voteTeam->where("id = ".$bst_sup['id'])->update("vote_count=vote_count+1")) throw new  Exception('系统错误,113');
				if(!$voteByStatus->where("userid = ".$sal_dir_sh['id'])->update("v_score=v_score+1")) throw new  Exception('系统错误,114');
				if(!$voteByStatus->where("userid = ".$sal_dir_ot['id'])->update("v_score=v_score+1")) throw new  Exception('系统错误,115');
				if(!$voteByStatus->where("userid = ".$dep_meneger['id'])->update("v_score=v_score+1")) throw new  Exception('系统错误,116');
				if(!$voteByStatus->where("userid = ".$bst_ncomer['id'])->update("v_score=v_score+1")) throw new  Exception('系统错误,117');

			} catch (Exception $e){
				$contactModel->rollback();
				$this->error($e->getMessage());
			}
			//事物提交
            $contactModel->commit();
            $this->success(' 提交成功1');
		}elseif ($type == 2) {
			$vote_show = sget('vote_show','a');
			$voteShow = M('vote:voteShow');
			$str = substr($vote_show[0],1,strlen($vote_show[0])-2);
			$vote_show = explode(",",$str);
			//当前操作人信息
			$vote_contact = M('vote:contact')->getVoteContact($userid);
			if( $vote_contact['v_count'] > 1 ) $this->json_output(array('err'=>2,'msg'=>'您已经投过票啦'));
			//开启事物操作
			$contactModel->startTrans();
			try{
				foreach ($vote_show as $key => $value) {
					$key += 1;
					if(!$voteShow->where("id = $key")->update("score=score+$value")) throw new  Exception('系统错误,118');
				}
				if(!$contactModel->where("id = ".$userid)->update("v_count=v_count+1")) throw new  Exception('系统错误,119');
			} catch (Exception $e){
				$contactModel->rollback();
				$this->error($e->getMessage());
			}
			//事物提交
            $contactModel->commit();
			$this->success(' 提交成功2');
		}
	}
	/**
	 * 获取第一屏数据
	 * @author gsp <[<email address>]>
	 * @return [type] [description]
	 */
	public function getFirstData(){
		$this->is_ajax=true;
		$data = M('vote:contact')->getFirstData();
		if(empty($data)) $this->error('没有相关数据');
		$this->json_output(array('err'=>0,'data'=>$data));
	}
	/**
	 * 获取第二屏数据
	 * @return [type] [description]
	 */
	public function getSecondData(){
		$this->is_ajax=true;
		$data = M('vote:voteShow')->getSecondData();
		if(empty($data)) $this->error('没有相关数据');
		$this->json_output(array('err'=>0,'data'=>$data));
	}
}