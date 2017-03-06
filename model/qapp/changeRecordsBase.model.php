<?php
class changeRecordsBaseModel extends model
{
    //初始化模型
    public function __construct()
    {
        parent::__construct(C('db_default'), 'change_records_base');
    }

    public function insertAll($changeRecords=[],$c_id=0){
        /**
         * ['ProjectName'=>'','BeforeContent'=>'','AfterContent'=>'','ChangeDate'=>'']
         */
        if($c_id<1||count($changeRecords)<1){
            return false;
        }
        $this->startTrans();
        foreach($changeRecords as $row){
            if(!$this->add(array(
                'c_id'=>$c_id,
                'project_name'=>$row['ProjectName'],
                'before_content'=>$row['BeforeContent'],
                'after_content'=>$row['AfterContent'],
                'change_date'=>strtotime($row['ChangeDate']),
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

    public function updateAll($changeRecords=[],$c_id=0){
        if($c_id<1||count($changeRecords)<1){
            return false;
        }
        $this->startTrans();
        $this->where("c_id=$c_id")->update(array('is_enable'=>0,'update_time'=>CORE_TIME,'update_admin'=>'SCRIPT'));
        if($this->insertAll($changeRecords,$c_id)){
            $this->commit();
            return true;
        }else{
            $this->rollback();
            return false;
        }
    }

}