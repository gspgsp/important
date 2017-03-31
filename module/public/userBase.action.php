<?php

class userBaseAction extends homeBaseAction{

	public function __construct(){
		parent::__construct();
		if($this->user_id<=0){
			$this->forward('/user/login');
		}
		$this->var=M('user:customerContact')->getCustomerInFoById($this->user_id);
	}
}