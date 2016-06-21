<?php
/**
*新界面
*/
class newPageAction extends homeBaseAction
{
	public function init(){
		$this->is_ajax = true;
        if($this->user_id<=0) $this->error('账户错误');
		$this->display('newpage');
	}///touch/newPage/init
}