<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2016/6/27
 * Time: 11:47
 */

class concernedProductModel extends Model{
    public function __construct() {
        parent::__construct(C('db_default'), 'concerned_product');
    }

    /**关注列表
     * 根据user_id查询用户关注信息
     * @param $where
     */
    public function getConcernedList($where){

        return  $this->from('concerned_product as cp')
            ->leftjoin('factory f','f.f_name=cp.factory_name')
            ->leftjoin('product p','f.fid=p.f_id')
            ->leftjoin('purchase pur','pur.p_id=p.id')
            ->where($where)
            ->group ('cp.id')
            ->order('pur.update_time DESC')
            ->select('cp.id,p.id as pid,pur.unit_price,cp.product_name,cp.model,cp.product_name,cp.factory_name,cp.status,pur.update_time')
            //->limit('4')
            ->getAll();

    }


}