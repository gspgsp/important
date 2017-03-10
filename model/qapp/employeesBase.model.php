<?php
class employeesBaseModel extends model
{
    //初始化模型
    public function __construct()
    {
        parent::__construct(C('db_default'), 'employees_base');
    }

    public function insertAll($employees=[],$c_id=0){
        /**
         * ['Name'=>'','Job'=>'','CerNo'=>'','ScertName'=>'']
         */
        if($c_id<1||count($employees)<1){
            return false;
        }
        $this->startTrans();
        foreach($employees as $row){  
            if(!$this->add(array(
                'c_id'=>$c_id,
                'name'=>$row['Name'],
                'job'=>$row['Job'],
                'cret_no'=>$row['CerNo'],
                'scert_name'=>$row['ScertName'],
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

    public function updateAll($employees=[],$c_id=0){
        if($c_id<1||count($employees)<1){
            return false;
        }
        $this->startTrans();
        $this->where("c_id=$c_id and is_enable=1")->update(array('is_enable'=>0,'update_time'=>CORE_TIME,'update_admin'=>'SCRIPT'));
        if($this->insertAll($employees,$c_id)){
            $this->commit();
            return true;
        }else{
            $this->rollback();
            return false;
        }
    }

}