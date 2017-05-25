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
     * 查询所有供应商信息
     * @return mixed
     */
    public function getSupplierInfo(){

        return $info=$this->model('logistics_supplier')->select('*')->getAll();

    }
}