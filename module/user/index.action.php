<?php
class indexAction extends userBaseAction{


	public function init()
	{
		$this->act='index';

		$this->display('index');
	}
}