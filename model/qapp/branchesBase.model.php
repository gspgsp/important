<?php
class branchesBaseModel extends model
{
    //初始化模型
    public function __construct()
    {
        parent::__construct(C('db_default'), 'branches_base');
    }

    public function insertAll($branches=[],$c_id=0){
        /**
         * ['RegNo'=>'','Name'=>'','BelongOrg'=>'']
         */
        if($c_id<1||count($branches)<1){
            return false;
        }
        $this->startTrans();
        foreach($branches as $row){
            if(!$this->add(array(
                'c_id'=>$c_id,
                'reg_no'=>$row['RegNo'],
                'name'=>$row['Name'],
                'belong_org'=>$row['BelongOrg'],
                'input_time'=>CORE_TIME,
                'input_admin'=>'SCRIPT',
            ))){
                $this->rollback();
                return false;
            }
        }
        $this->commit();
        return true;
    }

    public function updateAll($branches=[],$c_id=0){
        if($c_id<1||count($branches)<1){
            return false;
        }
        $this->startTrans();
        $this->where("c_id=$c_id")->update(array('is_enable'=>0,'update_time'=>CORE_TIME,'update_admin'=>'SCRIPT'));
        if($this->insertAll($branches,$c_id)){
            $this->commit();
            return true;
        }else{
            $this->rollback();
            return false;
        }
    }

}