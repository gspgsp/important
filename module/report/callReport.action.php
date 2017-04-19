<?php 
/**
* 呼叫中心报表
*/
class callReportAction extends adminBaseAction
{       
	public function __init(){
        $this->db=M('public:common');
        $adminid=$_SESSION['adminid'];
        //获取当前的用户的pid，判断时候有下级成员来进行权限控制
        $pid=$this->db->model('admin')->select('pid')->where("admin_id=$adminid")->getOne();
        if(!$pid){//超管
            $listone=$this->db->model('phone_name')->getAll("select aname from p2p_phone_name where role_id > 33");//类似都是销售人员
            $listtwo=$this->db->model('adm_role')->getAll('select name from p2p_adm_role where pid=22');//销售团队
        }else{//非超管
            //获取管理员列表
            $adminlist=$this->db->model('admin')->select('admin_id,name,pid')->getAll();
            $tree=$this->tree($adminlist,$adminid);
            $treetrue='';
            foreach($tree as $t1){
                $treetrue.=$t1['admin_id'].',';//获取需要的管理员id
            }
            $treetrue.=$adminid.',';
            $treetrue=rtrim($treetrue,',');
            $where=' 1';
            if($pid){
                $where.=" and a.user_id in ($treetrue) ";
                $where1="admin_id in ($treetrue)";
            }
            $listone=$this->db->model('admin')->select('name as aname')->where($where1)->getAll();//下属人员的名字
            $listtwo=$this->db->from('adm_role_user `a`')
                    ->join('adm_role ar','a.role_id=ar.id')
                    ->select('ar.name as name')
                    ->where($where)
                    ->getAll();//角色名（战队名字）
            $listtwo=$this->a_array_unique($listtwo);
        }
        $this->assign('listone',$listone);
        $this->assign('listtwo',$listtwo);
	}
	public function init(){		
	}
    //去掉相同的数组
    public function a_array_unique($array){
        $out = array();
        foreach ($array as $key=>$value) {
            if (!in_array($value, $out)){
                $out[$key] = $value;
            }
        }
        return $out;
    }
    //无限级分类
    public function  tree($list,$pid=0,$level=0){
        static $tree = array();
        foreach($list as $v){
            if($v['pid'] == $pid){
                $v['level'] = $level;
                $tree[] = $v;
                $this->tree($list,$v['admin_id'],$level+1);
            }
        }
        return $tree;
    }


	public function calllist(){
         $action=sget('action','s');
         if(!empty($action)){
            $page = sget("pageIndex",'i',0); //页码
            $size = sget("pageSize",'i',50); //每页数
            $sortField = sget("sortField",'s','admin_id'); //排序字段
            $sortOrder = sget("sortOrder",'s','desc'); //排序
            $startDate=strtotime(date("Y-m-d"))+21600;
            $startTime=sget("startTime",'s','');
            if(empty($startTime)){
                $startTime=$startDate;
            }else{
                $startTime=  strtotime($startTime)+21600;
            }
            $endDate=$startTime+60000;
            $endTime=sget('endTime','s','');
            if(empty($endTime)){
                $endTime=$endDate;
            }else{
                $endTime=strtotime($endTime)+79200;
            }
            $name= sget('key_name','s','');
            $dpt= sget('key_dpt','s','');

            $where="1 ";
            $where.=" and role_id > 33 ";
            $where.=" and seat_phone >0";
            if(!empty($dpt)){
                $where.=" and cname='{$dpt}'";
            }

             // 关键词
             $key_type=sget('key_type','s','customer_manager');
             $keyword=sget('keyword','s');
             if(!empty($keyword)){
                 if($key_type=='customer_manager'){
                     $where.=" and aname like '%$keyword%'";
                 }elseif($key_type=='seat_phone'){
                     $where.=" and seat_phone=$keyword";
                 }
             }
//                if(!empty($name)){
//                    $where.=" and aname='{$name}'";
//                }

            $adminid=$_SESSION['adminid'];
            $pid=$this->db->model('admin')->select('pid')->where("admin_id=$adminid")->getOne();
            $adminlist=$this->db->model('admin')->select('admin_id,name,pid')->getAll();
            //p($adminid);
            $tree=$this->tree($adminlist,$adminid);
            $treetrue='';
            foreach($tree as $t1){
                $treetrue.=$t1['admin_id'].',';
            }
            $treetrue.=$adminid.',';
            $treetrue=rtrim($treetrue,',');
            if($pid){
                $where.=" and admin_id in ($treetrue) ";
            }
            $this->assign('startTime',$startTime);
            $this->assign('endTime',$endTime);
            $phonelist=$this->db->model('phone_name')->where($where)
                            ->page($page+1,$size)
                            ->order("$sortField $sortOrder")
                            ->getPage();

            $tmp=array();
            foreach($phonelist['data'] as $row){
                    //拨出次数和拨出时间
                    $sql2="select count(id) as out_num,sum(time) as out_time from p2p_api where phone='{$row['seat_phone']}' and callstatus='ou' and ctime>{$startTime} and ctime<{$endTime}";

                    $rs2=$this->db->model('api')->getAll($sql2);
                    //拨出有效次数
                    $sql3="select count(id) as out_eff_num from p2p_api where phone='{$row['seat_phone']}' and callstatus='ou' and time>0 and ctime>{$startTime} and ctime<{$endTime}";
                    $rs3=$this->db->model('api')->getAll($sql3);
                    //打入次数和打出时间
                    $sql4="select count(id) as in_num,sum(time) as in_time from p2p_api where phone='{$row['seat_phone']}' and callstatus='in' and ctime>{$startTime} and ctime<{$endTime}";
                    $rs4=$this->db->model('api')->getAll($sql4);
                    //打入有效次数
                    $sql5="select count(id) as in_eff_num from p2p_api where phone='{$row['seat_phone']}' and callstatus='in' and time>0 and ctime>{$startTime} and ctime<{$endTime}";
                    $rs5=$this->db->model('api')->getAll($sql5);

                    //呼出匹配数量和匹配时间
                    $sql6="SELECT count(distinct a.id) as sum,sum(a.time) as out_match_time
                            FROM `p2p_api` `a`
                            JOIN `p2p_phone_name` `c` ON a.phone=c.seat_phone
                            JOIN `p2p_customer_contact` `con` ON con.mobile=a.remark
                            JOIN `p2p_customer` `cus` ON cus.c_id=con.c_id
                            WHERE phone='{$row['seat_phone']}' and callstatus='ou' and a.ctime>{$startTime} and a.ctime<{$endTime} and cus.c_name is not null";
                    $rs6=$this->db->model('api')->getAll($sql6);

                    //匹配时间
                    $sql7="SELECT a.id,a.time,a.phone
                            FROM `p2p_api` `a`
                            JOIN `p2p_phone_name` `c` ON a.phone=c.seat_phone
                            JOIN `p2p_customer_contact` `con` ON con.mobile=a.remark
                            JOIN `p2p_customer` `cus` ON cus.c_id=con.c_id
                            WHERE phone='{$row['seat_phone']}' and callstatus='ou' and a.ctime>{$startTime} and a.ctime<{$endTime} and cus.c_name is not null";

                    $rs7=$this->db->model('api')->getAll($sql7);
                    $rs6[0]['out_match_time']= 0;
                    $ids2=array();
                    $newArr2=array();
                    foreach($rs7 as $row7){
                        if(!isset($ids2[$row7['id']])){
                            $ids2[$row7['id']]=$row7['id'];
                            $newArr2[]=$row7;
                        }
                    }
                    //if($row['seat_phone']=='214'){p($rs7);p($newArr2);showTrace();exit;}
                    foreach($newArr2 as $ese){
                        $rs6[0]['out_match_time'] += $ese['time'];
                    }






                    $a['intime']=$rs4[0]['in_time'];
                    $dayc=floor($a['intime']/86400);
                    $hourc=floor($a['intime']/60/60%24);
                    $minc=floor($a['intime']/60%60);
                    $sc=floor($a['intime']%60);
                    $str='';
                    $str.=empty($dayc)?'':$dayc.'天';
                    $str.=empty($hourc)?'':$hourc.'时';
                    $str.=empty($minc)?'':$minc.'分钟';
                    $str.=empty($sc)?'':$sc.'秒';
                    if(empty($str)){
                        $str="-";
                    }
                    $rs4[0]['in_time']=$str;


                $_tmpTime_match_ratio=$rs6[0]['out_match_time']/$rs2[0]['out_time'];

                $rs2[0]['out_time'] = $this->returnSomeTime($rs2[0]['out_time']);
                $rs6[0]['out_match_time'] = $this->returnSomeTime($rs6[0]['out_match_time']);
                    if(!empty($keyword)){
                        if($key_type=='in_num'){
                            if($rs4[0]['in_num']<$keyword) continue;
                        }elseif($key_type=='in_eff_num'){
                            if($rs5[0]['in_eff_num']<$keyword) continue;
                        }elseif($key_type=='out_num'){
                            if($rs2[0]['out_num']<$keyword) continue;
                        }elseif($key_type=='out_eff_num'){
                            if($rs3[0]['out_eff_num']<$keyword) continue;
                        }
                    }

                    $tmp[]=array(
                            'name'=>$row['aname'],
                            'dpt'=>$row['cname'],
                            'seat_phone'=>$row['seat_phone'],
                            'out_num'=>$rs2[0]['out_num'],//拨出次数
                            'out_time'=>$rs2[0]['out_time'],//拨出时间
                            'out_eff_num'=>$rs3[0]['out_eff_num'],//有效次数
                            'out_match_num'=>$rs6[0]['sum'],//客户匹配数量
                            'company_match_ratio'=>(sprintf(" %.4f",$rs6[0]['sum']/$rs2[0]['out_num'] )*100).'%',//公司匹配率
                            'out_match_time'=>$rs6[0]['out_match_time'],    //匹配时长
                            'time_match_ratio'=>(sprintf("%.4f",$_tmpTime_match_ratio)*100).'%',//匹配时长率
                            'in_num'=>$rs4[0]['in_num'],
                            'in_time'=>$rs4[0]['in_time'],
                            'in_eff_num'=>$rs5[0]['in_eff_num'],
                    );
            }//showTrace();
            $result=array('total'=>$phonelist['count'],'data'=>$tmp);
            $this->json_output($result);
         }
        $this->display('callReport.list.html');
    }
         
     public function callin(){
        $action=sget('action','s');
        $startDate=strtotime(date("Y-m-d"))+21600;
        $startTime=sget("startTime",'s','');
        $this->assign('startTime',$startTime);
        if(empty($startTime)){
         $startTime=$startDate;
        }else{
         $startTime=  strtotime($startTime)+21600;
        }
        $endDate=$startTime+60000;
        $endTime=sget('endTime','s','');
        $this->assign('endTime',$endTime);
        if(empty($endTime)){
         $endTime=$endDate;
        }else{
         $endTime=strtotime($endTime)+79200;
        }
        $seat_phone=sget('seat_phone','s');
        $this->assign('seat_phone',$seat_phone);
        $phone_status=sget('phone_status','s');//通话状态
        $this->assign('phone_status',$phone_status);
        if(!empty($action)){
        //if(true){
        $page = sget("pageIndex",'i',0); //页码
        $size = sget("pageSize",'i',20); //每页数
        $sortField = sget("sortField",'s','id'); //排序字段
        $sortOrder = sget("sortOrder",'s','desc'); //排序
        $name= sget('key_name','s','');
        $dpt= sget('key_dpt','s','');
        $where="a.callstatus='in'";
        $where.=" and a.ctime>{$startTime} and a.ctime<{$endTime}";
        if(!empty($dpt)){
            $where.=" and c.cname='{$dpt}'";
        }
        if(!empty($name)){
            $where.=" and c.aname='{$name}'";
        }
        if($phone_status==1){
         $where.=" and a.time>0";
        }elseif($phone_status==2){
         $where.=" and a.time=0";
        }
         // 关键词
         $key_type=sget('key_type','s','customer_manager');
         $keyword=sget('keyword','s');
         if(!empty($keyword)){
             if($key_type=='customer_manager'){
                 $where.=" and c.aname like '%$keyword%'";
             }elseif($key_type=='seat_phone'){
                 $where.=" and c.seat_phone=$keyword";
             }elseif($key_type=='remark'){
                 $where.=" and a.remark like '%$keyword%'";
             }
         }


        $adminid=$_SESSION['adminid'];
            $pid=$this->db->model('admin')->select('pid')->where("admin_id=$adminid")->getOne();
            $adminlist=$this->db->model('admin')->select('admin_id,name,pid')->getAll();
            //p($adminid);
            $tree=$this->tree($adminlist,$adminid);
            $treetrue='';
            foreach($tree as $t1){
                $treetrue.=$t1['admin_id'].',';
            }
            $treetrue.=$adminid.',';
            $treetrue=rtrim($treetrue,',');
            //p($treetrue);
            if($pid){
                $where.=" and c.admin_id in ($treetrue) ";
            }
             if(!empty($seat_phone)){
                 $where.=" and c.seat_phone=$seat_phone";
             }
            if($phone_status==1){
                $where.=" and a.time>0";
            }elseif($phone_status==2){
                $where.=" and a.time=0";
            }

        //showTrace();
        $phonelist=$this->db->model('api')->from('api as a')
                            ->join('phone_name as c','a.phone=c.seat_phone')
                            ->select("a.*,c.aname,c.cname")
                            ->page($page+1,$size)
                            ->where($where)
                            ->order("$sortField $sortOrder")
                            ->getPage();

        foreach($phonelist['data'] as &$row){
            $start=$row['ctime']-$row['time'];
            $start=date('Y-m-d H:i:s',$start);
            $row['start']=$start;
            $row['files']=$row['address'];
            if(empty($row['time'])){
                $row['ending']='响铃';
            }else{
                $row['ending']='接听成功';
            }
        }
            $result=array('total'=>$phonelist['count'],'data'=>$phonelist['data']);
            $this->json_output($result);
         }
         $this->display('callIn.list.html');
     }

    public function returnSomeTime($b=0){
        $a['outime'] = $b;
        $dayc=floor($a['outime']/86400);
        $hourc=floor($a['outime']/60/60%24);
        $minc=floor($a['outime']/60%60);
        $sc=floor($a['outime']%60);
        $str1='';
        $str1.=empty($dayc)?'':$dayc.'天';
        $str1.=empty($hourc)?'':$hourc.'时';
        $str1.=empty($minc)?'':$minc.'分钟';
        $str1.=empty($sc)?'':$sc.'秒';
        if(empty($str1)){
            $str1="-";
        }
        return $str1;
    }

         
     public function callout()
     {
         $action = sget('action', 's');
         $name = sget('key_name', 's', '');
         $dpt = sget('key_dpt', 's', '');
         $startDate=strtotime(date("Y-m-d"))+21600;
         $startTime=sget("startTime",'s','');
         $this->assign('startTime',$startTime);
         if(empty($startTime)){
             $startTime=$startDate;
         }else{
             $startTime=  strtotime($startTime)+21600;
         }

         $endDate=$startTime+60000;
         $endTime=sget('endTime','s','');
         $this->assign('endTime',$endTime);
         if(empty($endTime)){
             $endTime=$endDate;
         }else{
             $endTime=strtotime($endTime)+79200;
         }
         $seat_phone=sget('seat_phone','i');
         $this->assign('seat_phone',$seat_phone);
         $phone_status=sget('phone_status','i');
         $this->assign('phone_status',$phone_status);
         if (!empty($action)) {
             $page = sget("pageIndex",'i',0); //页码
             $size = sget("pageSize",'i',20); //每页数
             $sortField = sget("sortField",'s','id'); //排序字段
             $sortOrder = sget("sortOrder",'s','desc'); //排序
             $name = sget('key_name', 's', '');
             $dpt = sget('key_dpt', 's', '');
             $where="callstatus='ou'";
             $where.=" and a.ctime>{$startTime} and a.ctime<{$endTime}";
             if(!empty($dpt)){
                 $where.=" and c.cname='{$dpt}'";
             }
             // 关键词
             $key_type = sget('key_type', 's', 'customer_manager');
             $keyword = sget('keyword', 's');
             if (!empty($keyword)) {
                 if ($key_type == 'customer_manager') {
                     $where .= " and c.aname like '%$keyword%'";
                 } elseif ($key_type == 'seat_phone') {
                     $where .= " and c.seat_phone=$keyword";
                 } elseif ($key_type == 'remark') {
                     $where .= " and a.remark like '%$keyword%'";
                 }
             }
             $adminid = $_SESSION['adminid'];
             $pid = $this->db->model('admin')->select('pid')->where("admin_id=$adminid")->getOne();
             $adminlist = $this->db->model('admin')->select('admin_id,name,pid')->getAll();
             //p($adminid);
             $tree = $this->tree($adminlist, $adminid);
             $treetrue = '';
             foreach ($tree as $t1) {
                 $treetrue .= $t1['admin_id'] . ',';
             }
             $treetrue .= $adminid . ',';
             $treetrue = rtrim($treetrue, ',');
             //p($treetrue);
             if ($pid) {
                 $where .= " and c.admin_id in ($treetrue) ";
             }
            if(!empty($seat_phone)){
                $where.=" and c.seat_phone=$seat_phone";
            }
             if($phone_status==1){
                 $where.=" and a.time>0";
             }elseif($phone_status==2){
                 $where.=" and a.time=0";
             }
                 $phonelist = $this->db->model('api')->from('api as a')
                     ->join('phone_name as c', 'a.phone=c.seat_phone')
                     //->leftjoin('customer_contact con','(con.mobile+0)=(a.remark+0) or (con.tel+0)=(a.remark+0)')
                     ->leftjoin('customer_contact con','con.mobile=a.remark')
                     ->leftjoin('customer as cus','cus.c_id=con.c_id')
                     ->where($where)
                     ->select("distinct(a.id) as wss,a.*,c.aname,c.admin_id,c.cname,cus.c_name")
                     ->page($page + 1, $size)
                     ->order("$sortField $sortOrder")
                     ->getPage();

             $_tmpAdmin=$this->db->model('adm_role_user')->where("user_id=$adminid")->getAll();

             $_tmpBool = True;
             foreach($_tmpAdmin as $row1){
                 if($row1['role_id']==2){
                     $_tmpBool = True; break;
                 }
                 $_tmpBool = False;
             }

                $_newPhoneList=array();
                $ids = array();
                 foreach ($phonelist['data'] as $k=>&$row) {
                     $start = $row['ctime'] - $row['time'];
                     $start = date('Y-m-d H:i:s', $start);
                     $row['start'] = $start;
                     $row['files'] = $row['address'];
                     if (empty($row['time'])) {
                         $row['ending'] = '响铃';
                     } else {
                         $row['ending'] = '拨号成功';
                     }
                     if(empty($row['c_name'])) $row['c_name']='-';

                     if(!$_tmpBool && $row['admin_id']!=$adminid){
                         if(count($row['remark'])<5){
                             $row['remark'] = substr_replace($row['remark'],'****',-2);
                         }else{
                             $row['remark'] = substr_replace($row['remark'],'****',-4);
                         }
                     }
                    if(!isset($ids[$row['id']])){
                        $ids[$row['id']] = $row['id'];
                        $_newPhoneList[] =$row;
                    }

                 }

             $phonelistall = $this->db->model('api')->from('api as a')
                 ->join('phone_name as c', 'a.phone=c.seat_phone')
                 //->leftjoin('customer_contact con','(con.mobile+0)=(a.remark+0) or (con.tel+0)=(a.remark+0)')
                 ->leftjoin('customer_contact con','con.mobile=a.remark')
                 ->leftjoin('customer as cus','cus.c_id=con.c_id')
                 ->where($where)
                 ->select("count(distinct(a.id)) as wss")
                 ->order("$sortField $sortOrder")
                 ->getOne();

                 $result = array('total' => $phonelistall, 'data' => $_newPhoneList);
                 $this->json_output($result);
             }
            $this->assign('sdf',33);
             $this->display('callOut.list.html');
         }


         
         
         public function showInfo(){
             $info=sget('info','s');
             $this->assign('info',$info);
             $this->display('callInfo.list.html');
         }

         /**
     * 导出excel
     * @access public 
     * @return html
     */
    public function download(){

               $sortField = sget("sortField",'s','admin_id'); //排序字段
                $sortOrder = sget("sortOrder",'s','desc'); //排序
                $startDate=strtotime(date("Y-m-d"))+21600;
                $startTime=sget("startTime",'s','');
                if(empty($startTime)){
                    $startTime=$startDate;
                }else{
                    $startTime=  strtotime($startTime)+21600;
                }            
                $endDate=$startTime+60000;
                $endTime=sget('endTime','s','');
                if(empty($endTime)){
                    $endTime=$endDate;
                }else{
                    $endTime=strtotime($endTime)+79200;
                }            
                $name= sget('key_name','s','');
                $dpt= sget('key_dpt','s','');
                $where="1 ";
                $where.=" and role_id > 33 ";
                $where.=" and seat_phone >0";
                if(!empty($dpt)){
                    $where.=" and cname='{$dpt}'";
                }
                if(!empty($name)){
                    $where.=" and aname='{$name}'";
                }

                $adminid=$_SESSION['adminid'];
                $pid=$this->db->model('admin')->select('pid')->where("admin_id=$adminid")->getOne();
                $adminlist=$this->db->model('admin')->select('admin_id,name,pid')->getAll();
                //p($adminid);
                $tree=$this->tree($adminlist,$adminid);
                $treetrue='';
                foreach($tree as $t1){
                    $treetrue.=$t1['admin_id'].',';
                }
                $treetrue.=$adminid.',';
                $treetrue=rtrim($treetrue,',');
                if($pid){
                    $where.=" and admin_id in ($treetrue) ";
                }
                $this->assign('startTime',$startTime);
                $this->assign('endTime',$endTime);
                $phonelist=$this->db->model('phone_name')->where($where)
                                //->page($page+1,$size)
                                ->order("$sortField $sortOrder")
                                //->getPage();
                                ->getAll();
               // p($phonelist);
               // showTrace();
               // exit;
                $tmp=array();
                foreach($phonelist as $row){ 
                        //拨出次数和拨出时间
                        $sql2="select count(id) as out_num,sum(time) as out_time from p2p_api where phone='{$row['seat_phone']}' and callstatus='ou' and ctime>{$startTime} and ctime<{$endTime}";

                        $rs2=$this->db->model('api')->getAll($sql2);
                        //拨出有效次数
                        $sql3="select count(id) as out_eff_num from p2p_api where phone='{$row['seat_phone']}' and callstatus='ou' and time>0 and ctime>{$startTime} and ctime<{$endTime}";
                        $rs3=$this->db->model('api')->getAll($sql3);
                        //打入次数和打出时间
                        $sql4="select count(id) as in_num,sum(time) as in_time from p2p_api where phone='{$row['seat_phone']}' and callstatus='in' and ctime>{$startTime} and ctime<{$endTime}";
                        $rs4=$this->db->model('api')->getAll($sql4);
                        //打入有效次数
                        $sql5="select count(id) as in_eff_num from p2p_api where phone='{$row['seat_phone']}' and callstatus='in' and time>0 and ctime>{$startTime} and ctime<{$endTime}";
                        $rs5=$this->db->model('api')->getAll($sql5);
                        $a['intime']=$rs4[0]['in_time'];
                        $dayc=floor($a['intime']/86400);
                        $hourc=floor($a['intime']/60/60%24);
                        $minc=floor($a['intime']/60%60);
                        $sc=floor($a['intime']%60);
                        $str='';
                        $str.=empty($dayc)?'':$dayc.'天';
                        $str.=empty($hourc)?'':$hourc.'时';
                        $str.=empty($minc)?'':$minc.'分钟';
                        $str.=empty($sc)?'':$sc.'秒';
                        if(empty($str)){
                            $str="无";
                        }
                        $rs4[0]['in_time']=$str;
                        $a['outime']=$rs2[0]['out_time'];
                        $dayc=floor($a['outime']/86400);
                        $hourc=floor($a['outime']/60/60%24);
                        $minc=floor($a['outime']/60%60);
                        $sc=floor($a['outime']%60);
                        $str1='';
                        $str1.=empty($dayc)?'':$dayc.'天';
                        $str1.=empty($hourc)?'':$hourc.'时';
                        $str1.=empty($minc)?'':$minc.'分钟';
                        $str1.=empty($sc)?'':$sc.'秒';
                        if(empty($str1)){
                            $str1="无";
                        }
                        $rs2[0]['out_time']=$str1;

                        $tmp[]=array(    
                                'name'=>$row['aname'],
                                'dpt'=>$row['cname'],
                                'seat_phone'=>$row['seat_phone'],
                                'out_num'=>$rs2[0]['out_num'],//拨出次数
                                'out_time'=>$rs2[0]['out_time'],//拨出时间
                                'out_eff_num'=>$rs3[0]['out_eff_num'],//有效次数
                                'in_num'=>$rs4[0]['in_num'],
                                'in_time'=>$rs4[0]['in_time'],
                                'in_eff_num'=>$rs5[0]['in_eff_num'],
                        );
                    }

        // foreach($list as &$v){
        //     $v['input_time']=$v['input_time']>1000 ? date("Y-m-d H:i:s",$v['input_time']) : '-';
        //     $v['update_time']=$v['update_time']>1000 ? date("Y-m-d H:i:s",$v['update_time']) : '-';
        //     $v['product_type'] = L('product_type')[$v['product_type']];
        //     $v['f_id'] = $this->_getFactoryName($v['f_id']);
        //     $v['c_id'] = M('user:customer')->getColByName($v['c_id']);
        //     $v['process_type'] = L('process_level')[$v['process_type']];
        //     $v['bargain'] = L('bargain')[$v['bargain']];
        //     $v['username'] = M('rbac:adm')->getUserByCol($v['customer_manager'],'name');
        //     if($v['origin']){
        //         $areaArr = explode('|', $v['origin']);
        //         $v['origin'] = M('system:region')->get_name(array($areaArr[0],$areaArr[1]));
        //     }
        //     if($v['provinces']>0){
        //         $v['provinces'] = M('system:region')->get_name($v['provinces']);
        //     }
        // }

        $str = '<meta http-equiv="Content-Type" content="text/html; charset=utf8" /><table width="100%" border="1" cellspacing="0">';

        $str .= '<tr><td>部门</td><td>业务人员</td><td>座机号码</td><td>呼出次数</td><td>有效呼出次数</td>
                    <td>呼出总时长</td><td>呼入次数</td><td>有效呼入次数</td><td>呼入总时长</td>
                </tr>';
        foreach($tmp as $k=>$v){
            $str .= "<tr><td style='vnd.ms-excel.numberformat:@'>".$v['dpt']."</td><td>".$v['name']."</td><td>".$v['seat_phone']."</td><td>".$v['out_num']."</td><td>".$v['out_eff_num']."</td>
                        <td>".$v['out_time']."</td><td>".$v['in_num']."</td><td>".$v['in_eff_num']."</td><td>".$v['in_time']."</td>                      
                    </tr>";
        }
        $str .= '</table>';
        $filename = 'callReport.'.date("Y-m-d");
        header("Content-type: application/vnd.ms-excel; charset=utf-8");
        header("Content-Disposition: attachment; filename=$filename.xls");
        echo $str;
        exit;
    }
}
