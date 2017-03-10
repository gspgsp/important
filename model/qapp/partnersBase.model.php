<?php
class partnersBaseModel extends model
{
    //初始化模型
    public function __construct()
    {
        parent::__construct(C('db_default'), 'partners_base');
    }

    public function insertAll($partners=[],$c_id=0){
        /**
         * ['StockName'=>'','StockType'=>'','StockPercent'=>'','IdentifyType'=>'','IdentifyNo'=>'',
         * 'ShouldCapi'=>'','ShoudDate'=>'','InvestType'=>'','InvestName'=>'','RealCapi'=>'','CapiDate'=>'','Address'=>''],
         */
        if($c_id<1||count($partners)<1){
            return false;
        }
        $this->startTrans();
        foreach($partners as $row){
            if(!$this->add(array(
                'c_id'=>$c_id,
                'stock_name'=>$row['StockName'],
                'stock_type'=>$row['StockType'],
                'stock_percent'=>$row['StockPercent'],
                'identify_type'=>$row['IdentifyType'],
                'identify_no'=>$row['IdentifyNo'],
                'should_capi'=>$row['ShouldCapi'],
                'shoud_date'=>$row['ShoudDate'],
                'invest_type'=>$row['InvestType'],
                'invest_name'=>$row['InvestName'],
                'real_capi'=>$row['RealCapi'],
                'capi_date'=>$row['CapiDate'],
                'address'=>$row['Address'],
                'input_time'=>CORE_TIME,
            ))){
                $this->rollback();
                return false;
            }
        }
        $this->commit();
        return true;
    }

    public function updateAll($partners=[],$c_id=0){
        if($c_id<1||count($partners)<1){
            return false;
        }
        $this->startTrans();
        $this->where("c_id=$c_id and is_enable=1")->update(array('is_enable'=>0,'update_time'=>CORE_TIME,'update_admin'=>'SCRIPT'));
        if($this->insertAll($partners,$c_id)){
            $this->commit();
            return true;
        }else{
            $this->rollback();
            return false;
        }
    }


}