<?php
class customerBaseModel extends model
{
    protected $apiKey,$forcedUpdate,$updateTime;
    //初始化模型
    public function __construct()
    {
        parent::__construct(C('db_default'), 'customer_base');
        //获取企业信用的配置
        $tmp= M('system:setting')->get('creditlimit')['creditlimit'];
        $this->apiKey=$tmp['creditcode'];
        $this->forcedUpdate=$tmp['forcedupdate'];
        $this->updateTime = (int)$tmp['updatetime'];
    }

    public function insertAll($res=[]){
        /**
         * $arr=array(
        'name'=>$temp['Name'],
        'register_no'=>$temp['No'],
        'belong_org'=>$temp['BelongOrg'],
        'oper_name'=>$temp['OperName'],
        'start_date'=>strtotime($temp['StartDate']),
        'end_date'=>strtotime($temp['EndDate']),
        'status'=>$temp['Status'],
        'province'=>$temp['Province'],
        'update_date'=>strtotime($temp['UpdatedDate']),
        'credit_code'=>$temp['CreditCode'],
        'register_capi'=>$temp['RegistCapi'],
        'econkind'=>$temp['EconKind'],
        'industry'=>$temp['Industry']['Industry'],
        'sub_industry'=>$temp['Industry']['SubIndustry'],
        'address'=>$temp['Address'],
        'scope'=>$temp['Scope'],
        'term_start'=>strtotime($temp['TermStart']),
        'term_end'=>strtotime($temp['TeamEnd']),
        'check_date'=>strtotime($temp['CheckDate']),
        'phone_number'=>$temp['ContactInfo']['PhoneNumber'],
        'email'=>$temp['ContactInfo']['Email'],
        'website_name'=>$temp['ContactInfo']['WebSite'][0]['Name'],
        'website_url'=>$temp['ContactInfo']['WebSite'][0]['Url'],
        'input_time'=>CORE_TIME,
        'input_admin'=>'SCRIPT',
        );
         */
        $forcedUpdate=$this->forcedUpdate;//强制更新
        $forcedTime=$this->updateTime;//天
        $temp=$res['Result'];
        if(!empty($temp)){
            //开启事务
            $this->startTrans();
            $arr=array(
                'name'=>$temp['Name'],
                'register_no'=>$temp['No'],
                'belong_org'=>$temp['BelongOrg'],
                'oper_name'=>$temp['OperName'],
                'start_date'=>strtotime($temp['StartDate']),
                'end_date'=>strtotime($temp['EndDate']),
                'status'=>$temp['Status'],
                'province'=>$temp['Province'],
                'update_date'=>strtotime($temp['UpdatedDate']),
                'credit_code'=>$temp['CreditCode'],
                'register_capi'=>$temp['RegistCapi'],
                'econkind'=>$temp['EconKind'],
                'industry'=>$temp['Industry']['Industry'],
                'sub_industry'=>$temp['Industry']['SubIndustry'],
                'address'=>$temp['Address'],
                'scope'=>$temp['Scope'],
                'term_start'=>strtotime($temp['TermStart']),
                'term_end'=>strtotime($temp['TeamEnd']),
                'check_date'=>strtotime($temp['CheckDate']),
                'phone_number'=>$temp['ContactInfo']['PhoneNumber'],
                'email'=>$temp['ContactInfo']['Email'],
                'website_name'=>$temp['ContactInfo']['WebSite'][0]['Name'],
                'website_url'=>$temp['ContactInfo']['WebSite'][0]['Url'],
                'input_time'=>CORE_TIME,
                'input_admin'=>'SCRIPT',
            );
            $oneRow=$this->select("id,input_time,update_time")->where("name='{$temp['Name']}'")->getRow();
            if(isset($oneRow['id'])){//有记录
                unset($arr['input_time']);
                unset($arr['input_admin']);
                $arr['update_time']=CORE_TIME;
                $arr['update_admin']='SCRIPT';
                if($forcedUpdate){//强制更新
                    if($this->where("id={$oneRow['id']}")->update($arr)){
//                        $customerBase=true;
//                        p('customerBase更新成功1');
                    }else{
                        //p('customerBase更新失败1');
                        $this->rollback();
                        return false;
                    }
                }else{
                    if((isset($oneRow['update_time'])&&CORE_TIME>($oneRow['update_time']+$forcedTime*86400))||(isset($oneRow['input_time'])&&CORE_TIME>($oneRow['input_time']+$forcedTime*86400))){
                        if($this->where("id={$oneRow['id']}")->update($arr)){
//                            $customerBase=true;
//                            p('customerBase更新成功2');
                        }else{
//                            $customerBase=false;
//                            p('customerBase更新失败2');
                            $this->rollback();
                            return false;
                        }
                    }
                }
                if(!empty($temp['Partners'])) {
                    if (M("qapp:partnersBase")->updateAll($res['Result']['Partners'], $oneRow['id'])) {
//                        $partnersBase = true;
//                        p('partnersBase更新成功');
                    } else {
//                        $partnersBase = false;
//                        p('partnersBase更新失败');
                        $this->rollback();
                        return false;
                    }
                }
                if(!empty($temp['Employees'])){
                    if(M("qapp:employeesBase")->updateAll($res['Result']['Employees'],$oneRow['id'])){
//                        $employeesBase=true;
//                        p('employeesBase更新成功');
                    }else{
//                        $employeesBase=false;
//                        p('employeesBase更新失败');
                        $this->rollback();
                        return false;
                    }
                }
                if(!empty($temp['ChangeRecords'])){
                    if(M("qapp:changeRecordsBase")->updateAll($res['Result']['ChangeRecords'],$oneRow['id'])){
//                        $changeRecordsBase=true;
//                        p('changeRecordsBase更新成功');
                    }else{
//                        $changeRecordsBase=false;
//                        p('changeRecordsBase更新失败');
                        $this->rollback();
                        return false;
                    }
                }
                if(!empty($temp['Branches'])){
                    if(M("qapp:branchesBase")->updateAll($res['Result']['Branches'],$oneRow['id'])){
//                        $branchesBase=true;
//                        p('branchesBase更新成功');
                    }else{
//                        $branchesBase=false;
//                        p('branchesBase更新失败');
                        $this->rollback();
                        return false;
                    }
                }
                $this->commit();
            }else{//没有记录
                    if($this->add($arr)){
//                        p('customerBase插入成功');
                    }else{
//                        p('customerBase插入失败');
                        $this->rollback();
                        return false;
                    }
                    $c_id=$this->getLastID();
                    if(!empty($temp['Partners'])){
                        if(M("qapp:partnersBase")->insertAll($res['Result']['Partners'],$c_id)){
//                            p('partnersBase插入成功');
                        }else{
//                            p('partnersBase插入失败');
                            $this->rollback();
                            return false;
                        }
                    }
                    if(!empty($temp['Employees'])){
                        if(M("qapp:employeesBase")->insertAll($res['Result']['Employees'],$c_id)){
//                            p('employeesBase插入成功');
                        }else{
//                            p('employeesBase插入失败');
                            $this->rollback();
                            return false;
                        }
                    }
                    if(!empty($temp['ChangeRecords'])){
                        if(M("qapp:changeRecordsBase")->insertAll($res['Result']['ChangeRecords'],$c_id)){
//                            p('changeRecordsBase插入成功');
                        }else{
//                            p('changeRecordsBase插入失败');
                            $this->rollback();
                            return false;
                        }
                    }
                    if(!empty($temp['Branches'])){
                        if(M("qapp:branchesBase")->insertAll($res['Result']['Branches'],$c_id)){
//                            p('branchesBase插入成功');
                        }else{
//                            p('branchesBase插入失败');
                            $this->rollback();
                            return false;
                        }
                    }
                }
            $this->commit();
            return true;
            //showTrace();exit;
        }else{
            return false;
        }
    }

    public function updateAll($partners=[],$c_id=0){
        if($c_id<1||count($partners)<1){
            return false;
        }
        $this->startTrans();
        $this->where("c_id=$c_id")->update(array('is_enable'=>0));
        if($this->insertAll($partners,$c_id)){
            $this->commit();
            return true;
        }else{
            $this->rollback();
            return false;
        }
    }

    public function selectAll($id){
        /**
        * $arr=array(
        'name'=>$temp['Name'],
        'register_no'=>$temp['No'],
        'belong_org'=>$temp['BelongOrg'],
        'oper_name'=>$temp['OperName'],
        'start_date'=>strtotime($temp['StartDate']),
        'end_date'=>strtotime($temp['EndDate']),
        'status'=>$temp['Status'],
        'province'=>$temp['Province'],
        'update_date'=>strtotime($temp['UpdatedDate']),
        'credit_code'=>$temp['CreditCode'],
        'register_capi'=>$temp['RegistCapi'],
        'econkind'=>$temp['EconKind'],
        'industry'=>$temp['Industry']['Industry'],
        'sub_industry'=>$temp['Industry']['SubIndustry'],
        'address'=>$temp['Address'],
        'scope'=>$temp['Scope'],
        'term_start'=>strtotime($temp['TermStart']),
        'term_end'=>strtotime($temp['TeamEnd']),
        'check_date'=>strtotime($temp['CheckDate']),
        'phone_number'=>$temp['ContactInfo']['PhoneNumber'],
        'email'=>$temp['ContactInfo']['Email'],
        'website_name'=>$temp['ContactInfo']['WebSite'][0]['Name'],
        'website_url'=>$temp['ContactInfo']['WebSite'][0]['Url'],
        'input_time'=>CORE_TIME,
        'input_admin'=>'SCRIPT',
        );
        */
        $tmp=$this->select('id,name,register_no,belong_org,oper_name,start_date,end_date,status,province,update_date,credit_code,register_capi,econkind,industry,sub_industry,address,scope,term_start,term_end,check_date,phone_number,email,website_name,website_url')->where("id=$id")->getRow();
        $tmp['start_date']=$this->checkTime($tmp['start_date']);
        $tmp['end_date'] = $this->checkTime($tmp['end_date']);
        $tmp['update_date'] = $this->checkTime($tmp['update_date']);
        $tmp['term_start']= $this->checkTime($tmp['term_start']);
        $tmp['term_end'] = $this->checkTime($tmp['term_end']);
        $tmp['check_date'] = $this->checkTime($tmp['check_date']);
        if(!empty($tmp)){
            return array('err'=>0,'data'=>$tmp);
        }else{
            return array('msg'=>'服务器繁忙,请稍后再试！','err'=>7);
        }
    }

    public function selectOrNot($name){
        if($oneRow=$this->checkName($name)){
            /**
             * $arr=array(
            'name'=>$temp['Name'],
            'register_no'=>$temp['No'],
            'belong_org'=>$temp['BelongOrg'],
            'oper_name'=>$temp['OperName'],
            'start_date'=>strtotime($temp['StartDate']),
            'end_date'=>strtotime($temp['EndDate']),
            'status'=>$temp['Status'],
            'province'=>$temp['Province'],
            'update_date'=>strtotime($temp['UpdatedDate']),
            'credit_code'=>$temp['CreditCode'],
            'register_capi'=>$temp['RegistCapi'],
            'econkind'=>$temp['EconKind'],
            'industry'=>$temp['Industry']['Industry'],
            'sub_industry'=>$temp['Industry']['SubIndustry'],
            'address'=>$temp['Address'],
            'scope'=>$temp['Scope'],
            'term_start'=>strtotime($temp['TermStart']),
            'term_end'=>strtotime($temp['TeamEnd']),
            'check_date'=>strtotime($temp['CheckDate']),
            'phone_number'=>$temp['ContactInfo']['PhoneNumber'],
            'email'=>$temp['ContactInfo']['Email'],
            'website_name'=>$temp['ContactInfo']['WebSite'][0]['Name'],
            'website_url'=>$temp['ContactInfo']['WebSite'][0]['Url'],
            'input_time'=>CORE_TIME,
            'input_admin'=>'SCRIPT',
            );
             */
            /**
             * 2017年3月9日17:23:56
             * 其实下面的这个在insertAll也有判断，算作双保险吧，其实是我逻辑             *
             */
            $forcedTime=$this->updateTime;
            if($this->forcedUpdate){//强制更新
                $tmp = $this->getQichacha($name);

            }elseif(($oneRow['update_time']>0&&CORE_TIME>($oneRow['update_time']+$forcedTime*86400))||($oneRow['input_time']>0&&CORE_TIME>($oneRow['input_time']+$forcedTime*86400))){
               //过期更新
                $tmp = $this->getQichacha($name);
            }
        }else{
            $tmp = $this->getQichacha($name);
        }
        if(!empty($tmp)) return $tmp;
        if(isset($oneRow['id'])&&$oneRow['id']>0){
            return $this->selectAll($oneRow['id']);
        }
    }

    public function checkName($name){
        $oneRow=$this->select("id,input_time,update_time")->where("name='$name'")->getRow();
        if(!empty($oneRow)){
            return $oneRow;
        }
        return false;
    }

    public function getQichacha($name){
        $apiKey = $this->apiKey;//获取企查查接口数据key
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://i.yjapi.com/ECI/GetDetailsByName?key=$apiKey&keyword=$name");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $output = curl_exec($ch);
        curl_close($ch);
        $ch = curl_init();
        $res = json_decode($output,true);
        if($res['Status']==201){
            return array('err'=>2,'msg'=>'没有相关数据');
        }elseif($res['Status']==200){
            $this->insertAll($res);
            usleep(20);
            if(!$oneRow=$this->checkName($name)){
                return array('err'=>8,'msg'=>'服务正在维护,请稍后再试！');
            }
        }else{
            return array('err'=>8,'msg'=>'服务正在维护,请稍后再试！');
        }
    }

    public function checkTime($time){
        if($time>0){
            return date("Y-m-d",$time);
        }
        return $time;
    }
}