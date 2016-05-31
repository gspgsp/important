<?php
class indexAction extends homeBaseAction{


	public function init()
	{
		$this->forward('/user/msg');
	}
}