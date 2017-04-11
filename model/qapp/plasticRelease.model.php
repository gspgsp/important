<?php

/**
 *塑料圈发布报价-zhanpeng
 */
class plasticReleaseModel extends model
{

    public function __construct ()
    {
        parent::__construct (C ('db_default'), 'purchase');
    }

    public function getReleaseMsg ($keywords, $page, $size, $type, $sortField1, $sortField2, $user_id)
    {
        $where = "pur.sync = 6";
        //$where ="1";
        /**
         *
         * 华东地区（包括山东、江苏、安徽、浙江、福建、上海）；ec
         * 华南地区（包括广东、广西、海南）；sc
         * 华中地区（包括湖北、湖南、河南、江西）；cc
         * 华北地区（包括北京、天津、河北、山西、内蒙古）；nc
         * 西北地区（包括宁夏、新疆、青海、陕西、甘肃）；
         * 西南地区（包括四川、云南、贵州、西藏、重庆）；
         * 东北地区（包括辽宁、吉林、黑龙江）；
         * 台港澳地区（包括台湾、香港、澳门）
         */
        //华北
        $arrNC = array('北京', '天津', '河北', '山西', '内蒙古');
        //华南
        $arrSC = array('广东', '广西', '海南');
        //华东
        $arrEC = array('上海', '江苏', '浙江', '山东', '安徽', '福建');
        //华中
        $arrCC = array('湖北', '湖南', '河南', '江西');
        if (!empty($keywords)) {
            $keywords = strtoupper ($keywords);
            $where .= " and ((fa.f_name like '%{$keywords}%' or pro.model like '%{$keywords}%')  OR pur.content LIKE '%{$keywords}%')";
        }
        //筛选：0 全部 1 求购 2 供给
        if ($type > 0) $where .= " and pur.type = $type ";

        /**
         * sortField2的筛选
         */
        if ($sortField2 == 'DEMANDORSUPPLY') $where .= " and pur.user_id=$user_id";
        if ($sortField2 == 'CONCERN') {
            //$type   1粉丝 2关注
            $f_type = 2;
            $data = M ('qapp:plasticIntroduction')->getMyFuns ($user_id, $f_type, $page, $size);

            $tmp = array();//关注人的id；
            foreach ($data['data'] as $key => $value) {
                if(!empty($value['focused_id']['user_id'])) {
                    $tmp[] = $value['focused_id']['user_id'];
                }
            }
            $tmpStr = implode (',', $tmp);
            $where .= " and pur.user_id in($tmpStr)";

        } elseif ($sortField2 == 'AUTO') {
//            if($page>1){
//                $data['count']=1;
//                return $data;
//            }
            // $size=20;
            //$sql="select * from p2p_suggestion_purchase where user_id=$user_id order by update_time desc,create_time desc limit ".($page-1)*$size.",".$size;
            //$data4=$this->model('suggestion_purchase')->getAll($sql);//p(33);showTrace();exit;
            //$data4=$this->model('suggestion_purchase')->where('user_id='.$user_id)->order('update_time desc,create_time desc')->limit(10,20)->getAll();
            //$data=$this->model('suggestion_purchase')->where('user_id=9266')->order('update_time desc,create_time desc')->getAll();
            // $data2=$this->model('purchase')->select('id')->where('user_id!='.$user_id)->order('input_time desc')->limit(20)->getAll();

            //p($data2);showTrace();exit;
            //p($data);
//            $tmp=array();
//            foreach($data4 as $key=>$value){
//                $tmp[]=$value['outter_id'];
//            }
//            if(count($data4)<=20&&count($data4)>=0){
//                $tmp2=array();
//                foreach($data2 as $v){
//                    $tmp2[]=$v['id'];
//                }
//            }
            $where2 = $where;
//            $tmp=array_slice($tmp,0,20);
//            $tmpStr=implode(',',$tmp);
//            if(empty($data)){
//                $data['data']='';
//                return $data;
//            }else{
            // $where.=" and pur.id in($tmpStr) ";
            //}
        }

        function change_to_quotes ($str)
        {
            return sprintf ("'%s'", $str);
        }


        if ($sortField1 == 'ALL') {

        } elseif ($sortField1 == 'NC') {
            $str = implode (',', array_map ('change_to_quotes', $arrNC));
            $where .= " and (info.adistinct='NC' or (info.adistinct is null and info.mobile_province in($str) )) ";
            $where2 .= " and info.mobile_province in($str) ";
        } elseif ($sortField1 == 'SC') {
            $str = implode (',', array_map ('change_to_quotes', $arrSC));
            $where .= " and (info.adistinct='SC' or (info.adistinct is null and info.mobile_province in($str) )) ";
            $where2 .= " and info.mobile_province in($str) ";
        } elseif ($sortField1 == 'EC') {
            $str = implode (',', array_map ('change_to_quotes', $arrEC));
            $where .= " and (info.adistinct='EC' or (info.adistinct is null and info.mobile_province in($str) ))  ";
            $where2 .= " and info.mobile_province in($str) ";
        } elseif ($sortField1 == 'OTHER') {
            $arrtmp = array_merge ($arrNC, $arrSC, $arrEC);
            $str = implode (',', array_map ('change_to_quotes', $arrtmp));
            $where .= " and info.mobile_province not in($str) ";
            $where2 .= " and info.mobile_province not in($str) ";
        }

        $this->model ('purchase')->select ('pur.id,pur.p_id,pur.user_id,pro.model,pur.unit_price,pur.store_house,fa.f_name,pur.input_time,pur.type,pur.content')->from ('purchase pur')
            ->leftjoin ('product pro', 'pur.p_id=pro.id')
            ->leftjoin ('factory fa', 'pro.f_id=fa.fid')
            ->leftjoin ('contact_info info', 'info.user_id=pur.user_id');
        if ($sortField2 == 'AUTO') {
            $size = 20;
            $where .= " and sug_pur.user_id=$user_id";
            $data = $this->leftjoin ('suggestion_purchase sug_pur', 'sug_pur.outter_id=pur.id')
                ->where ($where)
                ->page ($page, $size)
                //->order('pur.id desc,sug_pur.update_time desc,sug_pur.create_time desc,pur.input_time desc')
                //->order('sug_pur.update_time desc,sug_pur.create_time desc,pur.input_time desc')
                ->order ('sug_pur.update_time desc,sug_pur.create_time desc,pur.id desc')
                ->getPage ();

            //p($page);var_dump($data);showTrace();exit;
            //return $data;

//            if(empty($data['data'])&&$page<=10){
//                $where2.=" and pur.user_id!=$user_id";
//                $data5=$this->model('purchase')->select('pur.id,pur.p_id,pur.user_id,pro.model,pur.unit_price,pur.store_house,fa.f_name,pur.input_time,pur.type,pur.content')->from('purchase pur')
//                    ->leftjoin('product pro','pur.p_id=pro.id')
//                    ->leftjoin('factory fa','pro.f_id=fa.fid')
//                    ->leftjoin('contact_info info','info.user_id=pur.user_id')
//                    ->where($where2)
//                    ->page($page,$size)
//                    ->order('pur.id desc')
//                    ->getPage();
//                foreach($data5['data'] as $row){
//                    $data['data'][]=$row;
//                }
//                if(empty($data['count'])) $data['count']=$data5['count'];
//            }elseif($page>=11){
//                $data['count']=1;
//                return $data;
//            }


        } else {
            $data = $this->where ($where)
                ->page ($page, $size)
                ->order ('pur.top desc,pur.id desc')
                ->getPage ();
            //SELECT COUNT(*) AS num FROM `p2p_purchase` `pur` LEFT JOIN `p2p_product` `pro` ON pur.p_id=pro.id LEFT JOIN `p2p_factory` `fa` ON pro.f_id=fa.fid LEFT JOIN `p2p_contact_info` `info` ON info.user_id=pur.user_id WHERE pur.sync = 6 and pur.user_id in(37088) LIMIT 1
        }
        //p($data);showTrace();exit;
        if (!empty($data['data'])) {
            foreach ($data['data'] as $key => &$value) {
                $cus_con = M ('user:customerContact')->getListByUserid ($value['user_id']);
                $value['input_time'] = date ("m-d H:i", $value['input_time']);
                $value['name'] = $cus_con['name'];
                if (empty($value['name'])) unset($data['data'][$key]);
                $value['c_name'] = $this->model ('customer')->select ('c_name')->where ('c_id=' . $cus_con['c_id'])->getOne ();
                if (empty($value['c_name'])) unset($data['data'][$key]);
                $value['is_pass'] = $cus_con['is_pass'];
                $thumb = $this->model ('contact_info')->select ('thumb,thumbqq,mobile_province')->where ('user_id=' . $value['user_id'])->getRow ();

                if (empty($thumb['thumbqq'])) {
                    if (strstr ($thumb['thumb'], 'http')) {
                        $thumb['thumb'] = $thumb['thumb'];
                    } else {
                        if (empty($thumb['thumb'])) {
                            $thumb['thumb'] = "http://statics.myplas.com/upload/16/09/02/logos.jpg";
                        } else {
                            $thumb['thumb'] = FILE_URL . "/upload/" . $thumb['thumb'];
                        }
                    }
                } else {
                    $thumb['thumb'] = $thumb['thumbqq'];
                }
                $value['thumb'] = $thumb['thumb'];
                //显示的内容
                if (empty($value['content'])) {
                    if ($value['unit_price'] == 0.00 && empty($value['model']) && empty($value['f_name']) && empty($value['store_house'])) {
                        $value['contents'] = '';
                    } else {
                        $value['contents'] = '价格' . $value['unit_price'] . '元左右/' . $value['model'] . '/' . $value['f_name'] . '/' . $value['store_house'];
                    }
                } elseif (!empty($value['content'])) {
                    if ($value['unit_price'] == 0.00 && empty($value['model']) && empty($value['f_name']) && empty($value['store_house'])) {
                        $value['contents'] = $value['content'];
                    } else {
                        $value['contents'] = '价格' . $value['unit_price'] . '元左右/' . $value['model'] . '/' . $value['f_name'] . '/' . $value['store_house'] . '/' . $value['content'];
                    }
                }
                $value['contents'] = str_replace ($keywords, "<strong style='color: #ff5000;'>{$keywords}</strong>", $value['contents']);
                //网友说
                //$value['says'] =  M('plasticzone:plasticMyMsg')->_getLiuYan($value['id']);
                $value['saysCount'] = (int)M ('qapp:plasticMyMsg')->_getLiuYanDirectLine ($value['id'], $value['user_id'])['count'];
//            $value['deliverPrice'] = M('qapp:plasticQuote')->where("pur_id=".$value['id'])->order('input_time desc')->limit(1)->getRow()['price'];
//
//            if(empty($value['deliverPrice'])){
//                $value['deliverPrice']=$value['unit_price'];
//            }

                $_tmp = M ('qapp:plasticQuote')->getPurchasePrice ($value['id'], $value['user_id']);
                $value['deliverPriceCount'] = empty($_tmp['count'])?0:$_tmp['count'];
            }
        }
        //重新赋索引，为的是在json格式传的时候，来进行数组拼接的，
        $data['data'] = array_values ($data['data']);

        //p($data);showTrace();exit;
        return $data;
    }

    public function getReleaseMsgDetail ($id, $own_id, $user_id)
    {
        $where = "pur.id=$id";
        $detail = M ('product:purchase')->getPurchaseLeftById ($where, null, null);//p($detail);showTrace();exit;
        $value = $detail;
        if (empty($value['content'])) {
            if ($value['unit_price'] == 0.00 && empty($value['model']) && empty($value['f_name']) && empty($value['store_house'])) {
                $value['contents'] = '';
            } else {
                $value['contents'] = '价格' . $value['unit_price'] . '元左右/' . $value['model'] . '/' . $value['f_name'] . '/' . $value['store_house'];
            }
        } elseif (!empty($value['content'])) {
            if ($value['unit_price'] == 0.00 && empty($value['model']) && empty($value['f_name']) && empty($value['store_house'])) {
                $value['contents'] = $value['content'];
                $value['b_and_s'] = '';
                $value['deal_price'] = '';
            } else {
                $value['contents'] = '价格' . $value['unit_price'] . '元左右/' . $value['model'] . '/' . $value['f_name'] . '/' . $value['store_house'] . '/' . $value['content'];
            }
        }
        $value['input_time'] = date ("m-d H:i", $value['input_time']);
        $value['saysCount'] = (int)M ('qapp:plasticMyMsg')->_getLiuYanDirectLine ($value['id'], $value['user_id'])['count'];
//        $value['deliverPrice'] = M('qapp:plasticQuote')->where("pur_id=$id")->order('input_time desc')->limit(1)->getRow()['price'];
//        if(empty($value['deliverPrice'])){
//            $value['deliverPrice']=$value['unit_price'];
//        }
        $data = M ('qapp:plasticPerson')->getPersonInfo ($own_id);
        //关注的状态
        $status = $this->model ('weixin_fans')->select ('status')->where ("user_id=$user_id and focused_id=$own_id")->getOne ();
        // p(M('qapp:plasticPersonalInfo')->getConut(3858,1));exit;
//        $status = M("qapp:plasticPersonalInfo")->getAttentionStatus($user_id,$own_id);
        $value['deliverPriceCount'] = M ('qapp:plasticQuote')->getPurchasePrice ($id, $own_id)['count'];
        $data['status'] = $status == 1 ? '已关注' : '关注';
        $value['info'] = $data;
        $detail = $value;

        return $detail;
    }


    public function getReleaseMsgDetailReply ($id, $user_id, $page, $size)
    {
        $where1 = 'pur.sync = 6';
        $data2 = M ('qapp:plasticMyMsg')->_getLiuYanDirectLine ($id, $user_id, $page, $size);//p($data2);exit;
        foreach ($data2['data'] as $key => &$value) {
            $timeSub = time () - $value['input_time'];
            if ((int)($timeSub / (3600 * 24)) > 0) {
                $value['input_time'] = (int)($timeSub / (3600 * 24)) . '天前';
            } elseif ((int)($timeSub / (3600)) > 0) {
                $value['input_time'] = (int)($timeSub / (3600)) . '小时前';
            } elseif ((int)($timeSub / (60)) > 0) {
                $value['input_time'] = (int)($timeSub / (60)) . '分钟前';
            } else {
                $value['input_time'] = $timeSub . '秒前';
            }
            $data = $this->select ('con.user_id,con.name,con.c_id,con.is_pass,con.mobile,con.sex,info.thumb,info.thumbqq,info.thumbcard,cus.c_name,cus.need_product,cus.address')
                ->from ('customer_contact con')
                ->leftjoin ('contact_info info', 'con.user_id=info.user_id')
                ->leftjoin ('customer cus', 'con.c_id=cus.c_id')
                ->where ("con.user_id=" . $value['user_id'])
                ->getRow ();
            if (empty($data['thumbqq'])) {
                if (strstr ($data['thumb'], 'http')) {
                    $data['thumb'] = $data['thumb'];
                } else {
                    if (empty($data['thumb'])) {
                        $data['thumb'] = "http://statics.myplas.com/upload/16/09/02/logos.jpg";
                    } else {
                        $data['thumb'] = FILE_URL . "/upload/" . $data['thumb'];
                    }
                }
            } else {
                $data['thumb'] = $data['thumbqq'];
            }
            $data['sex'] = L ('sex')[$data['sex']];
            $value['info'] = $data;
        }

        return $data2;
    }

    public function getReleaseMsg2 ($keywords, $page, $size, $userid, $type)
    {
        $where = "pur.sync = 6 ";
        if (!empty($keywords)) {
            //筛选产品类型
            $p_types = L ('product_type');
            if (in_array ($keywords, $p_types)) {
                $keyValue = $this->_getProKey ($p_types, $keywords);
            }
            $where .= " and ((fa.f_name like '%{$keywords}%' or pro.model like '%{$keywords}%')  OR pur.content LIKE '%{$keywords}%')";
        }
        //来源:获取塑料圈好友信息
        if (!empty($userid) && !empty($type)) {
            $where .= " and pur.user_id=$userid and pur.type=$type ";
        }
        $data = $this->model ('purchase')->select ("pur.id,pur.p_id,pur.user_id,'' as model,pur.unit_price,pur.store_house,'' as f_name,pur.input_time,pur.type,pur.content")->from ('purchase pur')
            ->where ($where)
            ->page ($page, $size)
            ->order ('pur.input_time desc')
            ->getPage ();//echo '<pre>';var_dump($data);showTrace();exit;
        foreach ($data['data'] as &$value) {
            $cus_con = M ('user:customerContact')->getListByUserid ($value['user_id']);
            // $value['product_type'] = L('product_type')[$value['product_type']];
            $value['input_time'] = date ("m-d H:i", $value['input_time']);
            $value['name'] = $cus_con['name'];
            $value['is_pass'] = $cus_con['is_pass'];
            $value['c_name'] = $this->model ('customer')->select ('c_name')->where ('c_id=' . $cus_con['c_id'])->getOne ();
            $modelrow = $this->model ('product')->select ('f_id,model')->where ('id=' . $value['p_id'])->getRow ();
            $value['model'] = $modelrow['model'];
            $fid = $modelrow['f_id'];
            $value['f_name'] = $this->model ('factory')->select ('f_name')->where ('fid=' . $fid)->getOne ();
            // $thumb = $this->model('contact_info')->select('thumb')->where('user_id='.$value['user_id'])->getOne();
            // $value['thumb'] = FILE_URL."/upload/".$thumb;
            $thumb = $this->model ('contact_info')->select ('thumb,thumbqq')->where ('user_id=' . $value['user_id'])->getRow ();
            if (empty($thumb['thumbqq'])) {
                if (strstr ($thumb['thumb'], 'http')) {
                    $thumb['thumb'] = $thumb['thumb'];
                } else {
                    if (empty($thumb['thumb'])) {
                        $thumb['thumb'] = "http://statics.myplas.com/upload/16/09/02/logos.jpg";
                    } else {
                        $thumb['thumb'] = FILE_URL . "/upload/" . $thumb['thumb'];
                    }
                }

                // $value['thumb']= FILE_URL."/upload/".$value['thumb'];
            } else {
                $thumb['thumb'] = $thumb['thumbqq'];
            }
            $value['thumb'] = $thumb['thumb'];
            //显示的内容
            if (empty($value['content'])) {
                if ($value['unit_price'] == 0.00 && empty($value['model']) && empty($value['f_name']) && empty($value['store_house'])) {
                    $value['contents'] = '';
                } else {
                    $value['contents'] = '价格' . $value['unit_price'] . '元左右/' . $value['model'] . '/' . $value['f_name'] . '/' . $value['store_house'];
                }
            } elseif (!empty($value['content'])) {
                if ($value['unit_price'] == 0.00 && empty($value['model']) && empty($value['f_name']) && empty($value['store_house'])) {
                    $value['contents'] = $value['content'];
                } else {
                    $value['contents'] = '价格' . $value['unit_price'] . '元左右/' . $value['model'] . '/' . $value['f_name'] . '/' . $value['store_house'] . '/' . $value['content'];
                }
            }
            //网友说
            $value['says'] = M ('plasticzone:plasticMyMsg')->_getLiuYan ($value['id']);
        }

        return $data;
    }

    //获取公司
    //获取姓名
    //获取当前类型的键
    private function _getProKey ($p_types, $keywords)
    {
        foreach ($p_types as $key => $value) {
            if ($value == strtoupper ($keywords))
                return $key;
        }
    }
}