<?php
class indexAction extends userBaseAction{


	public function init()
	{
		$this->forward('/user/msg');
	}
}