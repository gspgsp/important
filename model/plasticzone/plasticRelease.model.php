<?php
/**
*塑料圈发布报价-gsp
*/
class plasticReleaseModel extends model
{

	public function __construct() {
		parent::__construct(C('db_default'), 'purchase');
	}
	public function getReleaseMsg($keywords,$page,$size,$type){
		$where = "pur.sync = 6";
		if(!empty($keywords)){
			$where.=" and ((fa.f_name like '%{$keywords}%' or pro.model like '%{$keywords}%')  OR pur.content LIKE '%{$keywords}%')";
		}
        //筛选：0 全部 1 求购 2 供给
        if($type > 0) $where.=" and pur.type = $type ";
		$data = $this->model('purchase')->select('pur.id,pur.p_id,pur.user_id,pro.model,pur.unit_price,pur.store_house,fa.f_name,pur.input_time,pur.type,pur.content')->from('purchase pur')
            ->leftjoin('product pro','pur.p_id=pro.id')
            ->leftjoin('factory fa','pro.f_id=fa.fid')
            ->where($where)
            ->page($page,$size)
            ->order('pur.top desc,pur.input_time desc')
            ->getPage();
        foreach ($data['data'] as &$value) {
            $cus_con = M('user:customerContact')->getListByUserid($value['user_id']);
            $value['input_time'] = date("m-d H:i",$value['input_time']);
            $value['name'] = $cus_con['name'];
            $value['c_name'] = $this->model('customer')->select('c_name')->where('c_id='.$cus_con['c_id'])->getOne();
            $value['is_pass'] = $cus_con['is_pass'];
            $thumb=$this->model('contact_info')->select('thumb,thumbqq')->where('user_id='.$value['user_id'])->getRow();
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
            $value['thumb'] = $thumb['thumb'];
            //显示的内容
            if(empty($value['content'])){
                if($value['unit_price']==0.00 && empty($value['model']) && empty($value['f_name']) && empty($value['store_house'])){
                    $value['contents'] = '';
                }else{
                $value['contents'] = '价格'.$value['unit_price'].'元左右/'.$value['model'].'/'.$value['f_name'].'/'.$value['store_house'];
                }
            }elseif(!empty($value['content'])){
                if($value['unit_price']==0.00 && empty($value['model']) && empty($value['f_name']) && empty($value['store_house'])){
                    $value['contents'] = $value['content'];
                }else{
                    $value['contents'] = '价格'.$value['unit_price'].'元左右/'.$value['model'].'/'.$value['f_name'].'/'.$value['store_house'].'/'.$value['content'];
                }
            }
            $value['contents'] = str_replace($keywords,"<strong style='color: #ff5000;'>{$keywords}</strong>",$value['contents']);
            //网友说
            $value['says'] =  M('plasticzone:plasticMyMsg')->_getLiuYan($value['id']);
        }
        return $data;
	}
	
//	public function getReleaseMsg2($keywords,$page,$size,$userid,$type){
//	    $where = "pur.sync=6 and pur.sync=7";
//	    if(!empty($keywords)){
//	        //筛选产品类型
//	        $p_types = L('product_type');
//	        if(in_array($keywords, $p_types)){
//	            $keyValue = $this->_getProKey($p_types,$keywords);
//	        }
//	        $where.=" and ((fa.f_name like '%{$keywords}%' or pro.model like '%{$keywords}%')  OR pur.content LIKE '%{$keywords}%')";
//	    }
//	    //来源:获取塑料圈好友信息
//	    if(!empty($userid) && !empty($type)){
//	        $where.=" and pur.user_id=$userid and pur.type=$type ";
//	    }
//	    //$data = $this->select("pur.id,pur.p_id,pur.user_id,'' as model,pur.unit_price,pur.store_house,'' as f_name,pur.input_time,pur.type,pur.content")
//        $data = $this->model('purchase')
//            ->select("id,p_id,user_id,'' as model,unit_price,store_house,'' as f_name,input_time,type,content")
//           // ->from('purchase pur')
//	    ->where($where)
//	    ->page($page,$size)
//	    ->order('input_time desc')
//	    ->getPage();
//        var_dump($data);
//        showTrace();exit;
//	    foreach ($data['data'] as &$value) {
//	        $cus_con = M('user:customerContact')->getListByUserid($value['user_id']);
//	        // $value['product_type'] = L('product_type')[$value['product_type']];
//	        $value['input_time'] = date("m-d H:i",$value['input_time']);
//	        $value['name'] = $cus_con['name'];
//            $value['is_pass'] = $cus_con['is_pass'];
//	        $value['c_name'] = $this->model('customer')->select('c_name')->where('c_id='.$cus_con['c_id'])->getOne();
//	        $modelrow=$this->model('product')->select('f_id,model')->where('id='.$value['p_id'])->getRow();
//	        $value['model'] = $modelrow['model'];
//	        $fid = $modelrow['f_id'];
//	        $value['f_name'] = $this->model('factory')->select('f_name')->where('fid='.$fid)->getOne();
//	        // $thumb = $this->model('contact_info')->select('thumb')->where('user_id='.$value['user_id'])->getOne();
//	        // $value['thumb'] = FILE_URL."/upload/".$thumb;
//            $thumb=$this->model('contact_info')->select('thumb,thumbqq')->where('user_id='.$value['user_id'])->getRow();
//            if(empty($thumb['thumbqq']))
//            {
//                if (strstr($thumb['thumb'], 'http')) {
//                    $thumb['thumb']= $thumb['thumb'];
//                } else {
//                    if(empty($thumb['thumb'])){
//                        $thumb['thumb']= "http://statics.myplas.com/upload/16/09/02/logos.jpg";
//                    }else{
//                        $thumb['thumb']= FILE_URL."/upload/".$thumb['thumb'];
//                    }
//                }
//
//                // $value['thumb']= FILE_URL."/upload/".$value['thumb'];
//            }else{
//                $thumb['thumb']=$thumb['thumbqq'];
//            }
//            $value['thumb'] = $thumb['thumb'];
//	        //显示的内容
//	        if(empty($value['content'])){
//	            if($value['unit_price']==0.00 && empty($value['model']) && empty($value['f_name']) && empty($value['store_house'])){
//	                $value['contents'] = '';
//	            }else{
//	                $value['contents'] = '价格'.$value['unit_price'].'元左右/'.$value['model'].'/'.$value['f_name'].'/'.$value['store_house'];
//	            }
//	        }elseif(!empty($value['content'])){
//	            if($value['unit_price']==0.00 && empty($value['model']) && empty($value['f_name']) && empty($value['store_house'])){
//	                $value['contents'] = $value['content'];
//	            }else{
//	                $value['contents'] = '价格'.$value['unit_price'].'元左右/'.$value['model'].'/'.$value['f_name'].'/'.$value['store_house'].'/'.$value['content'];
//	            }
//	        }
//	        //网友说
//	        $value['says'] =  M('plasticzone:plasticMyMsg')->_getLiuYan($value['id']);
//	    }
//	    return $data;
//	}

    public function getReleaseMsg2($keywords,$page,$size,$userid,$type){
        $where = "pur.sync in (6,8) ";
        if(!empty($keywords)){
            //筛选产品类型
            $p_types = L('product_type');
            if(in_array($keywords, $p_types)){
                $keyValue = $this->_getProKey($p_types,$keywords);
            }
            $where.=" and ((fa.f_name like '%{$keywords}%' or pro.model like '%{$keywords}%')  OR pur.content LIKE '%{$keywords}%')";
        }
        //来源:获取塑料圈好友信息
        if(!empty($userid) && !empty($type)){
            $where.=" and pur.user_id=$userid and pur.type=$type ";
        }
        $data = $this->model('purchase')->select("pur.id,pur.p_id,pur.user_id,'' as model,pur.unit_price,pur.store_house,'' as f_name,pur.input_time,pur.type,pur.content")->from('purchase pur')
            ->where($where)
            ->page($page,$size)
            ->order('pur.input_time desc')
            ->getPage();//echo '<pre>';var_dump($data);showTrace();exit;
        foreach ($data['data'] as &$value) {
            $cus_con = M('user:customerContact')->getListByUserid($value['user_id']);
            // $value['product_type'] = L('product_type')[$value['product_type']];
            $value['input_time'] = date("m-d H:i",$value['input_time']);
            $value['name'] = $cus_con['name'];
            $value['is_pass'] = $cus_con['is_pass'];
            $value['c_name'] = $this->model('customer')->select('c_name')->where('c_id='.$cus_con['c_id'])->getOne();
            $modelrow=$this->model('product')->select('f_id,model')->where('id='.$value['p_id'])->getRow();
            $value['model'] = $modelrow['model'];
            $fid = $modelrow['f_id'];
            $value['f_name'] = $this->model('factory')->select('f_name')->where('fid='.$fid)->getOne();
            // $thumb = $this->model('contact_info')->select('thumb')->where('user_id='.$value['user_id'])->getOne();
            // $value['thumb'] = FILE_URL."/upload/".$thumb;
            $thumb=$this->model('contact_info')->select('thumb,thumbqq')->where('user_id='.$value['user_id'])->getRow();
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

                // $value['thumb']= FILE_URL."/upload/".$value['thumb'];
            }else{
                $thumb['thumb']=$thumb['thumbqq'];
            }
            $value['thumb'] = $thumb['thumb'];
            //显示的内容
            if(empty($value['content'])){
                if($value['unit_price']==0.00 && empty($value['model']) && empty($value['f_name']) && empty($value['store_house'])){
                    $value['contents'] = '';
                }else{
                    $value['contents'] = '价格'.$value['unit_price'].'元左右/'.$value['model'].'/'.$value['f_name'].'/'.$value['store_house'];
                }
            }elseif(!empty($value['content'])){
                if($value['unit_price']==0.00 && empty($value['model']) && empty($value['f_name']) && empty($value['store_house'])){
                    $value['contents'] = $value['content'];
                }else{
                    $value['contents'] = '价格'.$value['unit_price'].'元左右/'.$value['model'].'/'.$value['f_name'].'/'.$value['store_house'].'/'.$value['content'];
                }
            }
            //网友说
            $value['says'] =  M('plasticzone:plasticMyMsg')->_getLiuYan($value['id']);
        }
        return $data;
    }

    //获取公司
    //获取姓名
	//获取当前类型的键
    private function _getProKey($p_types,$keywords){
        foreach ($p_types as $key => $value) {
            if($value == strtoupper($keywords))
                return $key;
        }
    }
}