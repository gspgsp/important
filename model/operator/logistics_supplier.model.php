<?php
/**
 * Created by PhpStorm.
 * User:  yjy
 * Date: 2017/2/22
 * Time: 10:46
 */

class logistics_supplierModel extends Model{

   public function __construct()
   {
       parent::__construct(C('default'), 'logistics_supplier');

   }

    /**
     * 根据供应商名称查询是否已有改供应商
     * @param $supplier_name  供应商名称
     * @return mixed
     */
    public function supplierUnique($supplier_name){
     $this->db->model('logistics_supplier')->where('supplier_name='.$supplier_name)->select('supplier_name')->getAll();
     showTrace();
    }

    /**
     * 查询所有供应商信息
     * @return mixed
     */
    public function getSupplierInfo(){

        return $info=$this->model('logistics_supplier')->select('*')->getAll();

    }
}