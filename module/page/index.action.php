<?php 
class indexAction extends homeBaseAction{


	public function init()
	{
		$this->display('index.html');
	}

	public function about()
	{
		$this->display('about.html');
	}
}


 ?>