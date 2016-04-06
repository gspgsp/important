<?php
/*
 * 选择地区
*/
class regionAction extends action {
	public function __init(){
	}

	/**
	 * 获取地区
	 * @access public 
	 * @return json对象
	 */
	public function init() {
       $region_id=sget('region_id','i');
	   $data=array();
	   if($region_id>0){
           $data=M('system:region')->get_regions($region_id); 
	   }
	   $this->json_output($data);
    }
}
?>