<?php
/**
*塑料圈分享模型-gsp
*/
class plasticShareModel extends model
{
	public function __construct() {
		parent::__construct(C('db_default'), 'share_log');
	}
	public function getMySharePur($id){
		$where = " pur.id = $id ";
    	$data = $this->model('purchase')->select('pur.id,pur.p_id,pur.user_id,pro.model,pur.unit_price,pur.store_house,fa.f_name,pur.input_time,pur.type,pur.content')->from('purchase pur')
        ->leftjoin('product pro','pur.p_id=pro.id')
        ->leftjoin('factory fa','pro.f_id=fa.fid')
        ->where($where)
        ->getRow();
        $data['input_time'] = date('Y-m-d H:i:m',$data['input_time']);
        //个人信息
        $cus_con = M('user:customerContact')->getListByUserid($data['user_id']);
        $data['name'] = $cus_con['name'];
        $data['mobile'] = $cus_con['mobile'];
        $data['c_name'] = $this->model('customer')->select('c_name')->where('c_id='.$cus_con['c_id'])->getOne();
        $thumb=$this->model('contact_info')->select('thumb,thumbqq')->where('user_id='.$data['user_id'])->getRow();
        
        if(empty($thumb['thumbqq']))
        {
            if (strstr($thumb['thumb'], 'http')) {
                $thumb['thumb']= $thumb['thumb'];
            } else {
                if(empty($thumb['thumb'])){
                    $thumb['thumb']= "http://statics.myplas.com/upload/16/09/02/logos.jpg";
                }else{
                    $thumb['thumb']= FILE_URL."/upload/".$thumb['thumb'];
                }
            }
        }else{
            $thumb['thumb']=$thumb['thumbqq'];
        }
        $data['thumb'] = $thumb['thumb'];
    	//显示的内容
        if(empty($data['content'])){
            if($data['unit_price']==0.00 && empty($data['model']) && empty($data['f_name']) && empty($data['store_house'])){
                $data['contents'] = '';
            }else{
                $data['contents'] = '价格'.$data['unit_price'].'元左右/'.$data['model'].'/'.$data['f_name'].'/'.$data['store_house'];
                // $data['contents'] = mb_substr($data['contents'], 0,30,'utf-8').'...';
            }
        }elseif(!empty($data['content'])){
            if($data['unit_price']==0.00 && empty($data['model']) && empty($data['f_name']) && empty($data['store_house'])){
                // $data['contents'] = mb_substr($data['content'], 0,30,'utf-8').'...';
            }else{
                $data['contents'] = '价格'.$data['unit_price'].'元左右/'.$data['model'].'/'.$data['f_name'].'/'.$data['store_house'].'/'.$data['content'];
                // $data['contents'] = mb_substr($data['contents'], 0,30,'utf-8').'...';
            }
        }
        return $data;
	}
	public function saveShareLog($share_id,$type,$user_id,$share){
        $today = strtotime(date('Y-m-d',time()));
        $share_count = $this->where("user_id=$user_id and input_time > $today")->select('count(id)')->getOne();
		$contact = M('user:customerContact')->getListByUserid($user_id);
		$_log = array(
			'user_id'=>$user_id,
			'name'=>$contact['name'],
			'mobile'=>$contact['mobile'],
			'share_id'=>$share_id,
			'type'=>$type,
			'input_time'=>CORE_TIME
			);
		if($this->add($_log)){
            if($share_count < 1){
                M('points:pointsBill')->addPoints($share, $user_id, 13, 1);
            }
        }
	}
}