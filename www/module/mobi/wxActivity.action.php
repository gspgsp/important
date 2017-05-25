<?php
/**
*微信活动
*/
class wxActivityAction extends homeBaseAction
{
	public function __init()
	{
		$this->db=M('public:common');
	}
	//活动介绍页
	public function enIntroduction(){
		$this->display('introduction_rio');
	}
	//答题页
	public function enActivity(){
		$this->display('activity_rio');
	}
	//获取题目
	public function getActivity(){
		$this->is_ajax = true;
		if($this->user_id<0) $this->error('账户错误');
		$res = M('mobi:wxActivity')->getActivity($this->user_id);
		$count=$res[0]['total'];
		$ques_id=0;
		$question='';
		if($count>0){
			if($count>0 && $count<3){
				$ques_id = 1;
				$question = L('questions')[1];
			}elseif ($count>2 && $count<5) {
					$ques_id = 2;
					$question = L('questions')[2];
			}elseif ($count>4) {
					$ques_id = 3;
					$question = L('questions')[3];
			}
			$this->json_output(array('err'=>10,'msg'=>'题目获取成功','ques_id'=>$ques_id,'question'=>$question));
		}
		$this->json_output(array('err'=>11,'msg'=>'没有相关题目'));
	}
	//保存提交
	public function saveAnswer(){
		$this->is_ajax = true;
		if($this->user_id<0) $this->error('账户错误');
		$ques_id = sget('ques_id','i',0);
		$answer = sget('answer','s');
		if(empty($answer)) $this->json_output(array('err'=>12,'msg'=>'空答案'));
		if(M('mobi:wxActivity')->saveAnswer($this->user_id,$ques_id,$answer)) $this->json_output(array('err'=>13,'msg'=>'提交成功'));
		$this->json_output(array('err'=>14,'msg'=>'提交失败'));
	}
}