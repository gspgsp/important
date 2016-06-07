<?php
/**
*新界面
*/
class newPageAction extends homeBaseAction
{
	public function init(){
		$user_id = $_SESSION['uid'];

		$this->display('newpage');
	}///touch/newPage/init
}